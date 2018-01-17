

<div class="row">
	<div class="market_info">
		<div class="row">
			<div class="row">
				<div class="col-12-xs col-sm-12 col-lg-12">
					<h2>{{$market_from}} ({{{ $coinmain }}})</h2>
				</div>
				<?php
					/*
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
					*/
					?>
			</div>
		</div>
		<div class="row">
			<div class="bs-component">
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div id="lastprice-{{{Session::get('market_id')}}}"><span aria-hidden="true" class="glyphicon glyphicon-chevron-right" style="color: #2a9fd6;"></span> Last Price:<br><strong><span id="spanLastPrice-{{{Session::get('market_id')}}}">@if(empty($latest_price)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$latest_price)}} @endif</span> {{{ $coinsecond }}}</strong></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div ><span aria-hidden="true" class="glyphicon glyphicon-export" style="color: #6bbf46;"></span> 24H High:<br><strong><span id="spanHighPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->max)}} @endif</span>{{{ $coinsecond }}}</strong></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div ><span aria-hidden="true" class="glyphicon glyphicon-import" style="color: #cc0000;"></span> 24H Low:<br><strong><span id="spanLowPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->min)}} @endif</span>{{{ $coinsecond }}}</strong></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div ><span aria-hidden="true" class="glyphicon glyphicon-stats"></span> 24H Volume:<br><strong><span id="spanVolume-{{{Session::get('market_id')}}}">@if(empty($get_prices->volume)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->volume)}} @endif</span> {{{ $coinsecond }}}</strong></div>
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
		<div class="row">
			<!-- Charts Graph  -->
			<!--  <div class="col-12-xs col-sm-12 col-lg-12"> -->
			<!--  <div class="col-xs-12 col-sm-6 col-md-8"> -->
			<h2 ><img width="32" border=0 height="32" src="{{asset('')}}/{{$coinmain_logo}}" /> {{$market_from}}/{{$market_to}} </h2>
		</div>
	</div>
	@if ( $url != '' )
		<span class="blockviewer"><a href="{{$url}}" target="_blank">Pool URL</a></span>
	@endif
	@if ( $blockviewer != '' )
		<span class="blockviewer"><a href="{{$blockviewer}}" target="_blank">Block Viewer</a></span>
	@endif
	@if ( $forum != '' )
		<span class="blockviewer"><a href="{{$forum}}" target="_blank">Forum</a></span>
	@endif
	
	<?php
		/*
		@if ( $market_from == 'UFO' )
			  <div class="alert alert-danger">UFO Market is closing, please withdraw your coins ASAP!</div>
		@endif
		*/
		?>
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
	<!--
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
		-->
	<!-- Charts and Info !-->
	<div class="row">
		<!-- Charts Graph  -->
		<!--  <div class="col-xs-12 col-sm-6 col-md-8"> -->
		<!--  <div class="col-12-xs col-sm-12 col-lg-12"> -->
		<div class="col-12-xs col-sm-12 col-lg-12">
			<ul class="nav nav-tabs" id="chart_marketdepth_tab" role="tablist" >
				<li><a href="#orderdepth" role="tab" data-toggle="tab" data="order-chart" onclick="javascript: drawOrderDepthChart();">Order Depth</a></li>
				<li class="right active"><a href="#chartdiv" role="tab" data-toggle="tab" data="price-volume-chart">Price / Volume</a></li>
			</ul>
			<div class="tab-content chart_marketdepth">
				<div class="tab-pane active" id="chartdiv" style="width:100%; height:400px;">
					<div id="chartLoadingBox">Loading...</div>
				</div>
				<div class="tab-pane" id="orderdepth" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<?php
			/*
			
			<!-- Market Daily Info  -->
			<div class="col-xs-6 col-md-4">
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
			
			*/
			?>
	</div>
	<hr />
	<div class="row">
		<div class="col-12-xs col-sm-12 col-lg-12">
			<ul class="nav nav-tabs">
				<li class="active"><a aria-expanded="true" href="#coin_info" data-toggle="tab">{{$market_from}} ({{{ $coinmain }}}) - Information</a></li>
				<li class=""><a aria-expanded="false" href="#coin_upcoming" data-toggle="tab">Profile</a></li>
				<li class="disabled"><a>Disabled</a></li>
				<li class="dropdown">
					<a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#">
					Links <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#dropdown1" data-toggle="tab">Link 1</a></li>
						<li class="divider"></li>
						<li><a href="#dropdown2" data-toggle="tab">Link 2</a></li>
					</ul>
				</li>
			</ul>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade active in" id="coin_info">
					<p>Information about the Coin</p>
				</div>
				<div class="tab-pane fade" id="coin_upcoming">
					<p>Upcoming features.</p>
				</div>
				<div class="tab-pane fade" id="dropdown1">
					<p>Link 1 Content.</p>
				</div>
				<div class="tab-pane fade" id="dropdown2">
					<p>Link 2 Content.</p>
				</div>
			</div>
		</div>
	</div>
	<hr />
	<!-- Sell/Buy -->
	<?php
		/*
		@if ( Auth::guest() )
		@else
		*/
		?>
	<div class="row">
		<div class="wrapper-trading buysellform">
			<div class="col-xs-12 col-sm-6">
				<!-- <div class="inblock-left"> </div>-->
					@include('blocks.buyform')
			</div>
			<div class="col-xs-12 col-sm-6">
				<!-- <div class="inblock-right"> </div>-->
					@include('blocks.sellform')	
			</div>
		</div>
	</div>
	<?php
		//@endif
		?>
	<div class="row">
		<div class="wrapper-trading buysellorders">
			<div class="col-12-xs col-sm-12 col-lg-12">
				<h3>{{{ trans('texts.order_book')}}}</h3>
			</div>
	
			<div class="col-xs-12 col-sm-6">
				@include('blocks.buyorders')
			</div>
			<div class="col-xs-12 col-sm-6">
				@include('blocks.sellorders')
			</div>
		</div>
	</div>
	<!-- Active Orders  -->
	@if ( Auth::guest() )
	@else
	<div class="row">
		
			@include('blocks.yourorders')
		
	</div>
	@endif
	<!-- Trade History -->
	<div class="row">
		<div class="col-12-xs col-sm-12 col-lg-12">
			@include('blocks.tradehistory')
		</div>
	</div>
	<div class="row">
		<div class="col-12-xs col-sm-12 col-lg-12">
			&nbsp;
		</div>
	</div>
</div>
	<div class="clear"></div>
	<!-- Assets for Charts -->
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


