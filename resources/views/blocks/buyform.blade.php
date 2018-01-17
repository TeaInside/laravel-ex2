<h3>{{{ trans('texts.buy')}}} {{{ $coinmain }}}</h3>
<div class="inblock order_header">
  <div class="header-left">
  	{{{ trans('texts.your_balance')}}}: 
    <!-- <a id="buy_coin_link" data-amount="{{{ $balance_coinsecond }}}" href="javascript:void(0)" onclick="a_calc(17)"><b><span id="cur_to" class="money_rur">{{{ $balance_coinsecond }}}</span> {{{ $coinsecond }}}</b></a> -->
    <a id="buy_coin_link" data-amount="{{{ $balance_coinsecond }}}" href="#"><b><span id="cur_to" class="money_rur">{{{ $balance_coinsecond }}}</span> {{{ $coinsecond }}}</b></a>
  </div>
</div>
    <form class="form-horizontal inblock">
		
    <div class="form-group">
      <label class="col-lg-2 control-label" for="b_amount">{{{ trans('texts.amount') .' '. $coinmain }}}</label>
      <div class="col-lg-10 input-group">      
        <input id="b_amount" name="b_amount" class="form-control" type="text" value="0">
		<span class="input-group-addon">{{{ $coinmain }}}</span> 
      </div>
    </div>
	
    <div class="form-group">
      <label class="col-lg-2 control-label" >{{{ trans('texts.price_per')}}} {{{ $coinmain }}}</label>
      <div class="col-lg-10 input-group">
        <input id="b_price" name="b_price" class="form-control" type="text" value="{{$buy_highest}}">
		<span class="input-group-addon">{{{ $coinsecond }}}</span> 
      </div>
    </div> 
	
	
	<div class="forConfirm">
		<div class="form-group">
		  <label class="col-lg-2 control-label" >{{{ trans('texts.total')}}}</label>
		  <div class="col-lg-10 input-group">
			  <span class="">
			   <strong id="b_all">0.00 </strong> <strong>{{{ $coinsecond }}}</strong>
			  </span>
			</div>
		</div>


		<div class="form-group">
		  <label class="col-lg-2 control-label" >{{{ trans('texts.trading_fee_short')}}} (<span id="fee_buy">{{$fee_buy}}</span>%)</label>
		  <div class="col-lg-10 input-group">
			  <span class="">
			   <strong id="b_fee">0 </strong> <strong>{{{ $coinsecond }}}</strong>
			  </span>
			</div>
		</div>
		

		<div class="form-group">
		  <label class="col-lg-2 control-label" >{{{ trans('texts.net_total')}}}</label>
		  <div class="col-lg-10 input-group">
			  <span class="">
			   <strong id="b_net_total">0 </strong> <strong>{{{ $coinsecond }}}</strong>
			  </span>
			</div>
		</div>
		
		
    </div>
    <div class="form-group">
    	<hr>
      <span id="b_message"></span>
    </div>
    
    <div class="control-group"> 

		@if($enable_trading == 1)
			<input type="hidden" name="buy_market_id" id="buy_market_id" value="{{{Session::get('market_id')}}}">     
			<!-- <button type="button" class="btn" id="calc_buy">{{trans('texts.caculate')}}</button> -->
			<button type="button" class="btn btn-primary btn-success" id="do_buy">{{ trans('texts.buy')}} {{{ $coinmain }}}</button>      
			<div style="display:none; width:75px; height:42px; padding:8px 12px; float: right;" id="buy_loader">
			  <i class="fa fa-circle-o-notch fa-spin fa-1x" style="color:#27c295; "></i>
			</div>
		@else
			<div class="alert alert-danger">
				<i class="fa fa-exclamation-triangle"></i> <strong>{{{ trans('texts.market_disabled')}}}</strong>
			</div>	
		@endif
    </div>
  </form> 


  
  
  
  
  
  
  

