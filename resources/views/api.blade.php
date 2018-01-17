@extends('layouts.default')
@section('content')

<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
		<h2><a href="<?=url('/', $parameters = array(), $secure = null);?>">{{{ Config::get('config_custom.company_name') }}} API</a></h2>
		<div id="fees_trade" class="content-body">
			
			<div class="public_content">

				
				
				
				
		<div class="panel-group" id="accordion">
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
				<h4 class="panel-title">
					<span class="glyphicon glyphicon-minus"></span>
						Public API
				</h4>
			  </a>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in">
			  <div class="panel-body">
				<strong>Public Methods</strong><br />
				Public Api methods do not require the use of an API key and can be accessed via the GET method. 
				<br />
				<br />
			
				<strong>General Market Data (All Markets):</strong> <br />

				<?=url('/', $parameters = array(), $secure = null);?>/page/api?method=allmarket <br /><br />

				<strong>General Market Data (Single Market):</strong> <br />

				<?=url('/', $parameters = array(), $secure = null);?>/page/api?method=singlemarket&marketid={MARKET ID} <br /><br />

				<strong>Get last price (Single Market):</strong> <br />
				<?=url('/', $parameters = array(), $secure = null);?>/page/api?method=lastprice&marketid={MARKET ID}  <br /><br />
				
				<strong>Last 24 hour stats:</strong> <br />
				<?=url('/', $parameters = array(), $secure = null);?>/page/api?method=allmarket24h  <br /><br />

				<strong>Last 24 hour stats (Single Market):</strong> <br />
				<?=url('/', $parameters = array(), $secure = null);?>/page/api?method=singlemarket24h&marketid={MARKET ID}  <br /><br />
			  </div>
			</div>
		  </div>
		  <div class="panel panel-default">
			<div class="panel-heading">
			  
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
					<h4 class="panel-title">
					<span class="glyphicon glyphicon-plus"></span>
					  Authenticated API
					</h4>
				</a>
			  
			</div>
			<div id="collapseTwo" class="panel-collapse collapse">
			  <div class="panel-body">
				 <strong>Authenticated Methods</strong>
				 <br >
							Authenticated methods require the use of an API key and can only be accessed via the POST method.<br /><br />

							<strong>URL</strong> - The URL you will be posting to is:<strong> <?=url('/', $parameters = array(), $secure = null);?>/page/api?method=METHOD&amp;key=KEY&amp;sign=SIGN </strong><br />
							Ex: <?=url('/', $parameters = array(), $secure = null);?>/page/api?method=getmarkets&amp;sign=USERNAME&amp;key=PASSWORD <br />

							
							<strong>sign</strong> - Your username<br />
							
							<strong>key</strong> - Your password<br />


							<strong>Other Variables</strong><br />

							<strong>method</strong> - The method from the list below which you are accessing.<br />

							<strong>General Return Values</strong><br />

							<strong>success</strong> - Either a 1 or a 0. 1 Represents sucessful call, 0 Represents unsuccessful<br />
							<strong>error </strong>- If unsuccessful, this will be the error message<br />
							<strong>return</strong> - If successful, this will be the data returned<br /><br /><br />

							<strong>Method List</strong> <br /><br /><hr> 
							Method:<strong>getmarkets</strong><br />
							Inputs:<strong>n/a</strong><br />  
							Outputs: Array of Active Markets <br />
							<table width="100%">
								<tbody><tr style="background: #eeeeee;"><td><strong>marketid</strong></td><td>Integer value representing a market</td></tr>
								<tr><td><strong>label</strong></td><td>Name for this market, for example: AMC/BTC</td></tr>
								<tr style="background: #eeeeee;"><td><strong>primary_currency_code</strong></td><td>Primary currency code, for example: AMC</td></tr>
								<tr><td><strong>primary_currency_name</strong></td><td>	Primary currency name, for example: AmericanCoin</td></tr>
								<tr style="background: #eeeeee;"><td><strong>secondary_currency_code</strong></td><td>	Secondary currency code, for example: BTC</td></tr>
								<tr><td><strong>secondary_currency_name</strong></td><td>	Secondary currency name, for example: BitCoin</td></tr>
								<tr style="background: #eeeeee;"><td><strong>last_trade</strong></td><td>Last trade price for this market</td></tr>
								<tr><td><strong>high_trade</strong></td><td>	24 hour highest trade price in this market</td></tr>
								<tr style="background: #eeeeee;"><td><strong>low_trade</strong></td><td>	24 hour lowest trade price in this market</td></tr>
								<tr><td><strong>created</strong></td><td>	Datetime (EST) the market was created</td></tr>
							</tbody></table>
							<br />
							<hr>
							Method:<strong>getwallets</strong><br />
							Inputs:<strong>n/a</strong><br />  
							Outputs: Array of Active Wallets <br />
							<table width="100%">
								<tbody><tr style="background: #eeeeee;"><td><strong>currencyid</strong></td><td>Integer value representing a wallet</td></tr>
								<tr><td><strong>name</strong></td><td>Name for this wallet, for example: Bitcoin</td></tr>
								<tr style="background: #eeeeee;"><td><strong>code</strong></td><td>	Currency code, for example: BTC</td></tr>
								<tr><td><strong>withdrawfee</strong></td><td>Fee charged for withdrawals of this currency</td></tr>
							</tbody></table>
							<br />
							<hr>
							Method:<strong>mydeposits</strong><br />
							Inputs:<strong>n/a</strong><br />  
							Outputs: Array of Deposits on your account <br />
							<table width="100%">
								<tbody><tr style="background: #eeeeee;"><td><strong>currencyid</strong></td><td>Integer value representing a wallet</td></tr>
								<tr><td><strong>created</strong></td><td>	The time the activity posted</td></tr>
								<tr style="background: #eeeeee;"><td><strong>updated</strong></td><td>The time the activity updated</td></tr>
								<tr><td><strong>address</strong></td><td>	Address to which the deposit posted was sent</td></tr>
								<tr style="background: #eeeeee;"><td><strong>amount</strong></td><td>	Amount of transaction (Not including any fees)</td></tr>
								<tr><td><strong>transactionid</strong></td><td>Network Transaction ID (If available)</td></tr>
							</tbody></table>
							<br />
							<hr>
							Method:<strong>mywithdraws</strong><br />
							Inputs:<strong>n/a</strong><br />  
							Outputs: Array of Withdraws on your account <br />
							<table width="100%">
								<tbody><tr style="background: #eeeeee;"><td><strong>currencyid</strong></td><td>Integer value representing a wallet</td></tr>
								<tr><td><strong>created</strong></td><td>	The time the activity posted</td></tr>
								<tr style="background: #eeeeee;"><td><strong>toaddress</strong></td><td>	Address to which the withdraws posted was received</td></tr>
								<tr><td><strong>amount</strong></td><td>	Amount of transaction (Not including any fees)</td></tr>
								<tr style="background: #eeeeee;"><td><strong>feeamount</strong></td><td>	Fee (If any) Charged for this Transaction (Generally only on Withdrawals)</td></tr>
								<tr><td><strong>receiveamount</strong></td><td>	Amount of transaction was received</td></tr>
								<tr style="background: #eeeeee;"><td><strong>transactionid</strong></td><td>Network Transaction ID (If available)</td></tr>
							</tbody></table>
							<br />
							<hr>
							Method:<strong>mytransfers</strong><br />
							Inputs:<strong>n/a</strong><br />  
							Outputs: Array of Transfers on your account <br />
							<table width="100%">
								<tbody><tr style="background: #eeeeee;"><td><strong>currency</strong></td><td>Name representing a wallet</td></tr>
								<tr><td><strong>time</strong></td><td>	The time the activity created</td></tr>
								<tr style="background: #eeeeee;"><td><strong>sender</strong></td><td>	Username sending transfer</td></tr>
								<tr><td><strong>receiver</strong></td><td>	Username receiving transfer</td></tr>
								<tr style="background: #eeeeee;"><td><strong>amount</strong></td><td>Amount of transaction</td></tr>
								</tbody></table> 
							<br />
							<hr>
							Method:<strong>getmydepositaddresses</strong><br />
							Inputs:<strong>n/a</strong><br />  
							Outputs: Array <br />
							<table width="100%">
								<tbody><tr style="background: #eeeeee;"><td><strong>coincode</strong></td><td>Type of wallet</td></tr>
								<tr><td><strong>despositaddress</strong></td><td>Your deposit address</td></tr>
								</tbody></table> 
							<br />
							<hr>
							Method:<strong>allmyorders</strong><br />
							Inputs:<strong>n/a</strong><br />  
							Outputs: Array of all open orders for your account. <br />
							<table width="100%">
								<tbody><tr style="background: #eeeeee;"><td><strong>orderid</strong></td><td>Order ID for this order</td></tr>
								<tr><td><strong>marketid</strong></td><td>	The Market ID this order was created for</td></tr>
								<tr style="background: #eeeeee;"><td><strong>created</strong></td><td>Datetime the order was created</td></tr>
								<tr><td><strong>ordertype</strong></td><td>Type of order (Buy/Sell)</td></tr>
								<tr style="background: #eeeeee;"><td><strong>price</strong></td><td>The price per unit for this order</td></tr>
								<tr><td><strong>fromvalue</strong></td><td>Amount from sender</td></tr>
								<tr style="background: #eeeeee;"><td><strong>tovalue</strong></td><td>Amount which receiver was received</td></tr>
								</tbody></table> 
							<br />
							<hr>
							Method:<strong>myorders</strong><br />
							Inputs:<strong>marketid</strong>	Market ID for which you are querying<br />  
							Outputs: Array of your orders for this market listing your current open sell and buy orders. <br />
							<table width="100%">
								<tbody><tr style="background: #eeeeee;"><td><strong>orderid</strong></td><td>Order ID for this order</td></tr>
								<tr><td><strong>created</strong></td><td>Datetime the order was created</td></tr>
								<tr style="background: #eeeeee;"><td><strong>ordertype</strong></td><td>Type of order (Buy/Sell)</td></tr>
								<tr><td><strong>price</strong></td><td>The price per unit for this order</td></tr>
								<tr style="background: #eeeeee;"><td><strong>fromvalue</strong></td><td>Amount from sender</td></tr>
								<tr><td><strong>tovalue</strong></td><td>Amount which receiver was received</td></tr>
								</tbody></table> 
							<br />
							<hr>
							
							Example PHP Code for making API calls: <br />
							<div>
								<pre> 	
				function api($method, array $req = array()) {

					$req['key'] = '';  // your password account
					$req['sign'] = '';  // your username account
					$req['method'] = $method;

					// generate the POST data string
					$post_data = http_build_query($req, '', '&amp;');

					$re = file_get_contents('<?=url('/', $parameters = array(), $secure = null);?>/page/api?'. $post_data, true);

					$dec = json_decode($re, true);
					return $dec;
				}
				
				$result = api("getmarkets"); 
				//$result = api("getwallets"); 
				//$result = api("mydeposits"); 
				//$result = api("mywithdraws"); 
				//$result = api("mytransfers"); 
				//$result = api("getmydepositaddresses"); 
				//$result = api("allmyorders"); 
				//$result = api("myorders"); 

				//print_r($result, true);
						</pre>
					</div>
				
				
      </div>
    </div>
  </div>
  <?php
	/*
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
          Push API
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">

		<h4>Get instant notification of trades and buy/sell ticker data</h4>

		<br>
		Using our Push API service you can get information on trades and market data in real time.   Using a client to connect to the service, you can subscribe to channels you wish to receive data on.  You can subscribe to as many channels as you wish.
		<br><br>

		<b>Channel: "trade.X"</b>&nbsp;(X is the Market ID for which you would like to subscribe. For example "trade.3" would be the LTC/BTC market)
		<br><br>
		Data format (event "message"):
		<pre>	{
		"channel": "trade.53",
		"trade": {
		"timestamp": 1412674077,
		"datetime": 2014-10-07 09:27:57 UTC,
		"marketid": "53",
		"marketname": "CAP/BTC",
		"amount": "0.02523500",
		"price": "0.00001060",
		"total": "0.00000027",
		"type": "Sell"
		}
		}
		</pre>

		<br>
		<b>Channel: "ticker.X"</b>&nbsp;(X is the Market ID for which you would like to subscribe. For example "ticker.3" would be the LTC/BTC market)
		<br><br>
		Data format (event "message"):
		<pre>	{
		"channel": "ticker.160",
		"trade": {
		"timestamp": 1412674077,
		"datetime": 2014-10-07 09:27:57 UTC,
		"marketid": "160",
		"topsell": {
		"price": "0.00451039",
		"amount": "12.31881709"
		},
		"topbuy": {
		"price": "0.00450001",
		"amount": "49.28204222"
		}
		}
		}
		</pre>			
		<br><br>
		<h4>Client Libraries</h4>
		You can find client libraries in several programming languages here:   <a href="http://pusher.com/docs/client_libraries" target="_blank">http://pusher.com/docs/client_libraries</a>
		<br><br>
		When connecting to our service you will need to use the following API Key:
		<pre>APP_KEY = '9fb6abdd0c628d95ed0a'
		</pre>
		<br>
		<h4>Sample Client Code</h4>
		<div>
		<pre>	<xmp>
<head>
	<title>Pusher Test</title>
	<script src="<?=url('/', $parameters = array(), $secure = null);?>/assets/js/pusher.min.js"></script>
	<!-- http://js.pusher.com/2.2/pusher.min.js -->
	<script type="text/javascript">
	// Enable pusher logging - don't include this in production
	Pusher.log = function(message) {
	if (window.console && window.console.log) {
	window.console.log(message);
	}
	};

	var pusher = new Pusher("9fb6abdd0c628d95ed0a");
	var channel = pusher.subscribe("trade.33");
	channel.bind("message", function(data) {
	console.log("trade.33",data);
	});

	var channel2 = pusher.subscribe("ticker.33");
	channel2.bind("message", function(data) {
	console.log("ticker.33",data);
	});
	</script>
	</head>
	<body>
		<h2>Test Pusher Client</h2>
		<p>Please press F12 and click tab "Console" to view result</p>
	</body>
</html>
		</xmp>
		</pre>
		</div>
					
      </div>
    </div>
  </div>
  */
  ?>
  
				</div>

			</div> 
		</div>
	</div>
</div>
<script type="text/javascript">
	//$('.collapse').collapse()
	
	$(document).ready(function() {
        
		$('.collapse').on('shown.bs.collapse', function(){
			$(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
		}).on('hidden.bs.collapse', function(){
			$(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
		});
        
	});
</script>
@stop