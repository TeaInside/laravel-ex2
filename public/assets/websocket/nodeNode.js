var fs = require('fs');
var http = require('https');
/*
var options = {
  key: fs.readFileSync('key/sweedx.com.key'),
  cert: fs.readFileSync('key/sweedx.com.crt'),
  requestCert: true,
  csr: [ fs.readFileSync('key/sweedx.com.csr') ]
};
*/
var privateKey = fs.readFileSync('sslcer/sweedx.com.key', 'utf8');
var certificate = fs.readFileSync('sslcer/sweedx.com.crt', 'utf8')
var credentials = {key: privateKey, cert: certificate};
var port = 8090;

var app = http.createServer(credentials,handler);
var io = require('socket.io').listen(app);

app.listen(port);


function handler (req, res) {
  fs.readFile(__dirname + '/index.html',
  function (err, data) {
    if (err) {
      res.writeHead(500);
      return res.end('Error loading index.html');
    }
    res.writeHead(200);
    res.end(data);
  });
}

var clients =[], clients_total=0;

/*
io.use(function(socket, next) {
  var handshakeData = socket.request;
  // make sure the handshake data looks good as before
  // if error do this:
    // next(new Error('not authorized');
  // else just call next
  next();
});


			socket.configure(function (){
			  socket.set('authorization', function (handshakeData, callback) {
				// findDatabyip is an async example function
				findDatabyIP(handshakeData.address.address, function (err, data) {
				  if (err) return callback(err);

				  if (data.authorized) {
					handshakeData.foo = 'bar';
					for(var prop in data) handshakeData[prop] = data[prop];
					callback(null, true);
				  } else {
					callback(null, false);
				  }
				}) 
			  });
			});
*/			
			
			
io.on('connection', function (socket) {

  
  //Clients counter
  ++clients_total;
  //socket.emit('users_count', clients_total);
  
	//Store Client data, in rooms: https://github.com/Automattic/socket.io/wiki/Rooms
	
	socket.on('create_room', function(room) {
		socket.join('market_id-'+room);
		
		//console.log( io.sockets.clients('market_id-'+room));		//Show array of clientss

	});

	Object.keys(io.sockets.manager.rooms).map(function(key){return console.log(io.sockets.manager.rooms[key]); })
	
	//To get socket by socket ID you may try this:
	//var socket = io.sockets.connected[socketId];
	
	/*
	socket.on('storeClientInfo', function (data) {
		var clientInfo = new Object();
		clientInfo.marketID         = data.market_id;
		clientInfo.clientID     = socket.id;
		clients.push(clientInfo);
		
		Object.keys(clients).map(function(key){return console.log(clients[key]); })
		
		console.log('clients info ');
		for(var key in clients) {
			var value = clients[key];
			console.log('clients[key].marketID: '+clients[key].marketID+', clients[key].clientID: '+clients[key].clientID);
			
		}
	
	});
	
  
	


	*/

	socket.on('disconnect', function (data) {
		/*
		for( var i=0, len=clients.length; i<len; ++i ){
			var c = clients[i];

			if(c.clientId == socket.id){
				clients.splice(i,1);
				break;
			}
		}
		*/
		--clients_total;

	});
	
  socket.emit('news', { hello: 'world' });

  
/*
http://socket.io/docs/#
*/  
  //Trading handler
  socket.on( 'doTrade', function( data ) {
    //io.emit( 'doTrade', data );
    //socket.emit( 'doTrade', data );
	
	
	//Broadcast trading info to everyone
	io.sockets.emit( 'doTrade', data );		//shown for all users/broadcast to all
	
	//Show trading history for only users in same room/market
	if (data.history_trade !== undefined)
		io.sockets.in('market_id-'+data.market_id).emit('doTradeHistory', data.history_trade) //emit to 'room' except this socket (io.sockets.in broadcasts to all sockets in the given room)
	
	
	console.log('trading goes -> '+data);
  });
  
    //Trading YourOrders handler
  socket.on( 'doTradeUser', function( data ) {
    //io.emit( 'doTrade', data );
    
	console.log('doTradeUser market_id: '+data.market_id);

		//socket.emit( 'doTradeUser', data );	//shown for the specific client/user who made the call
	
/*	
* Send message to the room1. It broadcasts the data to all 
the socket clients which are connected to the room1 

io.sockets.in('room1').emit('function', {foo:bar});
*/
		socket.emit( 'doTradeUser', data );	//shown for the specific client/user who made the call
		
		//io.sockets.in('market_id-'+data.market_id).emit('doTradeUser', data) //emit to 'room' except this socket (io.sockets.in broadcasts to all sockets in the given room)
		//socket.broadcast.to('market_id-'+data.market_id).emit('doTradeUser', data) //emit to 'room' except this socket (socket.broadcast.to broadcasts to all sockets in the given room, except to the socket on which it was called)

/*		
* Send message to the room1. It broadcasts the data to all 
   the socket clients which are connected to the room1 

io.sockets.in('room1').emit('function', {foo:bar});
*/
	//io.sockets.emit( 'doTrade', data );
	//console.log('your orders goes -> '+data);
	console.log('socket.rooms -> '+io.sockets.manager.rooms);
	
  });
  
});

/*
	//https://stackoverflow.com/questions/10058226/send-response-to-all-clients-except-sender-socket-io
	
 // send to current request socket client
 socket.emit('message', "this is a test");

 // sending to all clients, include sender
 io.sockets.emit('message', "this is a test");

 // sending to all clients except sender
 socket.broadcast.emit('message', "this is a test");

 // sending to all clients in 'game' room(channel) except sender
 socket.broadcast.to('game').emit('message', 'nice game');

  // sending to all clients in 'game' room(channel), include sender
 io.sockets.in('game').emit('message', 'cool game');

 // sending to individual socketid
 io.sockets.socket(socketid).emit('message', 'for your eyes only');
 */