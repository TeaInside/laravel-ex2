var fs = require('fs');
var options = {
  key: fs.readFileSync('key/www_cryptex_biz.key'),
  cert: fs.readFileSync('key/www_cryptex_biz.crt'),
  requestCert: true,
  ca: [ fs.readFileSync('key/www_cryptex_biz.ca') ]
};
var app = require('https').createServer(options,handler);
var io = require('socket.io').listen(app);

app.listen(8080);


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

io.on('connection', function (socket) {
  socket.emit('news', { hello: 'world' });
  socket.on('my other event', function (data) {
    console.log(data);
  });
  socket.on( 'doTrade', function( data ) {
    //io.emit( 'doTrade', data );
    socket.emit( 'doTrade', data );
  });
});