<!-- Confirm Modal -->
<div class="modal fade" id="modal_ConfirmOrder" tabindex="-1" role="dialog" aria-labelledby="label_ConfirmOrder" aria-hidden="true" >
  <div class="modal-dialog bootstrap-dialog type-primary" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title bootstrap-dialog-title" id="myModalLabel">Confirm Trade Order</h4>
      </div>
      <div class="modal-body" id="confirm-trade-box">

                <div id="form-container">
                    <form role="form" class="form-horizontal">
						<div class="form-group" style="margin-bottom:10px">
                            <div class=" col-sm-2">
                                {{{ trans('texts.type')}}}: <span id="modal_ConfirmOrder_type" ></span> 
                            </div>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    {{{ trans('texts.market')}}}: <strong>{{{ $coinmain }}}/{{{ $coinsecond }}}</strong>
                                </div>
                            </div>
                        </div>
						
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class=" col-sm-2">
                                {{{ trans('texts.amount')}}}:
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    
                                    <div id="confirm_trade_amount" class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinmain }}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class=" col-sm-2">
                                {{{ trans('texts.price')}}}:
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
									<div id="confirm_trade_price" class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinsecond }}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class=" col-sm-2">
                                {{{ trans('texts.total')}}}:
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
									<div id="confirm_trade_total" class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinsecond }}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class=" col-sm-2">
								{{{ trans('texts.trading_fee_short')}}} <small>(<span id="confirm_trade_fee_percent">{{$fee_buy}}</span>%)</small>
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
									<div id="confirm_trade_fee" class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinsecond }}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px">
                            <h5 class="col-sm-2">
                                {{{ trans('texts.net_total')}}}:
                            </h5>
                            <div class="col-sm-5">
                                <div class="input-group">
									<div id="confirm_trade_net_total" disabled class="form-control form-control-div text-right" ></div>
                                    <span class="input-group-addon">{{{ $coinsecond }}}</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

				<div class="row ">
					<div class="alert alert-warning">
						<p>{{{ trans('texts.disclaimer') }}}</p>
						<p>{{{ trans('texts.disclaimer_warning') }}}</p>
					</div>
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> {{{ trans('texts.cancel') }}}</button>
        <button type="button" class="btn btn-primary"><i class="fa fa-check"></i> {{{ trans('texts.confirm') }}}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

$('#modal_ConfirmOrder').on('shown.bs.modal', function () {
  

  
})

function updateDataBuy(){
    var amount = $('#b_amount').val(); 
    var price = $('#b_price').val();
    var fee = $('#fee_buy').html();

    var total = amount*price;
    var fee_amount = total*(fee/100);
    $('#b_all').html(total.toFixed(8)); 
    $('#b_fee').html(fee_amount.toFixed(8));
    $('#b_net_total').html((total+fee_amount).toFixed(8));
  }
  
