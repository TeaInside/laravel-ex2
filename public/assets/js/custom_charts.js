/*
var getChartURL = "<?php echo action('HomeController@getChart')?>";
var getMarketID = "<?php echo $market_id ?>";
var getOrderDepthChart = "<?php echo action('OrderController@getOrderDepthChart')?>";
var transError = "{{{ trans('texts.error') }}}";
var coinSecond = "{{{$coinsecond}}}";
*/

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
		url:'/get-chart',
		type:'post',
		dataType:'json',
		data: {Ajax:1,timeSpan:timeSpan_,market_id:getMarketID},
		cache:false,
		async:true,
		beforeSend: function(request) {
			return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
		},
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
		}, error:function(response) {
				showMessageSingle(transError, 'error');
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
       		     url:'/get-orderdepth-chart',
        	     type:'post',
        	     dataType:'json',
				 data: {Ajax:1,market_id:getMarketID},
				 cache:false,
				 async:true,
  				 beforeSend: function(request) {
					return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
				 },
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
                         "unit": coinSecond,
                         "titleBold": false
                     }],
                     "graphs": [{
                         "id": "g1",
                         "valueAxis": "v1",
                         "lineColor": "#00ff00",
                         "lineThickness": 2,
                         "hideBulletsCount": 30,
                         "valueField": "bid_total",
                         "balloonText": "<strong>[[value]]</strong> "+coinSecond+" to get to [[price]]",
                         "fillAlphas": 0.4
                     }, {
                         "id": "g2",
                         "valueAxis": "v1",
                         "lineColor": "#ff0000",
                         "lineThickness": 2,
                         "hideBulletsCount": 30,
                         "valueField": "ask_total",
                         "balloonText": "<b>[[value]]</b> "+coinSecond+" to get to [[price]]",
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
