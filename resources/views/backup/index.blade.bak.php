@extends('layouts.default')
@section('content')

@if(isset($show_all_markets) && $show_all_markets === true)

	<h2 style="margin-top:0px;">BTC 24 Hour trade statistics</h2>
	<table class="table table-striped table-hover">
		<thead>
			<tr class="header-tb">
			<th>Currency</th>
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
			<tr>
				<td >
					@if(!empty($am['logo']))                        
						<img width="23" height="23" src="{{asset('')}}/{{$am['logo']}}" />
					@else
					&nbsp;
					@endif
					<a style="font-weight:bold; text-decoration:none; color:orange;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}</a>
				</td>
				<td><a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from_name']}} / {{$am['to_name']}}</a></td>
				<td><a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['lastest_price']}}</a></td>
				<td>
					<a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">@if(empty($am['prices']->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->max)}} @endif</a>
				</td>
				<td>
					<a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">@if(empty($am['prices']->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->min)}} @endif</a>
				</td>
				<td>
					<a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
				</td>
			</tr>
			@endif
		@endforeach
		</tbody>
	</table>

	<h2 style="margin-top:0px;">LTC 24 Hour trade statistics</h2>
	<table class="table table-striped table-hover">
		<thead>
			<tr class="header-tb">
			<th>Currency</th>
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
			<tr>
				<td >
					@if(!empty($am['logo']))                        
						<img width="23" height="23" src="{{asset('')}}/{{$am['logo']}}" />
					@else
					&nbsp;
					@endif
					<a style="font-weight:bold; text-decoration:none; color:orange;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}</a>
				</td>
				<td><a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from_name']}} / {{$am['to_name']}}</a></td>
				<td><a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['lastest_price']}}</a></td>
				<td>
					<a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">@if(empty($am['prices']->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->max)}} @endif</a>
				</td>
				<td>
					<a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">@if(empty($am['prices']->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$am['prices']->min)}} @endif</a>
				</td>
				<td>
					<a style="font-weight:bold; text-decoration:none;" href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
				</td>
			</tr>
			@endif
		@endforeach
		</tbody>
	</table>
Sponsored currency
@endif

<h2 style="margin-top:0px;">Market:{{$market_from}}/{{$market_to}}</h2>
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
			<div class="success box" id="lastprice-{{{Session::get('market_id')}}}">Last Price:<br><strong><span id="spanLastPrice-{{{Session::get('market_id')}}}">@if(empty($lastest_price)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$lastest_price)}} @endif</span></strong></div>
		</div>
		<div class="item25">
			<div class="success box">24 Hour High:<br><strong><span id="spanHighPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->max)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->max)}} @endif</span></strong></div>
		</div>
		<div class="item25">
			<div class="success box">24 Hour Low:<br><strong><span id="spanLowPrice-{{{Session::get('market_id')}}}">@if(empty($get_prices->min)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->min)}} @endif</span></strong></div>
		</div>
		<div class="item25">
			<div class="success box">24 Hour Volume:<br><strong><span id="spanVolume-{{{Session::get('market_id')}}}">@if(empty($get_prices->volume)) {{{sprintf('%.8f',0)}}} @else {{sprintf('%.8f',$get_prices->volume)}} @endif</span> {{{ $coinmain }}}</strong></div>
		</div>
	</div>
	


	<div class="inblock">
		<div id="chartdiv" style="width:100%; height:400px;"></div>
	</div>
	<!-- Sell/Buy -->	
	@if ( Auth::guest() )
	@else
		<div class="wrapper-trading">
			<div class="inblock-left">
				@include('blocks.buyform')
			</div>	
			<div class="inblock-right">		
				@include('blocks.sellform')	
			</div>
		</div>
	@endif

	<div class="wrapper-trading">
		<div class="inblock-left">
			@include('blocks.sellorders')
		</div>	
		<div class="inblock-right">		
			@include('blocks.buyorders')
		</div>
	</div>
<!-- Trade history -->
	<div class="btn btn-default btn-block">		
		@include('blocks.tradehistory')				
	</div>
		<!-- Your Active Order  -->
	@if ( Auth::guest() )
	@else
	<div class="btn btn-default btn-block">		
		@include('blocks.yourorders')				
	</div>
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
<div class="notifyjs-corner" style="bottom: 0px; left: 25px;">
	<!-- <div class="notifyjs-wrapper notifyjs-hidable">	  	
	  	<div class="notifyjs-container">
	  		<div class="notifyjs-bootstrap-base notifyjs-bootstrap-success">
				<span data-notify-text="">Access granted</span>
			</div>
		</div>
	</div>
	<div class="notifyjs-wrapper notifyjs-hidable">	  	
	  	<div class="notifyjs-container">
	  		<div class="notifyjs-bootstrap-base notifyjs-bootstrap-success">
				<span data-notify-text="">Access granted</span>
			</div>
		</div>
	</div> -->