function doPostTradeOrder(tradeArray){

		var price, amount, market_id;
			
		price = tradeArray[0];
		amount = tradeArray[1];
		market_id = tradeArray[2];
		type = tradeArray[3];
		var ajax_trade_url;
		
		if(type == 'buy'){
			ajax_trade_url = '<?php echo action('OrderController@doBuy')?>';
		}else if(type == 'sell'){
			ajax_trade_url = '<?php echo action('OrderController@doSell')?>';
		}
		
	$.ajax({
		type: 'post',
		url: ajax_trade_url,
		datatype: 'json',
		data: {isAjax: 1, price: price, amount: amount, market_id: market_id },
		beforeSend: function(request) {
			return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
		},
		success:function(response) {
			var obj = $.parseJSON(response);
			//app.BrainSocket.message('doTrade',obj.message_socket);          

			

			if(obj.status == 'success'){
				socket.emit( 'subscribeAllMarkets', obj.message_socket);
				socket.emit( 'userOrder', obj.message_socket_user);
				showMessage(obj.messages,'success');
			}else{
				showMessage(obj.messages,'error');
			}
			
			if(type == 'buy'){
				$('#do_buy').fadeIn();
				$('#buy_loader').fadeOut(500);
			}else if(type == 'sell'){
				$('#do_sell').fadeIn();
				$('#sell_loader').fadeOut(500);
			}

		}, error:function(response) {
			showMessageSingle('{{{ trans('texts.error') }}}', 'error');
		}
	});
}

		/**
         * BootstrapDialog Confirm Trade modal box
         * 
         * @param {type} tradeArr [price, amount, market_id]
         * @param {type} callback
         * @returns {undefined}
         */
        BootstrapDialog.confirmTrade = function(tradeArray, callback) {
			
			var price, amount, market_id;
			
			price = tradeArray[0];
			amount = tradeArray[1];
			market_id = tradeArray[2];
			type = tradeArray[3];
			
			if(type == 'buy'){
				  $('#confirm_trade_amount').text( $('#b_amount').val() );
				  $('#confirm_trade_price').text( $('#b_price').val() );
				  $('#confirm_trade_total').text( $('#b_all').text() );
				  $('#confirm_trade_fee_percent').text( $('#fee_buy').val() );
				  $('#confirm_trade_fee').text( $('#b_fee').text() );
				  $('#confirm_trade_net_total').text( $('#b_net_total').text() );
				  $('#modal_ConfirmOrder_type').text( '{{{ trans('texts.buy')}}}' );
			}else if(type == 'sell'){
				  $('#confirm_trade_amount').text( $('#s_amount').val() );
				  $('#confirm_trade_price').text( $('#s_price').val() );
				  $('#confirm_trade_total').text( $('#s_all').text() );
				  $('#confirm_trade_fee_percent').text( $('#fee_sell').val() );
				  $('#confirm_trade_fee').text( $('#s_fee').text() );
				  $('#confirm_trade_net_total').text( $('#s_net_total').text() );
				  $('#modal_ConfirmOrder_type').text( '{{{ trans('texts.sell')}}}' );
			}
			
			/*
			var $buySellContentConfirm = $('#modal_ConfirmOrder .modal-body').html();
			//$buySellContentConfirm.append( $('.buysellform form .forConfirm').html() );
			$buySellContentConfirm.append( $('#modal_ConfirmOrder .modal_body').html() );
			*/
			var $buySellContentConfirmBox = $('<div></div>');
			//$buySellContentConfirm.append( $('.buysellform form .forConfirm').html() );
			$buySellContentConfirmBox.append( $('#modal_ConfirmOrder .modal-body').html() );
		
		
			
			var callback = function(result) {
				
				// result will be true if button was click, while it will be false if users close the dialog directly.
				if(result) {
					doPostTradeOrder(tradeArray);
					/*
					if(type == 'buy'){
						doPostBuyOrder(tradeArray);
					}else if(type == 'sell'){
						doPostSellOrder(tradeArray);
					}
					*/
				}else {
					//showMessageSingle('{{{ trans('texts.error') }}}', 'error');
					if(type == 'buy'){
						$('#do_buy').fadeIn();
						$('#buy_loader').fadeOut(500);
					}else if(type == 'sell'){
						$('#do_sell').fadeIn();
						$('#sell_loader').fadeOut(500);
					}

				}
				
				//console.log(result);
				return result;
			};

            new BootstrapDialog({
                title: '{{{ trans('messages.confirm_buy_order')}}}',
				message: $buySellContentConfirmBox,
				//type: BootstrapDialog.TYPE_WARNING, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
				closable: true, // <-- Default value is false
				//btnOKClass: 'btn-info', // <-- If you didn't specify it, dialog type will be used,
				data: {
                    'callback': callback
                },
				onhide: function(dialog){
					if(type == 'buy'){
						$('#do_buy').fadeIn();
						$('#buy_loader').fadeOut(500);
					}else if(type == 'sell'){
						$('#do_sell').fadeIn();
						$('#sell_loader').fadeOut(500);
					}
				},
				buttons: [{
                        label: '<i class="fa fa-times"></i> {{{ trans('texts.cancel')}}}',
                        action: function(dialog) {
                            typeof dialog.getData('callback') === 'function' && dialog.getData('callback')(false);
                            dialog.close();
                        }
                    }, {
                        label: '<i class="fa fa-check"></i> {{{ trans('texts.confirm')}}}',
                        cssClass: 'btn-primary',
                        action: function(dialog) {
                            typeof dialog.getData('callback') === 'function' && dialog.getData('callback')(true);
                            dialog.close();
                        }
                    }]
            }).open();
			
			return callback;
        };
		
