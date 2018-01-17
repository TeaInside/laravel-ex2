@extends('layouts.default')
<?php
// Set individual Market title
if ($market_predefined) :?>
	@section('title')
		<?php echo Config::get('config_custom.company_name_domain') . ' - ' . $market_from . ' / ' . $market_to . ' ' . trans('texts.market') ?>
	@stop
	@section('description')
		This is a description
	@stop
	
<?php
/*
	//@section('title', 'This is an individual page title')
	//@section('description', 'This is a description')
	*/
endif;
?>

@section('content')




<h2 style="color: red;" >
	<a href="https://sweedx.com/post/beta-testing-of-realtime-trading-system-2">
		Beta testing of realtime trading system. <br />
		Join us at freenode #sweedx
	</a>
</h2>
Users: <p id="client_count"></p>

@if(isset($show_all_markets) && $show_all_markets === true)
	<h2>BTC 24 Hour trade statistics</h2>
	<div class="market_search_box" >
		<input id="btc_market_search" type="search" data-column="1" placeholder="Search" class="form-control" />
	</div>
	<table class="table table-striped table-hover" id="btc_market_table">
		<thead>
			<tr class="header-tb">
			<th colspan=2>Currency</th>
				<th>Market</th>
				<th>Last Price</th>
				<th>24 Hour High</th>
				<th>24 Hour Low</th>
				<th>24 Hour Volume</th>
			</tr> 
		</thead>
		<tbody>
		@foreach($all_markets as $am)
			@if ($am['to'] == 'BTC')
			<tr id="mainCoin-{{$am['market']->id}}">
				<td >
					@if(!empty($am['logo']))                        
						<a href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}"><img src="{{asset('')}}/{{$am['logo']}}" class="coin_icon_small" /></a>
					@else
					&nbsp;
					@endif
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}</a>
				</td>
				<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}/{{$am['to']}}</a></td>
				<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}">{{$am['latest_price']}}</a></td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}">@if(empty($am['prices']->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->max)}} @endif</a>
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}">@if(empty($am['prices']->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->min)}} @endif</a>
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainVolume-{{$am['market']->id}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
				</td>
			</tr>
			@endif
		@endforeach
		</tbody>
	</table>

	<h2 style="margin-top:0px;">LTC 24 Hour trade statistics</h2>
	<div class="market_search_box" >
		<input id="ltc_market_search" type="search" data-column="1" placeholder="Search" class="form-control" />
	</div>
	<table class="table table-striped table-hover" id="ltc_market_table">
		<thead>
			<tr class="header-tb">
			<th colspan=2>Currency</th>
				<th>Market</th>
				<th>Last Price</th>
				<th>24 Hour High</th>
				<th>24 Hour Low</th>
				<th>24 Hour Volume</th>
			</tr> 
		</thead>
		<tbody>
		@foreach($all_markets as $am)
			@if ($am['to'] != 'BTC')
			<tr id="mainCoin-{{$am['market']->id}}">
				<td >
					@if(!empty($am['logo']))                        
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}"><img class="coin_icon_small" src="{{asset('')}}/{{$am['logo']}}" /></a>
					@else
					&nbsp;
					@endif
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}</a>
				</td>
				<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}/{{$am['to']}}</a></td>
				<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}"> {{$am['latest_price']}}</a></td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}"> @if(empty($am['prices']->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->max)}} @endif</a>
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}"> @if(empty($am['prices']->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->min)}} @endif</a>
				</td>
				<td>
					<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainVolume-{{$am['market']->id}}"> @if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
				</td>
			</tr>
			@endif
		@endforeach
		</tbody>
	</table>

