@extends('layouts.default')
<?php
// Set individual Market title
if ($market_predefined) :?>
	@section('title')
		<?php echo Config::get('config_custom.company_name_domain') . ' - ' . $market_from . ' / ' . $market_to . ' ' . trans('texts.market') ?>
	@stop
	@section('description')
		Sweedx - Swedish Digital E-currency eXchange
	@stop
	
<?php
/*
	//@section('title', 'This is an individual page title')
	//@section('description', 'This is a description')
	*/
endif;
/*
if(Auth::check()) {
echo "<h4>Logged in</h4>";
} else {
echo "<h4>Not logged in</h4>";
}
*/
?>

@section('content')



			
<?php
/*
@if(isset($show_all_markets) && $show_all_markets === true)

	<h2 style="color: red;" >
Sponsored Currencies
</h2>
	
	<?php
	$number_btc = isset($statistic_btc->number_trade)? $statistic_btc->number_trade:0;
	$volume_btc = (isset($statistic_btc->total) && !empty($statistic_btc->total))? sprintf('%.8f',$statistic_btc->total):0;
	$number_ltc = isset($statistic_ltc->number_trade)? sprintf('%.8f',$statistic_ltc->number_trade):0;
	$volume_ltc = (isset($statistic_ltc->total) && !empty($statistic_ltc->total))? sprintf('%.8f',$statistic_ltc->total):0;
	?>
			
	<div class="wrapper-trading buysellform">
		<div class="">
			<div class="item18">
				<div class="success box">BTC Volume:<br><strong><span id="volume_btc"> {{$volume_btc}} </span> BTC</strong></div>
				
			</div>
			<div class="item18">
				<div class="success box">LTC Volume:<br><strong><span id="volume_ltc"> {{$volume_ltc}} </span> LTC</strong></div>
			</div>
			<div class="item18">
				<div class="success box">Number of Trades:<br><strong><span id="number_of_trades"> {{$number_ltc+$number_btc}} </span> BTC</strong></div>
			</div>
		
		</div>

	</div>
@endif
*/
?>

<?php
/*
<div class="container">
  <div class="row">
    <div class="col-xs-4 col-md-4">1</div>
  </div>
  <div class="row">
    <div class="col-xs-8 col-md-8">2</div>
    <div class="col-xs-8 col-md-8">3</div>
    <div class="col-xs-8 col-md-8">4</div>
  </div>
  <div class="row">
    ...
  </div>
</div>
*/
?>
	