$(function(){  
  $('#buy_coin_link').click(function(e) {
    e.preventDefault();

    //var total = parseFloat($(this).data('amount'));
    //var total = parseFloat($('#cur_to').html()) /1.001; //balance
    var total = parseFloat($('#cur_to').html()) ; //balance
    var price = $('#b_price').val();
    var amount = total/price;
    var fee = $('#fee_buy').html();   
    var fee_amount = total*(fee/100);
    //var amount = total/fee;

    $('#b_all').html(total.toFixed(8)); 
    $('#b_net_total').html( prettyFloat(total+fee_amount, 8));
    //$('#b_all').html(total/1.001).toFixed(8)); 
    $('#b_fee').html(fee_amount.toFixed(8));
    $('#b_amount').val(amount.toFixed(8)); 

  });

  updateDataBuy();
  $('#b_amount, #b_price').keyup(function(event) {
    updateDataBuy();
  });

  $('#do_buy').click(function(e) {
     e.preventDefault(); 
      var market_id = $('#buy_market_id').val();
      var price = prettyFloat($('#b_price').val(), 8);
      var amount = prettyFloat($('#b_amount').val(), 8); 
      //var balance = prettyFloat($('#cur_to').html(), 8);
	  var balance = parseFloat($('#cur_to').html());
	  
      var fee = $('#fee_buy').html();
      var total = amount*price;
      var fee_amount = total*(fee/100); 
      //var net_total = prettyFloat(total+fee_amount, 8);
      var net_total = total+fee_amount;
     
	console.log('do_buy -> total : '+ total + ' || amount : ' +amount);

/*	
if(balance < net_total)
    alert('print ok');
else
    alert('print false');
*/


      if(!$('body').hasClass('logged')) {
        showMessage(["{{trans('messages.login_to_trade')}}"],'error'); 

       
      }else if(isNaN(price) || price < 0.00000001){
        
        showMessage(["{{trans('messages.message_min_price',array('price'=> '0.00000001'))}}"],'error'); 
      }
      else if(isNaN(amount) || amount < {{$limit_trade['min_amount']}} || amount > {{$limit_trade['max_amount']}}){
        showMessage(["{{trans('messages.message_limit_trade',array('min_amount'=> $limit_trade['min_amount'],'max_amount'=> $limit_trade['max_amount']))}}"],'error'); 

        
      }      
	  //else if(parseFloat(balance.toFixed(8)) < parseFloat(net_total.toFixed(8))){
      else if(prettyFloat(balance, 8) < prettyFloat(net_total, 8) ){
        showMessage(['{{trans('messages.buy_not_enough')}}'],'error'); 
        showMessage(['balance: '+balance + ' < ' +net_total + ' net_total'],'error'); 
       
      }
      /*else if((amount*price)>10){
        $('#b_message').html('<p style="color:red; font-weight:bold;text-align:center;">{{trans('messages.message_max_total',array('total'=> '10'))}}</p>');
      }*/else{
		$('#do_buy').fadeOut(500);
		$('#buy_loader').fadeIn();
        /*
		$('#do_buy').fadeOut(500, function() {
          $('#buy_loader').fadeIn();
        });
		*/
		

		var tradeArray = [price,amount,market_id, 'buy'];
		BootstrapDialog.confirmTrade(tradeArray);
		
		

		<?php
		/*
        $.post('<?php echo action('OrderController@doBuy')?>', {isAjax: 1, price: price, amount: amount, market_id:market_id }, function(response){
          var obj = $.parseJSON(response);
          //app.BrainSocket.message('doTrade',obj.message_socket);          
          socket.emit( 'subscribeAllMarkets', obj.message_socket);
		  socket.emit( 'userOrder', obj.message_socket_user);
          if(obj.status == 'success'){ 
            showMessage(obj.messages,'success');                       
            //showMessageSingle(obj.message['message'],obj.message['status']);                       
			//alert( obj.message['message'] );
          }else{
            showMessage(obj.messages,'error');           
          }
		  
          //$('#buy_loader').fadeOut(500, function() {
            //$('#do_buy').fadeIn();
          //});
		  
		  $('#do_buy').fadeIn();
		  $('#buy_loader').fadeOut(500);



          //console.log('Obj: ',obj);
        });
		*/
		?>
      }
    });
});

</script>