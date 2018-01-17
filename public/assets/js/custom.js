$(function() {

//Sidebar
	/*
	//Open
	$('#slide-submenu').on('click',function() {			        
        $(this).closest('.list-group').toggle('slide',function(){
        	$('.mini-submenu').toggle('slide');
        });
        
      });
	*/
	/*
		//Open/Close Toggle
	$('.mini-submenu').on('click',function(){		
        $(this).next('.list-group').toggle('slide');
        //$('.mini-submenu').hide();
	})
	*/
	
	//Sidebar Menu - Toggle
	$('[data-toggle=offcanvas]').click(function() {
		$('.row-offcanvas').toggleClass('active');
	});
	
		
		





	//START - Sidebar
	//Search sidebar
	$('#sidebar_search_market').keyup(function(){

	   var valThis = $(this).val().toLowerCase();
		$('ul.market>li').each(function(){
		 var text = $(this).text().toLowerCase();
			(text.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();            
	   });
	   
	});
		
	//STOP - Sidebar

	/*
	//Table Sorting
	if ( $( "#market_place" ).length ) {
		var $btc_table = $('table.market_table').tablesorter({
			//theme: 'blue',
			//widgets: ["zebra", "filter"],
			widgets: ["filter"],
			//widgets: ['zebra', 'columnSelector', 'stickyHeaders', "savesort"],
			widgetOptions : {				

				// filter_anyMatch replaced! Instead use the filter_external option
				// Set to use a jQuery selector (or jQuery object) pointing to the
				// external filter (column specific or any match)
				filter_external : '.market_search',
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
			/*
			}
		});
	}
	*/

	/*
	 Table Sorting and Filtering
	*/
	// Show on - All Markets page
	
if ( $( ".market_table" ).length ) {
//if ( $( "#market_place" ).length ) {
		var $btc_table = $('table.market_table').tablesorter({
			//theme: 'blue',
			//widgets: ["zebra", "filter"],
			//widgets: ["filter"],
			widgets: ['zebra', 'columnSelector', 'stickyHeaders', "savesort"],
			//widgets: ['zebra', 'columnSelector', 'stickyHeaders'],
			widgetOptions : {
				// target the column selector markup
      columnSelector_container : $('#columnMarketSelector'),
      // column status, true = display, false = hide
      // disable = do not display on list
      columnSelector_columns : {
        0: 'disable' /* set to disabled; not allowed to unselect it */
      },
      // remember selected columns (requires $.tablesorter.storage)
      //columnSelector_saveColumns: true,

      // container layout
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
	  //columnSelector_layout : '<div class="checkbox"><input type="checkbox"><label>{name}</label></div>',
      // data attribute containing column name to use in the selector container
      columnSelector_name  : 'data-selector-name',

      /* Responsive Media Query settings */
      // enable/disable mediaquery breakpoints
      columnSelector_mediaquery: true,
      // toggle checkbox name
      columnSelector_mediaqueryName: 'Show All',
      // breakpoints checkbox initial setting
      columnSelector_mediaqueryState: true,
      // responsive table hides columns with priority 1-6 at these breakpoints
      // see http://view.jquerymobile.com/1.3.2/dist/demos/widgets/table-column-toggle/#Applyingapresetbreakpoint
      // *** set to false to disable ***
      columnSelector_breakpoints : [ '20em', '30em', '40em', '50em', '60em', '70em' ],
      // data attribute containing column priority
      // duplicates how jQuery mobile uses priorities:
      // http://view.jquerymobile.com/1.3.2/dist/demos/widgets/table-column-toggle/
      columnSelector_priority : 'data-priority',

      // class name added to checked checkboxes - this fixes an issue with Chrome not updating FontAwesome
      // applied icons; use this class name (input.checked) instead of input:checked
      columnSelector_cssChecked : 'checked',
				
				

				// filter_anyMatch replaced! Instead use the filter_external option
				// Set to use a jQuery selector (or jQuery object) pointing to the
				// external filter (column specific or any match)
				filter_external : '.market_search',
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
	}


		
// initialize column selector using default settings
  // note: no container is defined!
  /*
  $(".bootstrap-popup").tablesorter({
	widgets: ['zebra', 'columnSelector', 'stickyHeaders', "savesort"]
  });
  */
// Show on - All Markets page
if ( $( ".market_table" ).length ) {
  
  // call this function to copy the column selection code into the popover
  $.tablesorter.columnSelector.attachTo( $('.bootstrap-popup'), '#popoverMarketSelectorTarget');
  //alert ($.tablesorter.columnSelector.toSource());

  $('#popoverMarketSelectorTarget input[data-column="auto"]').not(':first').parent().remove();


  $('#popoverMarketSelector')
    .popover({
      placement: 'bottom',
      html: true, // required if content has HTML
      content: $('#popoverMarketSelectorTarget')
      //content: $('#popoverMarketSelectorTarget_backup').html()
    });
}	

	
	
	
	



});

///////////////////////
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
			price = price.replace(/[^0-9\.]+/g, "");
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
			price = price.replace(/[^0-9\.]+/g, "");
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

}
		


////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

/*
 @ PNotify - Add and Remove CSS-Class
*/
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
		//update total amount of BTC SELL
		var b_amount_all = $('#buyorders_amount_all_'+market_id).text();
		
		var total_amount_new = 0;
		if (traded == 'yes')	//substract
			total_amount_new = prettyFloat( (parseFloat(b_amount_all) - parseFloat(amount)), 8 );
		else					//addition
			total_amount_new = prettyFloat( (parseFloat(b_amount_all) + parseFloat(amount)), 8 );
			
		$('#buyorders_amount_all_'+market_id).text( total_amount_new ).fadeIn();
		$('#buyorders_amount_all_box_'+market_id).text( prettyFloat(total_amount_new, 1) ).fadeIn();
		
	}else{	//buy side
		console.log('update total amount orders - buy');
		
		//update total amount of COIN BUY
		var s_total_all =  $('#sellorders_total_all_'+market_id).text();
		
		var total_sell_new = 0;
		if (traded == 'yes')
			total_sell_new = prettyFloat( (parseFloat(s_total_all) - parseFloat(total)), 8 );
		else	
			total_sell_new = prettyFloat( (parseFloat(s_total_all) + parseFloat(total)), 8 );

		$('#sellorders_total_all_'+market_id).text( total_sell_new ).fadeIn();
		$('#sellorders_total_all_box_'+market_id).text( prettyFloat( total_sell_new, 8) ).fadeIn();

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

//DIALOG BOXES
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