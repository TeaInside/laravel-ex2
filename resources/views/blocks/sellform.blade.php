<h3>{{{ trans('texts.sell')}}} {{{ $coinmain }}}</h3>
<div class="inblock order_header">
  <div class="header-left">
  	{{{ trans('texts.your_balance')}}}: 
    <!-- <a href="javascript:void(0)" onclick="a_calc(17)"><b><span id="cur_from" class="money_rur">{{{ $balance_coinmain }}}</span> {{{ $coinmain }}}</b></a> -->
    <a id="sell_coin_link" data-amount="{{{ $balance_coinmain }}}" href="#"><b><span id="cur_from" class="money_rur">{{{ $balance_coinmain }}}</span> {{{ $coinmain }}}</b></a>
  </div>  
</div>
  <form class="form-horizontal inblock">
	
    <div class="form-group">
      <label class="col-lg-2 control-label" for="s_amount">{{{ trans('texts.amount') . ' ' . $coinmain}}} </label>
      <div class="col-lg-10 input-group">      
        <input id="s_amount" name="s_amount" class="form-control" type="text" value="0">
		<span class="input-group-addon">{{{ $coinmain }}}</span> 
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2 control-label" >{{{ trans('texts.price_per')}}} {{{ $coinmain }}}</label>
      <div class="col-lg-10 input-group">      
        <input id="s_price" name="s_price" class="form-control" type="text" value="{{$buy_highest}}">
		<span class="input-group-addon">{{{ $coinsecond }}}</span> 
      </div>
    </div>
	
	<div class="forConfirm">
		<div class="form-group">
		  <label class="col-lg-2 control-label" >{{{ trans('texts.total')}}}</label>
		  <div class="col-lg-10 input-group">
			  <span class="">
			   <strong id="s_all">0.00</strong> <strong>{{{ $coinsecond }}}</strong>
			  </span>
			</div>
		</div>


		<div class="form-group">
		  <label class="col-lg-2 control-label" >{{{ trans('texts.trading_fee_short')}}} (<span id="fee_sell">{{$fee_sell}}</span>%)</label>
		  <div class="col-lg-10 input-group">
			  <span class="">
			   <strong id="s_fee">0 </strong> <strong>{{{ $coinsecond }}}</strong>
			  </span>
			</div>
		</div>
		
		<div class="form-group">
		  <label class="col-lg-2 control-label" >{{{ trans('texts.net_total')}}}</label>
		  <div class="col-lg-10 input-group">
			  <span class="">
			   <strong id="s_net_total">0 </strong> <strong>{{{ $coinsecond }}}</strong>
			  </span>
			</div>
		</div>
		
	</div>
	
    <div class="form-group">
    	<hr>
      <span id="s_message"></span>
    </div>
    
    <div class="control-group"> 
		@if($enable_trading == 1)
			<input type="hidden" name="sell_market_id" id="sell_market_id" value="{{{Session::get('market_id')}}}">  
			<!-- <button type="button" class="btn" id="calc_sell">{{trans('texts.caculate')}}</button> -->
			<button type="button" class="btn btn-primary btn-danger" id="do_sell">{{trans('texts.sell')}} {{{ $coinmain }}}</button>
			<div style="display:none; width:75px; height:42px; padding:8px 12px; float: right;" id="sell_loader">
			  <i class="fa fa-circle-o-notch fa-spin fa-1x" style="color:#27c295; "></i>
			</div>
		@else
			<div class="alert alert-danger">
				<i class="fa fa-exclamation-triangle"></i> <strong>{{{ trans('texts.market_disabled')}}}</strong>
			</div>	
		@endif
    </div>
  </form> 

<script type="text/javascript">
function updateDataSell(){
    var amount = $('#s_amount').val();
    var price = $('#s_price').val();
    var fee = $('#fee_sell').html();
    var total = amount*price;
    var fee_amount = total*(fee/100);
    $('#s_all').html(total.toFixed(8));
    $('#s_fee').html(fee_amount.toFixed(8));
    $('#s_net_total').html((total-fee_amount).toFixed(8));
  }
  