<div class="row">
	<div id="market_place">
		<div >

			<div class="col-12-xs col-sm-12 col-lg-12 news-panel">
				<div >
						<div class="bs-component">
							<div class="alert alert-dismissible alert-info">
								<button data-dismiss="alert" class="close" type="button">×</button>
									<a href="https://sweedx.com/post/beta-testing-of-realtime-trading-system">
										Beta testing of realtime trading system. Join us at freenode #sweedx
									</a>
							  </div>
						</div>
				</div>
			</div>



			@if(isset($show_all_markets) && $show_all_markets === true)
				<div class="row">
					<div class="col-12-xs col-sm-12 col-lg-12">
					
							
							<h2 id="nav-pills" style="float: left; margin-top:0px;">BTC - Live Market Data</h2>
							<br />

							
							
						
				
					<?php
					//var_dump($all_markets);
					?>




					<hr class="colorgraph"/>




					<table class="table table-striped table-hover market market_table bootstrap-popup" id="btc_market_table">
						<thead>
							<tr class="header-tb">
								<th data-priority="4">Currency</th>
								<th data-priority="critical">Market</th>
								<th data-priority="critical">Last Price</th>
								<th data-priority="1">% Change</th>
								
								<th data-priority="2">24 H High</th>
								<th data-priority="3">24 H Low</th>
								<th data-priority="critical">24 H Volume</th>
							</tr> 
						</thead>
						<tbody>
						<?php
						//var_dump($all_markets);
						?>
						@foreach($all_markets as $am)
							@if ($am['to'] == 'BTC')
							<tr id="mainCoin-{{$am['market']->id}}">
								<td class="from_name">
									@if(!empty($am['logo']))                        
										<a href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}"><img src="{{asset('')}}/{{$am['logo']}}" class="coin_icon_small" /></a>
									@else
									&nbsp;
									@endif
									
									
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from_name']}}</a>
								</td>
								<td>
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">
									@if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif
									{{$am['from']}}/{{$am['to']}}

									

									</a>
								</td>
								<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}">{{$am['latest_price']}}</a></td>
								<td class="market_change">
									<?php
									if ( sprintf('%.8f',$am['prices']->max)+0 == 0 )
										$coin_max_ = '';
									else
										$coin_max_ = sprintf('%.8f',$am['prices']->max);
									
									if ( sprintf('%.8f',$am['prices']->min)+0 == 0 )
										$coin_min_ = '';
									else
										$coin_min_ = sprintf('%.8f',$am['prices']->min);
									?>
									@if ($am['market_change']['change'] == 0)
										<span class="change" >{{$am['market_change']['change']}}% <i class="fa fa-minus"></i></span>
									@elseif ($am['market_change']['change'] > 0)
										<span class="change up" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-up"></i></span>
									@else ($am['latest_price'] < 0)
										<span class="change down" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-down"></i></span>
									@endif
								</td>
								<td>
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}">@if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
								</td>
								<td>
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}">@if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
								</td>
								<td>
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainVolume-{{$am['market']->id}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
								</td>
							</tr>
							@endif
						@endforeach
						</tbody>
					</table>
					

					<h2 id="nav-pills" style="float: left; margin-top:0px;">LTC - Live Market Data</h2>

					<table  class="table table-striped table-hover market market_table" id="ltc_market_table">
						<thead class="columnSelector-disable">
							<tr class="header-tb">
								<th data-priority="4">Currency</th>
								<th data-priority="critical">Market</th>
								<th data-priority="critical">Last Price</th>
								<th data-priority="1">% Change</th>
								
								<th data-priority="2">24 H High</th>
								<th data-priority="3">24 H Low</th>
								<th data-priority="critical">24 H Volume</th>
							</tr> 
						</thead>
						<tbody>
						@foreach($all_markets as $am)
							@if ($am['to'] != 'BTC')
							<tr id="mainCoin-{{$am['market']->id}}">
								<td class="from_name">
								@if(!empty($am['logo']))                        
										<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}"><img class="coin_icon_small" src="{{asset('')}}/{{$am['logo']}}" /></a>
									@else
									&nbsp;
									@endif
									
									
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from_name']}}</a>
								</td>
								<td>
									@if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}/{{$am['to']}}</a>
								</td>
								

								<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}"> {{$am['latest_price']}}</a></td>
									<?php
									if ( sprintf('%.8f',$am['prices']->max)+0 == 0 )
										$coin_max_ = '';
									else
										$coin_max_ = sprintf('%.8f',$am['prices']->max);
									
									if ( sprintf('%.8f',$am['prices']->min)+0 == 0 )
										$coin_min_ = '';
									else
										$coin_min_ = sprintf('%.8f',$am['prices']->min);
									?>
									
								<td class="market_change">
									@if ($am['market_change']['change'] == 0)
										<span class="change" >{{$am['market_change']['change']}}% <i class="fa fa-minus"></i></span>
									@elseif ($am['market_change']['change'] > 0)
										<span class="change up" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-up"></i></span>
									@else ($am['latest_price'] < 0)
										<span class="change down" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-down"></i></span>
									@endif
								</td>
								<td>
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}"> @if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
								</td>
								<td>
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}"> @if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
								</td>
								<td>
									<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainVolume-{{$am['market']->id}}"> @if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
								</td>
							</tr>
							@endif
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
			@endif

			
			@if($market_predefined)

				<div class="row">
					<div class="col-12-xs col-sm-12 col-lg-12">
					
					<div class="row">

		<div class="row">
          <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="widget-content-blue-wrapper changed-up">
              <div class="widget-content-blue-inner padded">
                <div class="pre-value-block"><i class="fa fa-dashboard"></i> Total Visits</div>
                <div class="value-block">
                  <div class="value-self">10,520</div>
                  <div class="value-sub">This Week</div>
                </div>
                <span class="dynamicsparkline">Loading..</span>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="widget-content-blue-wrapper changed-up">
              <div class="widget-content-blue-inner padded">
                <div class="pre-value-block"><i class="fa fa-user"></i> New Users</div>
                <div class="value-block">
                  <div class="value-self">1,120</div>
                  <div class="value-sub">This Month</div>
                </div>
                <span class="dynamicsparkline">Loading..</span>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="widget-content-blue-wrapper changed-up">
              <div class="widget-content-blue-inner padded">
                <div class="pre-value-block"><i class="fa fa-sign-in"></i> Sold Items</div>
                <div class="value-block">
                  <div class="value-self">275</div>
                  <div class="value-sub">This Week</div>
                </div>
                <span class="dynamicsparkline">Loading..</span>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="widget-content-blue-wrapper changed-up">
              <div class="widget-content-blue-inner padded">
                <div class="pre-value-block"><i class="fa fa-money"></i> Net Profit</div>
                <div class="value-block">
                  <div class="value-self">$9,240</div>
                  <div class="value-sub">Yesterday</div>
                </div>
                <span class="dynamicbars">Loading..</span>
              </div>
            </div>
          </div>
        </div>
      
					</div>
					<div class="row">
						<div class="bs-component">
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
								<div class="panel panel-default">
									<div class="panel-body">
										Basic panel	
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
								<div class="panel panel-default">
									<div class="panel-body">
										Basic panel	
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
								<div class="panel panel-default">
									<div class="panel-body">
										Basic panel	
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
								<div class="panel panel-default">
									<div class="panel-body">
										Basic panel	
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
								<div class="panel panel-default">
									<div class="panel-body">
										Basic panel	
									</div>
								</div>
							</div>
						</div>
					</div>
						@if($enable_trading == 0)
							<div class="alert alert-danger">
								<i class="fa fa-exclamation-triangle fa-2x"></i> <strong>{{{ trans('texts.market_disabled')}}}</strong>
							</div>	
						@endif

						<h2 style="margin-top:0px;"><img width="32" border=0 height="32" src="{{asset('')}}/{{$coinmain_logo}}" /> {{$market_from}}/{{$market_to}} 

							@if ( $url != '' )
								<span class="blockviewer"><a href="{{$url}}" target="_blank">Pool URL</a></span>
							@endif
							@if ( $blockviewer != '' )
								<span class="blockviewer"><a href="{{$blockviewer}}" target="_blank">Block Viewer</a></span>
							@endif
							@if ( $forum != '' )
								<span class="blockviewer"><a href="{{$forum}}" target="_blank">Forum</a></span>
							@endif

						</h2> 









						@if ( $market_from == 'UFO' )
							  <div class="alert alert-danger">UFO Market is closing, please withdraw your coins ASAP!</div>
						@endif

						@if (isset($news) && $news)
							<div class="alert alert-info">
								<strong>{{ $news->title }}</strong>
								{{ $news->content }}
							</div>
						@endif

						@if ( is_array(Session::get('error')) )
							<div class="alert alert-error">{{ head(Session::get('error')) }}</div>
						@elseif ( Session::get('error') )
						  <div class="alert alert-error">{{{ Session::get('error') }}}</div>
						@endif
						@if ( Session::get('success') )
						  <div class="alert alert-success">{{{ Session::get('success') }}}</div>
						@endif

						@if ( Session::get('notice') )
							  <div class="alert">{{{ Session::get('notice') }}}</div>
						@endif
						
						<div class="">
							<div class="item18">
								<div class="success box" id="lastprice-{{{Session::get('market_id')}}}">Last Price:<br><strong><span id="spanLastPrice-{{{Session::get('market_id')}}}">@if(empty($latest_price)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$latest_price)}} @endif</span> {{{ $coinsecond }}}</strong></div>
							</div>
							<div class="item18">
								<div class="success box">24 h High:<br><strong><span id="spanHighPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->max)}} @endif</span> {{{ $coinsecond }}}</strong></div>
							</div>
							<div class="item18">
								<div class="success box">24 h Low:<br><strong><span id="spanLowPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->min)}} @endif</span> {{{ $coinsecond }}}</strong></div>
							</div>
							<div class="item18">
								<div class="success box">24 h Vol:<br><strong><span id="spanVolume-{{{Session::get('market_id')}}}">@if(empty($get_prices->volume)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->volume)}} @endif</span> {{{ $coinsecond }}}</strong></div>
							</div>
							<div class="item25">
								<div class="success box">
									<div data-toggle="tooltip" data-placement="left" title="Total SELL in {{{ $coinsecond }}}">#Sell: <strong><span id="sellorders_total_all_box_{{{Session::get('market_id')}}}"></span> {{{ $coinsecond }}}</strong></div>
									<div data-toggle="tooltip" data-placement="left" title="Total BUY in {{{ $coinmain }}}">#Buy: <strong><span id="buyorders_amount_all_box_{{{Session::get('market_id')}}}"></span> {{{ $coinmain }}}</strong></div>
									
								</div>
							</div>
						</div>

							
						<ul class="nav nav-tabs" id="chart_marketdepth_tab" role="tablist" >
							<li><a href="#orderdepth" role="tab" data-toggle="tab" data="order-chart" onclick="javascript: drawOrderDepthChart();">Order Depth</a></li>
							<li class="right active"><a href="#chartdiv" role="tab" data-toggle="tab" data="price-volume-chart">Price / Volume</a></li>
							
						</ul>

						<div class="tab-content chart_marketdepth">
							<div class="tab-pane active" id="chartdiv" style="width:100%; height:400px;"><div id="chartLoadingBox">Loading...</div></div>
							<div class="tab-pane" id="orderdepth" style="width:100%; height:400px;"></div>
						</div>


							<!-- Sell/Buy -->
							<?php
							/*
							@if ( Auth::guest() )
							@else
							*/
							?>
								<div class="wrapper-trading buysellform">
									<div class="inblock-left">
										@include('blocks.buyform')
									</div>	
									<div class="inblock-right">		
										@include('blocks.sellform')	
									</div>
								</div>
							<?php
							//@endif
							?>

						<div class="wrapper-trading buysellorders">
							<div class="inblock-left">
								@include('blocks.buyorders')
							</div>	
							<div class="inblock-right">		
								@include('blocks.sellorders')
							</div>
						</div>
							<!-- Your Active Orders  -->
						@if ( Auth::guest() )
						@else
							@include('blocks.yourorders')
						@endif
						
						<!-- Trade history -->
						@include('blocks.tradehistory')				

					
						<!-- Assets for charts -->
						{{ HTML::style('assets/amcharts/style.css') }}
						{{ HTML::script('assets/amcharts/amcharts.js') }}
						{{ HTML::script('assets/amcharts/serial.js') }}
						{{ HTML::script('assets/amcharts/amstock.js') }}


						<script type="text/javascript">		
							var getChartURL = "<?php echo action('HomeController@getChart')?>";
							var getMarketID = "<?php echo $market_id ?>";
							var getOrderDepthChart = "<?php echo action('OrderController@getOrderDepthChart')?>";
							var transError = "{{{ trans('texts.error') }}}";
							var coinSecond = "{{{$coinsecond}}}";
						</script>
						{{ HTML::script('assets/js/custom_charts.js') }}
				
					
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
	