@endif

		@if($market_predefined)

			@if($enable_trading == 0)
				<div class="alert alert-danger">
					<strong>{{{ trans('texts.market_disabled')}}}</strong>
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
					<div class="item25">
						<div class="success box" id="lastprice-{{{Session::get('market_id')}}}">Last Price:<br><strong><span id="spanLastPrice-{{{Session::get('market_id')}}}">@if(empty($latest_price)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$latest_price)}} @endif</span></strong></div>
					</div>
					<div class="item25">
						<div class="success box">24 h High:<br><strong><span id="spanHighPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->max)}} @endif</span></strong></div>
					</div>
					<div class="item25">
						<div class="success box">24 h Low:<br><strong><span id="spanLowPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->min)}} @endif</span></strong></div>
					</div>
					<div class="item25">
						<div class="success box">24 h Vol:<br><strong><span id="spanVolume-{{{Session::get('market_id')}}}">@if(empty($get_prices->volume)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->volume)}} @endif</span> {{{ $coinsecond }}}</strong></div>
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
				@if ( Auth::guest() )
				@else
					<div class="wrapper-trading buysellform">
						<div class="inblock-left">
							@include('blocks.buyform')
						</div>	
						<div class="inblock-right">		
							@include('blocks.sellform')	
						</div>
					</div>
				@endif

				<div class="wrapper-trading buysellorders">
					<div class="inblock-left">
						@include('blocks.sellorders')
					</div>	
					<div class="inblock-right">		
						@include('blocks.buyorders')
					</div>
				</div>
				<!-- Trade history -->
				@include('blocks.tradehistory')				
				
					<!-- Your Active Order  -->
				@if ( Auth::guest() )
				@else
					@include('blocks.yourorders')
				@endif
			<div id="messageModal" class="modal">
			  <div class="modal-dialog">
				<div class="modal-content">      
				  <div class="modal-body">        
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">{{{ trans('texts.close')}}}</button>       
				  </div>
				</div>
			  </div>
			</div>

		

		{{ HTML::style('assets/amcharts/style.css') }}
		{{ HTML::script('assets/amcharts/amcharts.js') }}
		{{ HTML::script('assets/amcharts/serial.js') }}
		{{ HTML::script('assets/amcharts/amstock.js') }}
	
		<script type="text/javascript">
