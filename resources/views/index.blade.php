@extends('layouts.default')
<?php
	// Set individual Market title
	if ($market_predefined) :?>
@section('title')
<?php echo Config::get('config_custom.company_name_domain') . ' - ' . $market_from . ' / ' . $market_to . ' ' . trans('texts.market') ?>
@stop
@section('description')
<?php echo Config::get('config_custom.company_name_domain') . ' - '. Config::get('config_custom.company_slogan') ?>
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
		<div>
			
			<div class="col-12-xs col-sm-12 col-lg-12 news-panel">
				<div >
					<div class="bs-component">
						<div class="alert alert-dismissible alert-info">
							<button data-dismiss="alert" class="close" type="button">×</button>
							<a href="#">
								Testing
							</a>
						</div>
					</div>
				</div>
			</div>
			
			
			<!-- Startpage Markets -->
			@if(isset($show_all_markets) && $show_all_markets === true)
				@include('blocks.startmarkets')
			@endif
			<!-- Predefined Markets -->
			@if($market_predefined)
				@include('blocks.predefinedmarket')
			@endif
		</div>
		
		
	</div>
</div>
{{ HTML::script('assets/js/jquery.tablesorter.js') }}
{{ HTML::script('assets/js/jquery.tablesorter.widgets.js') }}
{{ HTML::script('assets/js/jquery.tablesorter.widgets.columnSelector.js') }}
<script type="text/javascript"></script>
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

@stop