{{ HTML::script('assets/js/jquery.tablesorter.js') }}
{{ HTML::script('assets/js/jquery.tablesorter.widgets.js') }}
{{ HTML::script('assets/js/jquery.tablesorter.widgets.columnSelector.js') }}

<script type="text/javascript">







</script>
<!-- <div class="container-fluid">
		<button onclick="testCal()">Test</button>
	</div>  -->


{{ HTML::script('https://cdn.socket.io/socket.io-1.2.0.js') }} 
<?php
      /*
	  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/0.9.16/socket.io.min.js" ></script>
	  
	  {{ HTML::script('assets/websocket/socket.io.min.js') }}
	 */
	  ?>
	  
<script type="text/javascript" charset="utf-8">	


		$(function(){
			window.socket = {};
			<?php /* <?php echo url('/', $parameters = array(), $secure = null);
				//socket = io.connect('https://sweedx.com:8090/',{secure: true});
			*/?>

			
			<?php
			/*
			  // This code works!
			  var socket = new WebSocket("ws://localhost:8080");
			  var socket = new WebSocket('wss://sweedx.com:8090');
			  // This code doesn't work and yells "cross origin!  Not allowed!"
			  var socket = io("ws://localhost:8080");
   		    */
			?>
			//
			//var socket = io('wss://sweedx.com:8090');
			socket = io.connect('<?php echo url('/', $parameters = array(), $secure = true);?>:8090/',{secure: true});

			//socket = io.connect('https://sweedx.com:8090/',{secure: true});
			
			//socket = io.connect('https://sweedx.com:8090/',{secure: true});
			//var socket = io.connect('https://sweedx.com:8090',{secure: true, port:8090});
			
			<?php /* Node server is not running*/ ?>
			socket.on('error', function(exception) {
				showMessageSingle('Socket Error 1 - Live prices is not available. <br />Socket is not connected!', 'error');
			})
			/*
			socket.of('connected', function(exception) {
				showMessageSingle('Socket Error 2 - Live prices not available. <br />Socket is not connected!', 'error');
			})
			*/
			
			socket.on('connect', function(){
				socket.emit('create_room', '<?php echo Session::get('market_id')?>');
			});
			
			socket.on('users_count', function(data){
				$('#client_count').text(data).addClassDelayRemoveClass({'elemclass': 'blue'});
				
			});		

			socket.on( 'userOrder', function( data ) {
				
				console.log('========userOrder '+data);

				//console.log('data socket:',data);
				var market_id=data.market_id;

					//Update balance
				if(data.data_price !== undefined){
					//console.log('update user balance');
					//Change buy/sell form balance
					$('#cur_to').text(data.data_price.balance_coinsecond.balance);
					$('#cur_from').text(data.data_price.balance_coinmain.balance);
					
					//Change sidebar balance
					
					$('#sidebar #spanBalance-'+data.data_price.balance_coinmain.wallet_id).text(data.data_price.balance_coinmain.balance);
					//$('#sidebar #spanBalance-'+data.data_price.balance_coinmain.wallet_id).text(balance_coinmain_sidebar);
					$('#sidebar #spanBalance-'+data.data_price.balance_coinsecond.wallet_id).text(data.data_price.balance_coinsecond.balance);
					//$('#sidebar #spanBalance-'+data.data_price.balance_coinsecond.wallet_id).text(balance_coinsecond_sidebar);
					
					
				}
					
				if( data.user_orders !== undefined ){
					$.each(data.user_orders, function(key, value){
						console.log(data);
						

						var order_type_value;
						if(value['order_b']!== undefined){
							order_type_value = 'order_b';
							order_type_string = 'buy';
							order_type_class_new = 'blue';
						}else if(value['order_s']!== undefined){
							order_type_value = 'order_s';
							order_type_string = 'sell';
							order_type_class_new = 'red';
						}
					
						if(value[order_type_value]!== undefined){
							console.log(value[order_type_value]['action']);

							var amount = prettyFloat(value[order_type_value]['amount'], 8);
							//var total = prettyFloat(value[order_type_value]['total'], 8);
							var total = value[order_type_value]['total'];

							
							//var price = prettyFloat(value[order_type_value]['price'], 8);
							var price = value[order_type_value]['price'];
							
							var class_price = price.replace(".","-");
							var class_price = class_price.replace(",","-");
							var order_date_ = value[order_type_value]['created_at']['date'];
							order_date_ = order_date_.substring(0, order_date_.indexOf('.'));	//Remove everything after a certain character

							
							
							switch(value[order_type_value]['action']){
								case "insert":
									console.log('insert private '+order_type_string+' order, market_id:' +market_id+', yourorder: '+ value[order_type_value]['id']);
									//insert your buy order, your current order list
									var your_order='<tr id="yourorder-'+value[order_type_value]['id'] +'" class="order price-'+class_price+'"><td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td><td><span class="date"><small>'+order_date_ +'<small></span></td><td><button type="button" onclick="javascript:cancelOrder(this, '+value[order_type_value]['id'] +');" class="btn btn-danger btn-xs">{{trans('texts.cancel')}}</button></td></tr>';
									//$('#yourorders_'+market_id+' > table tr.header-tb').after(your_order);

									if( $('#yourorders_market_'+market_id+' #yourorders_'+order_type_string+'  table > tbody > tr:first').length )
										$('#yourorders_market_'+market_id+' #yourorders_'+order_type_string+'  table > tbody > tr:first').before(your_order);
									else	
										$('#yourorders_market_'+market_id+' #yourorders_'+order_type_string+' table > tbody').append(your_order);
										
									
									$('#yourorders_market_'+market_id+' #yourorders_'+order_type_string+' table > tbody > tr#yourorder-'+value[order_type_value]['id']).addClassDelayRemoveClass({'elemclass': order_type_class_new+' affected'});

								break;
							}
						}
						
						//if ($element.parent().length) { alert('yes') }

					});
				}
			});
			
			socket.on( 'subscribeMarket', function( data ) {
			
				console.log('========subscribeMarket Socket '+ data);

				
				//update trade history
	               	if(data !== undefined){
						/*
						var history_trade_reversed = data.history_trade;
						history_trade_reversed = history_trade_reversed.reverse();
						
						console.log("message history_trade: "+key + ": " + value);
						
						

homes.sort(function(a,b) { return parseFloat(a.price) - parseFloat(b.price) } );
						*/
						var data_history_trade = $.map(data, function(el) { return el; });
						data_history_trade.sort(function(a, b) {
							// Ascending: first price less than the previous
							if(a.type == 'buy')
								return a.price - b.price;
							else
								return b.price - a.price;
						});

						
						//console.log('subscribeMarket Socket - before  ');
						var total = 0, market_id;
						$.each(data_history_trade, function(key, value){
							
							
							market_id=value['market_id'];
							if (market_id == {{{Session::get('market_id')}}} ){
								console.log('subscribeMarket Socket - market id  '+ market_id);
								//total = (parseFloat(value['price'])*parseFloat(value['amount'])).toFixed(8);
								
								//updateYourOrdersTable(value['type'], value['market_id'], value['id'], total, amount, total, amount);
								updateYourOrdersTable(value['type'], value['market_id'], value['id'], value['amount'], value['price']);
								
								//console.log('history_trade',value);    
								//console.log('history_trade id',value['id']);    
								//console.log('history_trade init');
								var trade_new = '<tr id="trade-'+value['id'] +'" class="order">';
								trade_new += '<td><span>'+value['created_at']+'</span></td>';

								//console.log('history_trade before total: ');
								var total = parseFloat(value['price'])*parseFloat(value['amount']);
								var amount = parseFloat(value['amount']).toFixed(8);
								
								
								//Update total maincoin and amount secondcoin on buy and sell side
								if(value['type'] == 'sell'){
									trade_new += '<td><span style="color:red; text-transform: capitalize;"><strong>'+value['type']+' <i class="icon-arrow-down icon-large" ></i></span></td>';
									
									updateTotalAmountOrders('buy', amount, total, market_id, 'yes');	//update on buy side
								}else{
									trade_new += '<td><span style="color:green; text-transform: capitalize;"><strong>'+value['type']+' <i class="icon-arrow-up icon-large" ></i></span></td>';
									
									updateTotalAmountOrders('sell', amount, total, market_id, 'yes');	//update on sell side
								}
									
									

								//console.log('history_trade total: ',total);
								//console.log('history_trade amount: ',amount);
								trade_new += '<td>'+parseFloat(value['price']).toFixed(8)+'</td>';
								trade_new += '<td>'+amount+'</td>';
								trade_new += '<td>'+total.toFixed(8)+'</td>';
								trade_new+='</tr>'; 
								//console.log('history_trade trade_new: ',trade_new);              		
								if($('#trade_histories_'+market_id+' > table > tbody > tr:first').length)
									$('#trade_histories_'+market_id+' > table > tbody > tr:first').before(trade_new);
								else	
									$('#trade_histories_'+market_id+' > table > tbody').append(trade_new);
									
								$('#trade_histories_'+market_id+' > table > tbody > tr#trade-'+value['id']).addClassDelayRemoveClass({'elemclass': 'new'});
								//$('#trade_histories_'+market_id+' > table tr.header-tb').after(trade_new);
								
							}
						});
						
	               	}
					
			});
			
			socket.on( 'subscribeAllMarkets', function( data ) {
				
				console.log('=========subscribeAllMarkets Socket '+ data);

				var market_id=data.market_id;
				
            	
				$.each(data.message_socket, function(key, value){
				    console.log("message socket data: "+key + ": " + value);
					
					var order_type_value;
					if(value['order_b']!== undefined){
						order_type_value = 'order_b';
						order_type_string = 'buy';
						order_type_class_new = 'green';
						order_type_class_update = 'red';
					}else if(value['order_s']!== undefined){
						order_type_value = 'order_s';
						order_type_string = 'sell';
						order_type_class_new = 'green';
						order_type_class_update = 'blue';
					}
				
				    if(value[order_type_value]!== undefined){
						//console.log(order_type,value[order_type_value]);              		
	               		var amount = parseFloat(value[order_type_value]['amount']).toFixed(8);
						var total = parseFloat(value[order_type_value]['total']).toFixed(8);

	               		var price = parseFloat(value[order_type_value]['price']).toFixed(8);
	               		var class_price = price.replace(".","-");
	            		class_price = class_price.replace(",","-");

						
	            		console.log('class_price',class_price);
	            		console.log('action',value[order_type_value]['action']); 
	               		
						if(value[order_type_value]['action'] == 'insert'){

								console.log('Insert '+order_type_string);	//New buy/sell order
	               				if($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).length){
		               				//console.log('Update '+order_type_string);
		               				var amount_old=parseFloat($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .amount').text());
		               				var total_old=parseFloat($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .total').text());

									$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).show();
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .amount').text((parseFloat(amount_old)+parseFloat(amount)).toFixed(8));
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .total').text((parseFloat(total_old)+parseFloat(total)).toFixed(8));
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_new});
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).attr('onclick','use_price(2,'+price +','+(parseFloat(amount_old)+parseFloat(amount)).toFixed(8)+')');
		               			}else{
		               				
		               				var new_order='<tr id="order-'+value[order_type_value]['id'] +'" class="order price-'+class_price+'" onclick="use_price(2,'+value[order_type_value]['price'] +','+amount+')" data-sort="'+price+'" data-counter=""><td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td></tr>';
		               				if($('#orders_'+order_type_string+'_'+market_id+' > table > tbody tr.order').length){
		               					var i_d=0;
			               				$( '#orders_'+order_type_string+'_'+market_id+' tr.order').each(function( index ) {
								            var value = $(this).val(); 
								            var price_compare = parseFloat($(this).attr('data-sort'));					
								            
												//Place the new order on correct row in table
											if(order_type_value == 'order_b'){
												if(price>price_compare){
													i_d=1;
													$(this).before(new_order);
													return false;
												}
											}else if(order_type_value == 'order_s'){
												if(price<price_compare){
													i_d=1;
													$(this).before(new_order);
													return false;
												}
											}
								        });
								        if(i_d==0){
								        	//console.log( "add to the end");  
								        	$('#orders_'+order_type_string+'_'+market_id+' > table > tbody tr:last-child').after(new_order);
								        }
		               				}else{
	               						$('#orders_'+order_type_string+'_'+market_id+' > table > tbody').html(new_order);		
	               					}
									$('#order-'+value[order_type_value]['id']).addClassDelayRemoveClass({'elemclass': order_type_class_new});	//Add green bg for new order, delay and remove class
								
		               			}
								
								updateTotalAmountOrders(order_type_string, amount, total, market_id, 'no');	//sell side
								
		               			//console.log('insert '+order_type_string+' end'); 
	               		}else if (value[order_type_value]['action'] == 'update' || value[order_type_value]['action'] == 'delete'){

	               				console.log('update '+order_type_string+' init');
								//Update existing order, cancel or delete them
								
	               				var amount_old=parseFloat($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .amount').text());
	               				var total_old=parseFloat($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .total').text());
	               				
	           					var new_amount = (parseFloat(amount_old)-parseFloat(amount)).toFixed(8);
	           					var new_total = (parseFloat(total_old)-parseFloat(total)).toFixed(8);

								
								var cancel = false;
								if(value[order_type_value]['type_sub'] !== undefined){
									if (value[order_type_value]['type_sub'] == 'cancel')
										cancel = true;
								}
									
	           					if(new_amount<='0.00000000' || new_amount<=0.00000000 || new_amount <= 0 || isNaN(new_amount)){
									//$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': 'red', 'delaysec': 1000}).fadeOut();
									
									if(cancel == true)
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).fadeOut().remove();
									else
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_update, 'delaysec': 1000}).fadeOut().remove();


									console.log('icee do'+order_type_string+'opposite: ' + new_amount);
									console.log('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price);
	           					}else{
	           						$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).attr('onclick','use_price(1,'+price +','+new_amount+')');
	           						$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .amount').text(new_amount);
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .total').text(new_total);
									$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_update});
									  
									if(cancel == false)
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_update, 'delaysec': 1000});
									else
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).hide().fadeIn();
	           					}
	               		}
	               	}
	               	
				});              	
	            
				
				
					
					
               	//update % change price
               	//console.log('change_price init: ',data.change_price);
               	if(data.change_price !== undefined){
               		//console.log('change init: ',data.change_price.change);
              		var change=parseFloat(data.change_price.change);
              		//console.log('curr_price: ',parseFloat(data.change_price.curr_price).toFixed(8));
              		$('#spanPrice-'+market_id).text(parseFloat(data.change_price.curr_price).toFixed(8));
              		$('#spanPrice-'+market_id).attr('yesterdayPrice',parseFloat(data.change_price.pre_price).toFixed(8));

					$('#volume-'+market_id).attr('data-original-title', (parseFloat(data.data_price.get_prices.volume).toFixed(8)) );
              		//console.log('change: ',change);
              		//console.log('change 1: ',data.change_price.change);

					/*
					var balance_coinmain_sidebar;
					var balance_coinsecond_sidebar;
					
					if (data.data_price.balance_coinmain.balance == 0)
						balance_coinmain_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinmain.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% <i class="fa fa-arrow-up"></span>';
					else if  (data.data_price.balance_coinmain.balance > 0)
						balance_coinmain_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinmain.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% <i class="fa fa-arrow-down"></span>';
					else
						balance_coinmain_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinmain.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% </span>';

					if (data.data_price.balance_coinsecond.balance == 0)
						balance_coinsecond_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinsecond.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% <i class="fa fa-arrow-up"></span>';
					else if  (data.data_price.balance_coinsecond.balance > 0)
						balance_coinsecond_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinsecond.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% <i class="fa fa-arrow-down"></span>';
					else
						balance_coinsecond_sidebar = '<span class="change" id="spanChange-'+data.data_price.balance_coinsecond.wallet_id+'">'+data.data_price.balance_coinmain.balance+'% </span>';
					*/
					
					if(change==0){  
               			$('#spanChange-'+market_id).removeClass('up down');
               			$('#spanChange-'+market_id).html('+'+data.change_price.change+'%');
					}else if(change>0){  
               			//console.log('Up ');           			
               			$('#spanChange-'+market_id).removeClass('up down').addClass('up');
               			$('#spanChange-'+market_id).html('+'+data.change_price.change+'% <i class="fa fa-arrow-up"></i>');
               			//console.log('Up 1a ');   
               		}else{
               			//console.log('Down ');               			 
               			$('#spanChange-'+market_id).removeClass('up down').addClass('down');
               			$('#spanChange-'+market_id).html(''+data.change_price.change+'% <i class="fa fa-arrow-down"></i>');
               			//console.log('Down a');
               		}               		
               	}
               	//update block price
               	if(data.data_price !== undefined){
               		//console.log('data_price',data.data_price);
               		if(data.data_price.latest_price!==undefined){
						//Set High,Low and Volume for viewed MarketID coin
						
               			var old_lastprice = parseFloat( $('#spanLastPrice-'+market_id).text() ).toFixed(8);
               			//var old_lastprice = $('#spanLastPrice-'+market_id).text();
	               		var new_lastprice = parseFloat(data.data_price.latest_price).toFixed(8);
						
	               		console.log("if(new_lastprice<old_lastprice) "+ new_lastprice+'<'+old_lastprice );
						if(new_lastprice<old_lastprice){
							$('#lastprice-'+market_id).addClass('red');
							
								//Set High,Low and Volume for index MarketID coin	
							if( $('#mainLastPrice-'+market_id).length )
								$('#mainCoin-'+market_id).addClassDelayRemoveClass({'elemclass': 'red'});
	               		}else{ 
							$('#lastprice-'+market_id).addClass('blue');
							
								//Set High,Low and Volume for index MarketID coin	
							if( $('#mainLastPrice-'+market_id).length )
								$('#mainCoin-'+market_id).addClassDelayRemoveClass({'elemclass': 'blue'});
						}
	               		$('#spanLastPrice-'+market_id).text(new_lastprice);
						
						if( $('#mainLastPrice-'+market_id).length )
							$('#mainLastPrice-'+market_id).text(new_lastprice);
						
						//Set High,Low and Volume for index MarketID coin
               		}               		
               		if(data.data_price.get_prices!==undefined){
							//Set High,Low and Volume for viewed MarketID coin
               			$('#spanHighPrice-'+market_id).text(parseFloat(data.data_price.get_prices.max).toFixed(8));
	               		$('#spanLowPrice-'+market_id).text(parseFloat(data.data_price.get_prices.min).toFixed(8));
	               		$('#spanVolume-'+market_id).text(parseFloat(data.data_price.get_prices.volume).toFixed(8));

							//Set High,Low and Volume for index MarketID coin
						if( $('#mainHighPrice-'+market_id).length )
							$('#mainHighPrice-'+market_id).text(parseFloat(data.data_price.get_prices.max).toFixed(8));
						if( $('#mainHighPrice-'+market_id).length )
							$('#mainLowPrice-'+market_id).text(parseFloat(data.data_price.get_prices.min).toFixed(8));
						if( $('#mainHighPrice-'+market_id).length )
							$('#mainVolume-'+market_id).text(parseFloat(data.data_price.get_prices.volume).toFixed(8));
						

               		}
               	}

				/*
               	setTimeout(function(){
               		$('table > tr').removeClass("new");
               		//$('table tr,li, div.box').removeClass("blue red green");               		
               		$('#s_message, #b_message').text('');
               	},10000);
				*/
				
				//Set new data-counter attributes (for real time trading updates)
				var i = 1;
				$('table.sellorders > tbody > tr').each(function() {
					$(this).attr('data-counter', i);
					i++;
				});
				var i = 1;
				$('table.buyorders > tbody > tr').each(function() {
					$(this).attr('data-counter', i);
					i++;
				});
				
			});
			

		});







//$('li.volume').tooltip('show');

$(function () { 
	$("[data-toggle='tooltip']").tooltip( { 'delay': { show: 100, hide: 100 } } ); 
});
</script>

@stop