var chart;
var defaultLoad=false;
var chartData=[];
AmCharts.loadJSON=function(timeSpan,buttonClick){
	chartData=[];
	createStockChart();
	var timeSpan_ = '6 hour';

	//console.log('timeSpan:',timeSpan);
	switch(timeSpan){
		case "1DD":
			timeSpan_ = '1 day';
			break;
		case "3DD":
			timeSpan_ = '3 day';
			break;
		case "7DD":
			timeSpan_ = '7 day';
			break;
		case "MAX":
			timeSpan_ = 'MAX';
			break;
		default:
			timeSpan_ = '6 hour';
	}
	console.log('timeSpan_:',timeSpan_);
	$('.loading').show();
	$.ajax({
		url:"<?php echo action('HomeController@getChart')?>",
		type:'post',
		dataType:'json',
		data: {Ajax:1,timeSpan:timeSpan_,market_id:<?php echo $market_id ?>},
		cache:false,
		async:true,
		success:function(rows){	
			//console.log('rows: ',rows);		
			$('.loading').hide();
			for(var i=0; i<rows.length; i++){
				//console.log('chartData '+i+': ',rows[i]);
				var open=parseFloat(rows[i]['open']).toFixed(8);
				var close=parseFloat(rows[i]['close']).toFixed(8);
				var high=parseFloat(rows[i]['high']).toFixed(8);
				var low=parseFloat(rows[i]['low']).toFixed(8);				
				//console.log('rows '+i+' date: '+rows[i]['date']+' open: '+open+' close: '+close+' high: '+high+' low: '+low);
				chartData.push({date:rows[i]['date'],open:open,close:close,high:high,low:low,exchange_volume:rows[i]['exchange_volume']});
			}
			//console.log('chartData: ',chartData);
			//date=rows[rows.length-1]['date'];
			//date=new Date(date.replace(" ","T")+'Z');
			//var localOffset=date.getTimezoneOffset()*60000;
			//date.setTime(date.getTime()+ 600000+ localOffset);
			//chartData.push({date:date,open:rows[rows.length-1]['close'],close:rows[rows.length-1]['close'],high:rows[rows.length-1]['close'],low:rows[rows.length-1]['close'],exchange_volume:0});
			chart.dataProvider=chartData;
			chart.validateNow();
			if(buttonClick===false){
				//$('input[value="6 hours"]').click();
				$('input[value="1 week"]').click();
			}else{
				//$('input[value="MAX"]').removeClass('amChartsButtonSelected').addClass('amChartsButton');
				$('.amChartsPeriodSelector input[type=button]').removeClass('amChartsButtonSelected').addClass('amChartsButton');
				if(timeSpan=='6hh'){
					$('input[value="6 hours"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
				}else if(timeSpan=='1DD'){
					$('input[value="24 hours"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
				}else if(timeSpan=='3DD'){
					$('input[value="3 days"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
				}else if(timeSpan=='7DD'){
					$('input[value="1 week"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
				}else{
					$('input[value="MAX"]').removeClass('amChartsButton').addClass('amChartsButtonSelected');
				}
			}

		}
	});
};
function buttonClickHandler(data){
	console.log('buttonClickHandler:',data);
	if(defaultLoad===true){
		if(typeof data.count!=='undefined'){AmCharts.loadJSON(data.count+ data.predefinedPeriod,true);
		}else{
			AmCharts.loadJSON(data.predefinedPeriod,true);
		}
	}else{
		defaultLoad=true;
	}
}
AmCharts.ready(function(){AmCharts.loadJSON('7DD',false);
	createStockChart();
});
function createStockChart(){
	chart=new AmCharts.AmStockChart();
	chart.pathToImages="/assets/js/amcharts/images/";
	var categoryAxesSettings=new AmCharts.CategoryAxesSettings();
	categoryAxesSettings.minPeriod="10mm";
	categoryAxesSettings.groupToPeriods=["10mm","30mm","hh","3hh","6hh","12hh","DD"];
	chart.categoryAxesSettings=categoryAxesSettings;
	chart.dataDateFormat="YYYY-MM-DD JJ:NN";
	var dataSet=new AmCharts.DataSet();
	dataSet.color="#7f8da9";
	dataSet.fieldMappings=[
		{fromField:"open",toField:"open"},
		{fromField:"close",toField:"close"},
		{fromField:"high",toField:"high"},
		{fromField:"low",toField:"low"},
		{fromField:"exchange_volume",toField:"exchange_volume"}
	];
	dataSet.dataProvider=chartData;
	dataSet.categoryField="date";
	chart.dataSets=[dataSet];
	var stockPanel1=new AmCharts.StockPanel();
	stockPanel1.showCategoryAxis=false;
	stockPanel1.title="Price";
	stockPanel1.percentHeight=70;
	stockPanel1.numberFormatter={precision:8,decimalSeparator:'.',thousandsSeparator:','};
	var graph1=new AmCharts.StockGraph();
	graph1.valueField="value";
	graph1.type="candlestick";
	graph1.openField="open";
	graph1.closeField="close";
	graph1.highField="high";
	graph1.lowField="low";
	graph1.valueField="close";
	graph1.lineColor="#6bbf46";
	graph1.fillColors="#6bbf46";
	graph1.negativeLineColor="#db4c3c";//"#db4c3c";
	graph1.negativeFillColors="#db4c3c";//"#db4c3c";
	graph1.fillAlphas=1;
	graph1.balloonText="open:<b>[[open]]</b><br>close:<b>[[close]]</b><br>low:<b>[[low]]</b><br>high:<b>[[high]]</b>";
	graph1.useDataSetColors=false;
	stockPanel1.addStockGraph(graph1);
	var stockLegend1=new AmCharts.StockLegend();
	stockLegend1.valueTextRegular=" ";
	stockLegend1.markerType="none";
	stockPanel1.stockLegend=stockLegend1;
	var stockPanel2=new AmCharts.StockPanel();
	stockPanel2.title="Volume";
	stockPanel2.percentHeight=30;
	stockPanel2.numberFormatter={precision:3,decimalSeparator:'.',thousandsSeparator:','};
	var graph2=new AmCharts.StockGraph();
	graph2.valueField="exchange_volume";
	graph2.type="column";
	graph2.cornerRadiusTop=2;
	graph2.fillAlphas=1;
	graph2.periodValue="Sum";
	stockPanel2.addStockGraph(graph2);
	var stockLegend2=new AmCharts.StockLegend();
	stockLegend2.valueTextRegular=" ";
	stockLegend2.markerType="none";
	stockPanel2.stockLegend=stockLegend2;
	chart.panels=[stockPanel1,stockPanel2];
	var cursorSettings=new AmCharts.ChartCursorSettings();
	cursorSettings.valueBalloonsEnabled=true;
	cursorSettings.fullWidth=true;
	cursorSettings.cursorAlpha=0.1;
	chart.chartCursorSettings=cursorSettings;
	var periodSelector=new AmCharts.PeriodSelector();
	periodSelector.position="top";
	periodSelector.dateFormat="YYYY-MM-DD JJ:NN";
	periodSelector.inputFieldWidth=150;
	periodSelector.inputFieldsEnabled=false;
	periodSelector.hideOutOfScopePeriods=false;
	periodSelector.periods=[
		{period:"hh",count:6,label:"6 hours",selected:true},
		{period:"DD",count:1,label:"24 hours"},
		{period:"DD",count:3,label:"3 days"},
		{period:"DD",count:7,label:"1 week"},
		{period:"MAX",label:"MAX"}
	];
	
	// let's add a listener to remove the loading indicator when the chart is
    // done loading
    chart.addListener("rendered", function (event) {
		$("#chartLoadingBox").text('');
    });
	
	periodSelector.addListener('changed',function(period){buttonClickHandler(period);});
	chart.periodSelector=periodSelector;
	var panelsSettings=new AmCharts.PanelsSettings();
	panelsSettings.usePrefixes=false;
	chart.panelsSettings=panelsSettings;
	var valueAxis=new AmCharts.ValueAxis();
	valueAxis.precision=8;
	chart.valueAxis=valueAxis;
	chart.chartScrollbarSettings.enabled=false;
	chart.write('chartdiv');
}


		function drawOrderDepthChart(){
      		  $('.loading').show();
       		 $.ajax({
       		     url:"<?php echo action('OrderController@getOrderDepthChart')?>",
        	     type:'post',
        	     dataType:'json',
                     data: {Ajax:1,market_id:<?php echo $market_id ?>},
                     cache:false,
                     async:true,
                     success:function(rows){
                     //console.log('response: ',response);
                    //var rows = $.parseJSON(response); 
                     console.log('Row: ',rows);
                    $('.loading').hide();
                     var chartData = [];               
               
                    for (var j = rows['buy'].length - 1; j >= 0; j--) {
                    chartData.push({
                        price: parseFloat(parseFloat(rows['buy'][j]['price']).toFixed(8)),
                        bid_total: parseFloat(rows['buy'][j]['total'])
                    });
                   }  

                   for (var i = 0; i < rows['sell'].length; i++) {
                    chartData.push({
                        price: parseFloat(parseFloat(rows['sell'][i]['price']).toFixed(8)),
                        ask_total: parseFloat(rows['sell'][i]['total'])
                    });
                   }
                   //console.log('chartData: ',chartData);
                   var chart = AmCharts.makeChart("orderdepth", {
                     "type": "serial",
                     "theme": "light",
                     "usePrefixes": true,
                     /*"pathToImages": "amcharts/images/",*/
                     "dataProvider": chartData,
                     "valueAxes": [{
                         "id": "v1",
                         "axisColor": "#EEE",
                         "axisThickness": 1,
                         "gridAlpha": 0,
                         "axisAlpha": 1,
                         "position": "left",
                         "visible": true,
                         "unit": " {{{$coinsecond}}}",
                         "titleBold": false
                     }],
                     "graphs": [{
                         "id": "g1",
                         "valueAxis": "v1",
                         "lineColor": "#00ff00",
                         "lineThickness": 2,
                         "hideBulletsCount": 30,
                         "valueField": "bid_total",
                         "balloonText": "<b>[[value]]</b> {{{$coinsecond}}} to get to [[price]]",
                         "fillAlphas": 0.4
                     }, {
                         "id": "g2",
                         "valueAxis": "v1",
                         "lineColor": "#ff0000",
                         "lineThickness": 2,
                         "hideBulletsCount": 30,
                         "valueField": "ask_total",
                         "balloonText": "<b>[[value]]</b> {{{$coinsecond}}} to get to [[price]]",
                         "fillAlphas": 0.4
                     }],
                     "chartCursor": {
                         "cursorPosition": "mouse"
                     },
                     "categoryField": "price",
                     "categoryAxis": {
                         "axisColor": "#BBB",
                         "minorGridEnabled": true,
                         "position": "bottom",
                         "labelRotation": 45
                     }
                 });

                setTimeout(function() {
                    drawOrderDepthChart();
                }, 60000);
            }
        });
    }

	
		</script>
	@endif	
	
{{ HTML::script('assets/js/jquery.tablesorter.js') }}
{{ HTML::script('assets/js/jquery.tablesorter.widgets.js') }}
<script type="text/javascript">

//START - Sidebar
//Search sidebar
$('#search_market').keyup(function(){
	
   var valThis = $(this).val().toLowerCase();
    $('ul.market>li').each(function(){
     var text = $(this).text().toLowerCase();
        (text.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();            
   });
   
});
	
//STOP - Sidebar

//Table sorting
$(function() {

	var $btc_table = $('table#btc_market_table').tablesorter({
		//theme: 'blue',
		//widgets: ["zebra", "filter"],
		widgets: ["filter"],
		widgetOptions : {
			// filter_anyMatch replaced! Instead use the filter_external option
			// Set to use a jQuery selector (or jQuery object) pointing to the
			// external filter (column specific or any match)
			filter_external : '#btc_market_search',
			// add a default type search to the first name column
			filter_defaultFilter: { 1 : '~{query}' },
			// Use the $.tablesorter.storage utility to save the most recent filters
			filter_saveFilters : true,
			// Delay in milliseconds before the filter widget starts searching; 
			filter_searchDelay : 300,
			// include column filters
			filter_columnFilters: false/*,
			filter_placeholder: { search : 'Search...' },
			filter_saveFilters : true,
			filter_reset: '.reset'*/
		}
	});
	var $ltc_table = $('table#ltc_market_table').tablesorter({
		//theme: 'blue',
		//widgets: ["zebra", "filter"],
		widgets: ["filter"],
		widgetOptions : {
			// filter_anyMatch replaced! Instead use the filter_external option
			// Set to use a jQuery selector (or jQuery object) pointing to the
			// external filter (column specific or any match)
			filter_external : '#ltc_market_search',
			// add a default type search to the first name column
			filter_defaultFilter: { 1 : '~{query}' },
			// Use the $.tablesorter.storage utility to save the most recent filters
			filter_saveFilters : true,
			// Delay in milliseconds before the filter widget starts searching; 
			filter_searchDelay : 300,
			// include column filters
			filter_columnFilters: false/*,
			filter_placeholder: { search : 'Search...' },
			filter_saveFilters : true,
			filter_reset: '.reset'*/
		}
	});

});

$(function() {
	$("#sellorders > tbody ").on('click', 'tr', function() {

			var tr_id = parseInt( $(this).attr('data-counter') );
			var tr_id_i = 0;
			var count = 0;
			var td_total = 0;
			var tr_string = '';
			
			//alert( $(this).find("td[class='amount']").text() );
			
			$("#sellorders > tbody > tr").each(function() {
				tr_id_i = parseInt( $(this).attr('data-counter') );
				
				if ( tr_id >= tr_id_i ) {
					//if ( $(this).find("td").hasClass('amount') ){
						//td_total =$('[data-counter='+tr_id_i+'] td.amount').text();
						td_total = $(this).find("td[class='amount']").text();
						count += parseFloat(td_total);
						$(this).addClass('sellorders_marked');
						//tr_string += ' '+tr_id_i;
					//}
				}else{
					$(this).removeClass('sellorders_marked');
				}
			});
			
			count = prettyFloat(count, 8);
			//var price = parseFloat( $(this).find("td[class='price']").text() );
			var price = $(this).find("td[class='price']").text();
			//price = prettyFloat(price, 8);
			$('#b_amount').val(count);
			$('#b_price').val( price);
			$('#s_price').val(price);
			
		updateDataSell();
		updateDataBuy();
	});
	$("#buyorders > tbody ").on('click', 'tr', function() {

			var tr_id = parseInt( $(this).attr('data-counter') );
			var tr_id_i = 0;
			var count = 0;
			var td_total = 0;
			var tr_string = '';
			
			//alert( $(this).find("td[class='amount']").text() );
			
			$("#buyorders > tbody > tr").each(function() {
				tr_id_i = parseInt( $(this).attr('data-counter') );
				
				if ( tr_id >= tr_id_i ) {
					//if ( $(this).find("td").hasClass('amount') ){
						//td_total =$('[data-counter='+tr_id_i+'] td.amount').text();
						td_total = $(this).find("td[class='amount']").text();
						count += parseFloat(td_total);
						$(this).addClass('buyorders_marked');
						//tr_string += ' '+tr_id_i;
					//}
				}else{
					$(this).removeClass('buyorders_marked');
				}
			});
			
			count = prettyFloat(count, 8);
			
			//var price = parseFloat( $(this).find("td[class='price']").text() );
			var price = $(this).find("td[class='price']").text();
			//price = prettyFloat(price, 8);

			$('#s_amount').val(count);
			$('#s_price').val(price);
			$('#b_price').val(price);
			
		updateDataSell();
		updateDataBuy();
	});
	
});


function use_price(type, price, total_amount, el){
	return;
<?php
/*
	@if ( Auth::guest() )
	@else
		// var pre = 'b_';
		// if(type==2) pre = 's_';
		// $('#'+pre+'price').val(price.toFixed(8));
		// $('#'+pre+'amount').val(total_amount.toFixed(8));
			var tr_id = parseInt( $(el).attr('data-counter') );
			var tr_id_i = 0;
			var count = 0;
			var td_total = 0;
			var tr_string = '';

			
		if(type==1){	//buy

			$("#sellorders > tbody > tr").each(function() {
				tr_id_i = parseInt( $(this).attr('data-counter') );
				
				if ( tr_id >= tr_id_i ) {
					//if ( $(this).find("td").hasClass('amount') ){
						//td_total =$('[data-counter='+tr_id_i+'] td.amount').text();
						td_total = $(this).find("td[class='amount']").text();
						count += parseFloat(td_total);
						$(this).addClass('sellorders_marked');
						//tr_string += ' '+tr_id_i;
					//}
				}else{
					$(this).removeClass('sellorders_marked');
				}
			});
			//alert(tr_string + ' || tr_id: ' +tr_id);
			//$('#b_amount').val(total_amount.toFixed(8));
			$('#b_amount').val(count.toFixed(8));
			$('#b_price').val(price.toFixed(8));
			$('#s_price').val(price.toFixed(8));
			
		}else if(type==2){		//sell

			$("#buyorders > tbody > tr").each(function() {
				tr_id_i = parseInt( $(this).attr('data-counter') );
				
				if ( tr_id >= tr_id_i ) {
					//if ( $(this).find("td").hasClass('amount') ){
						//td_total =$('[data-counter='+tr_id_i+'] td.amount').text();
						td_total = $(this).find("td[class='amount']").text();
						count += parseFloat(td_total);
						$(this).addClass('buyorders_marked');
						//tr_string += ' '+tr_id_i;
					//}
				}else{
					$(this).removeClass('buyorders_marked');
				}
			});
			
			//alert ( $("table.buyorders").text() );
			//alert(tr_string + ' || tr_id: ' +tr_id);
			
			$('#s_amount').val(count.toFixed(8));
			$('#s_price').val(price.toFixed(8));
			$('#b_price').val(price.toFixed(8));
		}else{}
		updateDataSell();
		updateDataBuy();
		
		//alert( $(el).attr('data-counter') + ' id->'+$(el).attr('id') + ' type->' +type);

		
	@endif
*/
?>
}

var stack_bottomleft = {"dir1": "up", "dir2": "right", "push": "top", "spacing1": 10, "spacing2": 10};	
// type: "notice" - Type of the notice. "notice", "info", "success", or "error".
function showMessageSingle(message,type){
	 var opts = {
		title: type,
		text: message,
		addclass: "stack-bottomleft",
		buttons: {
			closer_hover: false
		},
		stack: stack_bottomleft,
		animate_speed: 'fast'
	};
	switch (type) {
	case 'error':
		opts.type = "error";
		break;
	case 'info':
		opts.type = "info";
		break;
	case 'success':
		opts.type = "success";
		break;
	}
	new PNotify(opts);


}

function showMessage(messages,type){
	//var html;
	var message = '';
	for (i = 0; i < messages.length; i++) { 
		message = messages[i];
		
        //html='<div id="notifyjs-'+i+'" class="notifyjs-wrapper notifyjs-hidable '+type+'"><div class="notifyjs-container"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-success"><span data-notify-text="">'+message+'</span></div></div></div>';
		//$('.notifyjs-corner').append(html);
	
		showMessageSingle(message, type);
	}
	
}




</script>
<!-- <div class="container-fluid">
		<button onclick="testCal()">Test</button>
	</div>  -->
{{ HTML::script('https://cdn.socket.io/socket.io-1.2.0.js') }} 
<script type="text/javascript" charset="utf-8">	

(function ( $ ) {
$.fn.addClassDelayRemoveClass = function( options ) {
	// This is the easiest way to have default options.
	var settings = $.extend({
		// These are the defaults.
		elemclass: "",
		delaysec: 1000
	}, options );
	// Greenify the collection based on the settings variable.
	/*return this.css({
		color: settings.color,
		backgroundColor: settings.backgroundColor
	});
	*/	 
	 return $(this).addClass(settings.elemclass)
                       .delay(settings.delaysec)
                       .queue(function() {
                           $(this).removeClass(settings.elemclass);
                           $(this).dequeue();
                       });
	
	//$(this).addClass(settings.elemclass).delay(settings.delaysec).queue(function() {$(this).removeClass(settings.elemclass);$(this).dequeue();})
	
	};
}( jQuery ));


/*

 @ param traded = add or substract from buy/sell amount/total
*/
function updateTotalAmountOrders(type, amount, total, market_id, traded){


	if(type == 'buy'){	//sell side
		console.log('update total amount orders - sell');
		var b_amount_all = $('#buyorders_amount_all_'+market_id).text();
		
		
		var total_amount_new = 0;
		if (traded == 'yes')	//substract
			total_amount_new = prettyFloat( (parseFloat(b_amount_all) - parseFloat(amount)), 8 );
		else					//addition
			total_amount_new = prettyFloat( (parseFloat(b_amount_all) + parseFloat(amount)), 8 );
			
		b_amount_all = $('#buyorders_amount_all_'+market_id).text( total_amount_new ).fadeIn();
	}else{	//buy side
		console.log('update total amount orders - buy');
		var s_total_all =  $('#sellorders_total_all_'+market_id).text();
		
		var total_sell_new = 0;
		if (traded == 'yes')
			total_sell_new = prettyFloat( (parseFloat(s_total_all) - parseFloat(total)), 8 );
		else	
			total_sell_new = prettyFloat( (parseFloat(s_total_all) + parseFloat(total)), 8 );

		s_total_all = $('#sellorders_total_all_'+market_id).text( total_sell_new ).fadeIn();

	}	
}
//function updateYourOrdersTable(type, market_id, order_id, total, amount, amount_real_trading, amount_real_trading_total){
function updateYourOrdersTable(type, market_id, order_id, amount, price){
	//type = sell/buy
	//update your order
	//if($('#yourorders_'+market_id+' #yourorder-'+order_id+' .amount')!==undefined){
	if($('#yourorders_'+market_id+' #yourorder-'+order_id).length)	{
		//console.log('update your order '+type+' init'); 
		var y_amount_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+order_id+' .amount').text());
		var y_total_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+order_id+' .total').text());

		y_amount_old = parseFloat(y_amount_old);
		y_total_old = parseFloat(y_total_old);
		
		amount = parseFloat(amount);
		price = parseFloat(price);
		var total = amount*price;
		
		var y_new_amount = (y_amount_old-amount).toFixed(8);
		var y_new_total = (y_total_old-total).toFixed(8);
		
		console.log('y_amount_old: ' + y_amount_old + ', y_total_old '+ y_total_old +', y_new_amount '+y_new_amount+', y_new_total '+y_new_total);
		//var y_new_amount = (parseFloat(y_amount_old)-parseFloat(amount_real_trading)).toFixed(8);
		if(y_new_amount<='0.00000000' || y_new_amount<=0.00000000 || y_new_amount <= 0 || isNaN(y_new_amount)){
			$('#yourorders_'+market_id+' #yourorder-'+order_id).remove();
			console.log('icee 4: ' + y_new_amount);
		}else{
			$('#yourorders_'+market_id+' #yourorder-'+order_id+' .amount').text(y_new_amount);
			$('#yourorders_'+market_id+' #yourorder-'+order_id+' .total').text(y_new_total);
			//$('#yourorders_'+market_id+' #yourorder-'+order_id).addClassDelayRemoveClass({'elemclass': 'blue'});
		}
		//console.log('update your order '+type+' end'); 
	}else{
		console.log('error '+type+' yourorder updating');
	}
}

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
			
			socket = io.connect('<?php echo url('/', $parameters = array(), $secure = null);?>:8090/',{secure: true});

			<?php /* Node server is not running*/ ?>
			socket.on('error', function(exception) {
				showMessageSingle('Socket Error 1 - Live prices not available. <br />Socket is not connected!', 'error');
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
				$('#client_count').text(data);
			});		

			socket.on( 'userOrder', function( data ) {
				
				console.log('========userOrder '+data);

				//console.log('data socket:',data);
				var market_id=data.market_id;

					//Update balance
				if(data.data_price !== undefined){
					//console.log('update user balance');
					$('#cur_to').text(data.data_price.balance_coinsecond);
					$('#cur_from').text(data.data_price.balance_coinmain);
				}
					
				if( data.user_orders !== undefined ){
					$.each(data.user_orders, function(key, value){
						console.log(data);
						
						
						if(value['order_b']!== undefined){
							console.log(value['order_b']['action']);

							var amount = parseFloat(value['order_b']['amount']).toFixed(8);
							var total = parseFloat(value['order_b']['total']).toFixed(8);

							var price = parseFloat(value['order_b']['price']).toFixed(8);
							var class_price = price.replace(".","-");
							var class_price = class_price.replace(",","-");
							
							switch(value['order_b']['action']){
								case "insert":
									console.log('insert private buy order, market_id:' +market_id+', yourorder: '+ value['order_b']['id']);
									//insert your buy order, your current order list
									var your_order='<tr id="yourorder-'+value['order_b']['id'] +'" class="order price-'+class_price+'"><td><b style="color:green">Buy</b></td> <td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td><td><span>'+value['order_b']['created_at']['date'] +'</span></td><td><a href="javascript:cancelOrder('+value['order_b']['id'] +');">Cancel</a></td></tr>';
									//$('#yourorders_'+market_id+' > table tr.header-tb').after(your_order);
									
									$('#yourorders_'+market_id+' > table > tbody > tr:first').before(your_order);
									$('#yourorders_'+market_id+' > table > tbody > tr#yourorder-'+value['order_b']['id']).addClassDelayRemoveClass({'elemclass': 'blue affected'});

								break;
							}
						}
						
						//if ($element.parent().length) { alert('yes') }


						if(value['order_s'] !== undefined){ 
							console.log(value['order_s']['action']);
							
							var amount = parseFloat(value['order_s']['amount']).toFixed(8);
							var total = parseFloat(value['order_s']['total']).toFixed(8);

							var price = parseFloat(value['order_s']['price']).toFixed(8);
							var class_price = price.replace(".","-");
							var class_price = class_price.replace(",","-");
							switch(value['order_s']['action']){
								case "insert":
									console.log('insert private sell order, market_id:' +market_id+', yourorder: '+ value['order_s']['id']);
									//insert your sell order, your current order list
									var your_order='<tr id="yourorder-'+value['order_s']['id'] +'" class="order price-'+class_price+'"><td><b style="color:red">Sell</b></td> <td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td><td><span>'+value['order_s']['created_at']['date'] +'</span></td><td><a href="javascript:cancelOrder('+value['order_s']['id'] +');">Cancel</a></td></tr>';
									
									$('#yourorders_'+market_id+' > table > tbody > tr:first').before(your_order);
									$('#yourorders_'+market_id+' > table > tbody > tr#yourorder-'+value['order_s']['id']).addClassDelayRemoveClass({'elemclass': 'red affected'});
									
								break;
							}
						}
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

				/*
				var socket_market_id;
				socket.on('init_market', function(data){
					
					socket_market_id = data.market_id;
					console.log('socket market id1:'+socket_market_id);				
				});
				console.log('socket market id2:'+socket_market_id);				
				
				
				*/
				var market_id=data.market_id;
				
				//update order buy                 
            	//console.log('data message_socket: ',data.message_socket);
            	
				$.each(data.message_socket, function(key, value){
					//console.log('obj aaa: ',key);
				    console.log("message socket data: "+key + ": " + value);
			
					
					var order_type_value
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
	            		var class_price = class_price.replace(",","-");

						
	            		//console.log('class_price',class_price);
	            		console.log('action',value[order_type_value]['action']); 
	               		
						if(value[order_type_value]['action'] == 'insert'){
	               				//console.log('insert '+order_type_string,$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price));
	               				//if($('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).text()!==undefined){
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
		               				//console.log('Insert '+order_type_string);	New buy/sell order
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

	               				//console.log('update '+order_type_string+' init');
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
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).fadeOut();
									else
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_update, 'delaysec': 1000}).fadeOut();


									console.log('icee do'+order_type_string+'opposite: ' + new_amount);
									console.log('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price);
	           					}else{
	           						$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).attr('onclick','use_price(2,'+price +','+new_amount+')');
	           						$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .amount').text(new_amount);
		               				$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price+' .total').text(new_total);
									$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_update});
									
									if(cancel == false)
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).addClassDelayRemoveClass({'elemclass': order_type_class_update, 'delaysec': 1000});
									else
										$('#orders_'+order_type_string+'_'+market_id+' .price-'+class_price).hide().fadeIn();
	           					}
	               				//console.log('update '+order_type_string+' end');

	               				//update your order
								
								//updateYourOrdersTable(order_type_string, market_id, value[order_type_value]['id'], total, amount, amount_real_trading, amount_real_trading_total);
								/*
	               				if($('#yourorders_'+market_id+' #yourorder-'+value[order_type_value]['id']+' .amount')!==undefined){
	               					console.log('update your buy order init');
	               					var y_amount_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value[order_type_value]['id']+' .amount').text());
		               				var y_total_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value[order_type_value]['id']+' .total').text());
		               				var y_new_amount = (parseFloat(y_amount_old)-parseFloat(amount)).toFixed(8);
	               					if(y_new_amount=='0.00000000' || y_new_amount==0.00000000){
		           						$('#yourorders_'+market_id+' #yourorder-'+value[order_type_value]['id']).remove();
										console.log('icee 2: ' + new_amount);
		           					}else{
		           						$('#yourorders_'+market_id+' #yourorder-'+value[order_type_value]['id']+' .amount').text(y_new_amount);
			               				$('#yourorders_'+market_id+' #yourorder-'+value[order_type_value]['id']+' .total').text((parseFloat(y_total_old)-parseFloat(total)).toFixed(8));
			               				$('#yourorders_'+market_id+' #yourorder-'+value[order_type_value]['id']).addClassDelayRemoveClass({'elemclass': 'blue', 'delaysec': 1000});
		           					}
		               				//console.log('update your '+order_type_string+' order end');
	               				}else{
									console.log('error '+order_type_string+' yourorder updating');
								}
								*/
	               		}

								
	               		
						//alert (amount_real_trading);
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
               		if(change>=0){  
               			//console.log('Up ');           			
               			$('#spanChange-'+market_id).removeClass('up down').addClass('up');
               			$('#spanChange-'+market_id).text('+'+data.change_price.change+'%');
               			//console.log('Up 1a ');   
               		}else{
               			//console.log('Down ');               			 
               			$('#spanChange-'+market_id).removeClass('up down').addClass('down');
               			$('#spanChange-'+market_id).text(''+data.change_price.change+'%');
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

<?php 
//var_dump(   $queries = DB::getQueryLog() );

?>
@stop