</div>
{{HTML::style('assets/amcharts/style.css')}}
{{ HTML::script('assets/amcharts/amcharts.js') }}
{{ HTML::script('assets/amcharts/serial.js') }}
{{ HTML::script('assets/amcharts/amstock.js') }}
<script type="text/javascript">
function use_price(type, price, total_amount){
	// var pre = 'b_';
	// if(type==2) pre = 's_';
	// $('#'+pre+'price').val(price.toFixed(8));
	// $('#'+pre+'amount').val(total_amount.toFixed(8));
	$('#s_price').val(price.toFixed(8));
	$('#s_amount').val(total_amount.toFixed(8));
	$('#b_price').val(price.toFixed(8));
	$('#b_amount').val(total_amount.toFixed(8));
	updateDataSell();
	updateDataBuy();
} 
function showMessage(messages,type){
	var html;
	for (i = 0; i < messages.length; i++) { 
		var message = messages[i];
        html='<div id="notifyjs-'+i+'" class="notifyjs-wrapper notifyjs-hidable '+type+'"><div class="notifyjs-container"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-success"><span data-notify-text="">'+message+'</span></div></div></div>';
		$('.notifyjs-corner').append(html);		   
    }	
}
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
	graph1.negativeLineColor="#F87A06";//"#db4c3c";
	graph1.negativeFillColors="#F87A06";//"#db4c3c";
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
</script>
<!-- <div class="container-fluid">
		<button onclick="testCal()">Test</button>
	</div>  -->
{{ HTML::script('brain-socket-js/lib/brain-socket.min.js') }}
<script type="text/javascript" charset="utf-8">
	/*function testCal(){
		$.post('<?php echo action('HomeController@doTest')?>', {isAjax: 1 }, function(response){
	      var obj = $.parseJSON(response);               
	      console.log('Obj: ',obj);
	      app.BrainSocket.message('doTest',obj);
	    });
	}*/	
		$(function(){

			var fake_user_id = Math.floor((Math.random()*1000)+1);

			//make sure to update the port number if your ws server is running on a different one.
			window.app = {};

			app.BrainSocket = new BrainSocket(
					new WebSocket('wss://192.64.116.155:8080'),
					new BrainSocketPubSub()
			);
			console.log('Obj: ',app.BrainSocket);
			console.log('success: ',app.BrainSocket.success());
			console.log('error: ',app.BrainSocket.error());
			app.BrainSocket.Event.listen('generic.event',function(msg){				
				if(msg.client.data.user_id == fake_user_id){
					$('#chat-log').append('<div class="alert alert-success">Me: '+msg.client.data.message+'</div>');
				}else{
					$('#chat-log').append('<div class="alert alert-info">Them: '+msg.client.data.message+'</div>');
				}
			});

			app.BrainSocket.Event.listen('app.success',function(data){
				console.log('An app success message was sent from the ws server!');
				console.log(data);
			});

			app.BrainSocket.Event.listen('app.error',function(data){
				console.log('An app error message was sent from the ws server!');
				console.log(data);
			});
 			app.BrainSocket.Event.listen('doTest',function(msg){
                console.log('xxxxx: ',msg);
                // var buy_order='<tr id="order-1" class="order blue" onclick="use_price(2,0.002,0.004)"><td class="price">0.002</td><td class="amount">2</td><td class="total">0.004</td></tr>';  
               	// $('#orders_buy > table tr.header-tb').after(buy_order);
            });

 			app.BrainSocket.Event.listen('doTrade',function(msg){
                console.log('doTrade: ',msg);
                var data = msg.client.data;
                var market_id=data.market_id;
                //update order buy                 
                console.log('data message_socket: ',data.message_socket);
				$.each(data.message_socket, function(key, value){
					//console.log('obj aaa: ',key);
				    console.log("data: "+key + ": " + value);
				    //console.log("data: "+key + ": " + value['history_trade']);
				    if(value['order_b']!== undefined){  console.log('order_b',value['order_b']);              		
	               		var amount = parseFloat(value['order_b']['amount']).toFixed(8);
	               		var total = parseFloat(value['order_b']['total']).toFixed(8);
	               		var price = parseFloat(value['order_b']['price']).toFixed(8);
	               		var class_price = price.replace(".","-");
	            		var class_price = class_price.replace(",","-");
	            		console.log('class_price',class_price);
	            		console.log('action',value['order_b']['action']); 
	               		switch(value['order_b']['action']){
	               			case "insert":   
	               				//console.log('insert orders_buy_',$('#orders_buy_'+market_id+' .price-'+class_price));
	               				if($('#orders_buy_'+market_id+' .price-'+class_price).html()!==undefined){
		               				console.log('Update buy:');
		               				var amount_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html());
		               				var total_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .total').html());

		               				$('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html((parseFloat(amount_old)+parseFloat(amount)).toFixed(8));
		               				$('#orders_buy_'+market_id+' .price-'+class_price+' .total').html((parseFloat(total_old)+parseFloat(total)).toFixed(8));
		               				$('#orders_buy_'+market_id+' .price-'+class_price).addClass('blue');
		               				$('#orders_buy_'+market_id+' .price-'+class_price).attr('onclick','use_price(2,'+price +','+(parseFloat(amount_old)+parseFloat(amount)).toFixed(8)+')');
		               			}else{
		               				//console.log('Insert buy');
		               				var buy_order='<tr id="order-'+value['order_b']['id'] +'" class="order blue price-'+class_price+'" onclick="use_price(2,'+value['order_b']['price'] +','+amount+')" data-sort="'+price+'"><td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td></tr>';
		               				if($('#orders_buy_'+market_id+' > table > tbody tr.order').length){
		               					var i_d=0;
			               				$( '#orders_buy_'+market_id+' tr.order').each(function( index ) {
								            var value = $(this).val(); 
								            var price_compare = parseFloat($(this).attr('data-sort'));					
								            if(price>price_compare){
								            	i_d=1;
								            	$(this).before(buy_order);
								            	return false;
								            }
								        });
								        if(i_d==0){
								        	console.log( "add to the end");  
								        	$('#orders_buy_'+market_id+' > table > tbody tr:last-child').after(buy_order);
								        }
		               				}else{
	               						$('#orders_buy_'+market_id+' > table > tbody').html(buy_order);
	               					}		               				  
		               			}
		               			//console.log('insert buy end'); 
	               				break;
	               			case "update":  
	               				//console.log('update buy init');             				
	               				var amount_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html());
	               				var total_old=parseFloat($('#orders_buy_'+market_id+' .price-'+class_price+' .total').html());
	               				
	           					var new_amount = (parseFloat(amount_old)-parseFloat(amount)).toFixed(8);
	           					var new_total = (parseFloat(total_old)-parseFloat(total)).toFixed(8);

	           					if(new_amount=='0.00000000' || new_amount==0.00000000){
	           						$('#orders_buy_'+market_id+' .price-'+class_price).remove();
	           					}else{
	           						$('#orders_buy_'+market_id+' .price-'+class_price).attr('onclick','use_price(2,'+price +','+new_amount+')');
	           						$('#orders_buy_'+market_id+' .price-'+class_price+' .amount').html(new_amount);
		               				$('#orders_buy_'+market_id+' .price-'+class_price+' .total').html(new_total);
		               				$('#orders_buy_'+market_id+' .price-'+class_price).addClass('red');
	           					}
	               				//console.log('update buy end');

	               				//update your order
	               				if($('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .amount')!==undefined){
	               					//console.log('update your buy order init');
	               					var y_amount_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .amount').html());
		               				var y_total_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .total').html());
		               				var y_new_amount = (parseFloat(y_amount_old)-parseFloat(amount)).toFixed(8);
	               					if(y_new_amount=='0.00000000' || y_new_amount==0.00000000){
		           						$('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']).remove();
		           					}else{
		           						$('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .amount').html(y_new_amount);
			               				$('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']+' .total').html((parseFloat(y_total_old)-parseFloat(total)).toFixed(8));
			               				$('#yourorders_'+market_id+' #yourorder-'+value['order_b']['id']).addClass('red');
		           					}
		               				//console.log('update your buy order end');
	               				}               				
	               				break;
	               			case "delete":
	               				$('#orders_buy_'+market_id+' .price-'+class_price).remove();
	               				console.log('Delete '+'#orders_buy_'+market_id+' .price-'+class_price);
	               				//$('#orders_buy_'+market_id+' #order-'+value['order_b']['id']).remove();
	               				break;
	               		}
	               	}
	               	//update order sell
	               	if(value['order_s'] !== undefined){ 
	               		var amount = parseFloat(value['order_s']['amount']).toFixed(8);
	               		var total = parseFloat(value['order_s']['total']).toFixed(8);    
	               		var price = parseFloat(value['order_s']['price']).toFixed(8); 
	               		var class_price = price.replace(".","-");
	            		var class_price = class_price.replace(",","-");   
	            		console.log('order_s',value['order_s']);  
	            		console.log('action',value['order_s']['action']);  
	            		console.log('class_price',class_price);    		
	               		switch(value['order_s']['action']){	               			
	               			case "insert":
	               				//console.log('insert orders_sell_',$('#orders_sell_'+market_id+' .price-'+class_price));
	               				if($('#orders_sell_'+market_id+' .price-'+class_price).html()!==undefined){
	               					//console.log('Update sell:');
	               					var amount_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html());
		               				var total_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .total').html());
		               				
		               				$('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html((parseFloat(amount_old)+parseFloat(amount)).toFixed(8));
		               				$('#orders_sell_'+market_id+' .price-'+class_price+' .total').html((parseFloat(total_old)+parseFloat(total)).toFixed(8));
		               				$('#orders_sell_'+market_id+' .price-'+class_price).addClass('blue');
		               				$('#orders_sell_'+market_id+' .price-'+class_price).attr('onclick','use_price(1,'+price +','+(parseFloat(amount_old)+parseFloat(amount)).toFixed(8)+')');
	               				}else{
		               				//console.log('Insert sell');
	               					var orders_sell='<tr id="order-'+value['order_s']['id'] +'" class="order blue price-'+class_price+'" onclick="use_price(1,'+value['order_s']['price'] +','+amount+')" data-sort="'+price+'"><td class="price">'+price+'</td><td class="amount">'+amount+'</td><td class="total">'+total+'</td></tr>';  
	               					//$('#orders_sell_'+market_id+' > table tr.header-tb').after(orders_sell);
	               					if($('#orders_sell_'+market_id+' > table > tbody tr.order').length){
	               						var i_d=0;
		               					$( '#orders_sell_'+market_id+' tr.order').each(function( index ) {
								            var value = $(this).val(); 
								            var price_compare = parseFloat($(this).attr('data-sort'));					
								            if(price<price_compare){
								            	i_d=1;
								            	$(this).before(orders_sell);
								            	return false;
								            }     
								        });
								        if(i_d==0){
								        	console.log( "add to the end");  
								        	$('#orders_sell_'+market_id+' > table > tbody tr:last-child').after(orders_sell);
								        }
	               					}else{
	               						$('#orders_sell_'+market_id+' > table > tbody').html(orders_sell);
	               					}	               					
	               				}
	               				//console.log('insert sell init'); 
	               				break;
	               			case "update": 
	               				console.log('update sell init');               				
	               				var amount_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html());
	               				var total_old=parseFloat($('#orders_sell_'+market_id+' .price-'+class_price+' .total').html());
	               				
	           					var new_amount = (parseFloat(amount_old)-parseFloat(amount)).toFixed(8);
	           					var new_total = (parseFloat(total_old)-parseFloat(total)).toFixed(8);
	           					if(new_amount=='0.00000000' || new_amount==0.00000000){
	           						$('#orders_sell_'+market_id+' .price-'+class_price).remove();
	           					}else{
	           						$('#orders_sell_'+market_id+' .price-'+class_price).attr('onclick','use_price(1,'+price +','+new_amount+')');
	           						$('#orders_sell_'+market_id+' .price-'+class_price+' .amount').html(new_amount);
		               				$('#orders_sell_'+market_id+' .price-'+class_price+' .total').html(new_total);
		               				$('#orders_sell_'+market_id+' .price-'+class_price).addClass('red');
	           					}
	           					console.log('update sell end'); 
	               				//update your order
	               				if($('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .amount')!==undefined){
	               					console.log('update your order sell init'); 
	               					var y_amount_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .amount').html());
	               					var y_total_old=parseFloat($('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .total').html());

	               					var y_new_amount = (parseFloat(y_amount_old)-parseFloat(amount)).toFixed(8);
	               					if(y_new_amount=='0.00000000' || y_new_amount==0.00000000){
		           						$('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']).remove();
		           					}else{
		           						$('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .amount').html(y_new_amount);
			               				$('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']+' .total').html((y_total_old-total).toFixed(8));
			               				$('#yourorders_'+market_id+' #yourorder-'+value['order_s']['id']).addClass('red');
		           					}
		           					//console.log('update your order sell end'); 
	               				}               				
	               				break;
	               			case "delete":
	               				$('#orders_sell_'+market_id+' .price-'+class_price).remove();
	               				console.log('Delete '+'#orders_sell_'+market_id+' .price-'+class_price);
	               				//$('#orders_sell_'+market_id+' #order-'+value['order_s']['id']).remove();
	               				break;
	               		}
	               	}
	               	//update trade history
	               	if(value['history_trade']!== undefined){console.log('history_trade',value['history_trade']);    
	               		//console.log('history_trade init');
	               		var trade_new = '<tr id="trade-'+value['history_trade']['id'] +'" class="order new">';
	               		trade_new += '<td><span>'+value['history_trade']['created_at']+'</span></td>';
	               		if(value['history_trade']['type'] == 'sell')          
				            trade_new += '<td><b style="color:red;text-transform: capitalize;">'+value['history_trade']['type']+'</b></td>';           
				        else          
				            trade_new += '<td><b style="color:green;text-transform: capitalize;">'+value['history_trade']['type']+'</b></td>';
				        //console.log('history_trade before total: ');
				        var total = parseFloat(value['history_trade']['price'])*parseFloat(value['history_trade']['amount']);
				        var amount = parseFloat(value['history_trade']['amount']).toFixed(8);
				        //console.log('history_trade total: ',total);
				        //console.log('history_trade amount: ',amount);
				        trade_new += '<td>'+parseFloat(value['history_trade']['price']).toFixed(8)+'</td>';
				        trade_new += '<td>'+amount+'</td>';
	          			trade_new += '<td>'+total.toFixed(8)+'</td>';
	               		trade_new+='</tr>'; 
	               		//console.log('history_trade trade_new: ',trade_new);              		
	               		$('#trade_histories_'+market_id+' > table tr.header-tb').after(trade_new);
	               	}
				});              	
	            
               	//update % change price
               	//console.log('change_price init: ',data.change_price);
               	if(data.change_price !== undefined){
               		//console.log('change init: ',data.change_price.change);
              		var change=parseFloat(data.change_price.change);
              		//console.log('curr_price: ',parseFloat(data.change_price.curr_price).toFixed(8));
              		$('#spanPrice-'+market_id).html(parseFloat(data.change_price.curr_price).toFixed(8));
              		$('#spanPrice-'+market_id).attr('yesterdayPrice',parseFloat(data.change_price.pre_price).toFixed(8));
              		$('#volume-'+market_id).html(parseFloat(data.change_price.total_volume).toFixed(8));
              		//console.log('change: ',change);
              		//console.log('change 1: ',data.change_price.change);
               		if(change>=0){  
               			//console.log('Up ');           			
               			$('#spanChange-'+market_id).removeClass('up down').addClass('up');
               			$('#spanChange-'+market_id).html('+'+data.change_price.change+'%');
               			//console.log('Up 1a ');   
               		}else{
               			//console.log('Down ');               			 
               			$('#spanChange-'+market_id).removeClass('up down').addClass('down');
               			$('#spanChange-'+market_id).html(''+data.change_price.change+'%');
               			//console.log('Down a');
               		}               		
               	}
               	//update block price
               	if(data.data_price !== undefined){
               		var old_lastprice = $('#spanLastPrice-'+market_id).html();
               		var new_lastprice=parseFloat(data.data_price.lastest_price).toFixed(8);
               		if(new_lastprice<old_lastprice) $('#lastprice-'+market_id).addClass('red');
               		else $('#lastprice-'+market_id).addClass('blue');
               		$('#spanLastPrice-'+market_id).html(new_lastprice);
               		$('#spanHighPrice-'+market_id).html(parseFloat(data.data_price.get_prices.max).toFixed(8));
               		$('#spanLowPrice-'+market_id).html(parseFloat(data.data_price.get_prices.min).toFixed(8));
               		$('#spanVolume-'+market_id).html(parseFloat(data.data_price.get_prices.volume).toFixed(8));
               	}

               	setTimeout(function(){
               		$('table tr').removeClass("new");
               		$('table tr,li, div.box').removeClass("blue red");               		
               		$('#s_message, #b_message').html('');
               	},10000);
            });
			
		});
	</script>
@stop