$(function(){
  $('#sell_coin_link').click(function(e) {
    e.preventDefault();
    //$('#s_amount').val( $(this).data('amount') );
    $('#s_amount').val( $('#cur_from').html() );
    
    updateDataSell();
  });

  updateDataSell();
  $('#s_amount, #s_price').keyup(function(event) {
    updateDataSell();
  });

  $('#do_sell').click(function(e) {
    e.preventDefault();
      var market_id = $('#sell_market_id').val();
      var price = prettyFloat($('#s_price').val(), 8);
      var amount = prettyFloat($('#s_amount').val(), 8);
      var balance = parseFloat($('#cur_from').html());
      var fee = $('#fee_sell').html();
      var total = amount*price;
      var fee_amount = total*(fee/100); 
      //var net_total = prettyFloat(total+fee_amount, 8);
      var net_total = total+fee_amount;
	  
	  console.log('do_sellf2 -> total : '+ total + ' || amount : ' +amount);
	  
      if(!$('body').hasClass('logged')) {
        //$('#s_message').html('<p style="color:red; font-weight:bold;text-align:center;">{{trans('messages.login_to_trade')}}</p>');
        showMessage(["{{trans('messages.login_to_trade')}}"],'error'); 

      }else if(isNaN(price) || price < 0.00000001){
        //$('#s_message').html('<p style="color:red; font-weight:bold;text-align:center;">{{trans('messages.message_min_price',array('price'=> '0.00000001'))}}</p>');
        showMessage(["{{trans('messages.message_min_price',array('price'=> '0.00000001'))}}"],'error'); 

      }
      else if(isNaN(amount) || amount < {{$limit_trade['min_amount']}} || amount > {{$limit_trade['max_amount']}}){
        //$('#s_message').html('<p style="color:red; font-weight:bold;text-align:center;">{{trans('messages.message_limit_trade',array('min_amount'=> $limit_trade['min_amount'],'max_amount'=> $limit_trade['max_amount']))}}</p>');
        showMessage(["{{trans('messages.message_limit_trade',array('min_amount'=> $limit_trade['min_amount'],'max_amount'=> $limit_trade['max_amount']))}}"],'error'); 

      }      
      else if(balance < amount){
        //$('#s_message').html('<p style="color:red; font-weight:bold;text-align:center;">{{trans('messages.sell_not_enough')}}</p>');
        showMessage(['{{trans('messages.sell_not_enough')}}'],'error'); 

      }
      /*else if(amount>10){
        $('#s_message').html('<p style="color:red; font-weight:bold;text-align:center;">{{trans('messages.message_max_amount',array('amount'=> '10'))}}</p>');
      }*/else{
        /*
		$('#do_sell').fadeOut(500, function() {
          $('#sell_loader').fadeIn();
        });
		*/
		  $('#do_sell').fadeOut(500);
		  $('#sell_loader').fadeIn();

			var tradeArray = [price,amount,market_id, 'sell'];
			BootstrapDialog.confirmTrade(tradeArray);
			
		
		
		<?php
		/*
        $.post('<?php echo action('OrderController@doSell')?>', {isAjax: 1, price: price, amount: amount, market_id:market_id }, function(response){
          var obj = $.parseJSON(response);           
          //app.BrainSocket.message('doTrade',obj.message_socket);
          socket.emit( 'subscribeAllMarkets', obj.message_socket);
          socket.emit( 'userOrder', obj.message_socket_user);
          if(obj.status == 'success'){
             showMessage(obj.messages,'success');
			 //showMessageSingle(obj.message['message'],obj.message['status']);                       
          }else{
             showMessage(obj.messages,'error');
          }
		  
          //$('#sell_loader').fadeOut(500, function() {
            //$('#do_sell').fadeIn();
          //});
		  
		  $('#sell_loader').fadeOut(500);
		  $('#do_sell').fadeIn();
		  
        });
		*/
		?>
      }
  });
});
</script>