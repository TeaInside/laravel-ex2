<?php $current_orders_user_count = count($current_orders_user); ?>

<div class="wrapper-trading buysellorders" id="yourorders_market_{{{Session::get('market_id')}}}">

	<div class="col-12-xs col-sm-12 col-lg-12">
		<h3>{{{ trans('texts.open_orders')}}} ({{{ $current_orders_user_count }}})</h3>
	</div>
	
	
	<!-- <div class="inblock-left" id="yourorders_buy"> -->
	<div class="col-xs-12 col-sm-6" id="yourorders_buy">
		<h3>Your {{{ trans('texts.buy_orders')}}}</h3>
		<div style="color: #fff; background-color: #666 !important;" class="inblock order_header">
		  <div class="header-left">
			Your {{{ trans('texts.buy_orders')}}}
		  </div>
		</div>
	
		<div class="btn-default btn-block">
			<div class="scrolltable nano">
				<table class="table table-striped table-hover ">
					<thead>
					  <th>{{{ trans('texts.price')}}} / {{{$coinmain}}}</th><th>{{{ trans('texts.amount')}}} {{{ $coinmain }}}</th><th>{{{ trans('texts.total')}}} {{{$coinsecond}}}</th><th>{{{ trans('texts.date')}}}</th><th>{{{ trans('texts.action')}}}</th></tr>
					</thead>
					<tbody>
					  <?php 
					  if($current_orders_user_count > 0) : ?>
					  
						@foreach($current_orders_user as $order) 
						
							@if($order->type == 'buy')
							  <?php
								$price = sprintf('%.8f',$order->price);
								$class_price = str_replace(".", "-", $price);
								$class_price = str_replace(",", "-", $class_price);
							  ?>
							  <tr class="order order-{{$class_price}}" id="yourorder-{{$order->id}}">
								<td><span class="price">{{{sprintf('%.8f',$order->price)}}}</span></td>
								<td><span class="amount">{{{sprintf('%.8f',$order->from_value)+0}}}</span></td>
								<td><span class="total">{{{sprintf('%.8f',$order->to_value)}}}</span></td>
								<td><span class="date"><small>{{{date('Y-m-d H:m', strtotime($order->created_at))}}}</span></small></td><!-- title="26 sec. ago" -->
								<td><button type="button" onclick="javascript:cancelOrder(this, {{{$order->id}}});" class="btn btn-danger btn-xs">{{trans('texts.cancel')}}</button></td>
							  </tr>
							@endif
						@endforeach 
					  <?php
						else: ?>
						<tr><td class="order empty" colspan="6"></td></tr>
						  <?php
						  endif;
						  ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<!-- <div class="inblock-right" id="yourorders_sell"> -->
	<div class="col-xs-12 col-sm-6" id="yourorders_sell">
		
		<h3>Your {{{ trans('texts.sell_orders')}}}</h3>
		<div style="color: #fff; background-color: #666 !important;" class="inblock order_header">
		  <div class="header-left">
			Your {{{ trans('texts.sell_orders')}}}
		  </div>
		</div>
	
		<div class="btn-default btn-block">
			<div class="scrolltable nano">
				<table class="table table-striped table-hover ">
					<thead>
					  <th>{{{ trans('texts.price')}}} / {{{$coinmain}}}</th><th>{{{ trans('texts.amount')}}} {{{ $coinmain }}}</th><th>{{{ trans('texts.total')}}} {{{$coinsecond}}}</th><th>{{{ trans('texts.date')}}}</th><th>{{{ trans('texts.action')}}}</th></tr>
					</thead>
					<tbody>
					  <?php 
					  if($current_orders_user_count > 0) : ?>
					  
						@foreach($current_orders_user as $order) 
							@if($order->type == 'sell')
							  <?php
								$price = sprintf('%.8f',$order->price);
								$class_price = str_replace(".", "-", $price);
								$class_price = str_replace(",", "-", $class_price);
							  ?>
							  <tr class="order order-{{$class_price}}" id="yourorder-{{$order->id}}">
								<td><span class="price">{{{sprintf('%.8f',$order->price)}}}</span></td>
								<td><span class="amount">{{{sprintf('%.8f',$order->from_value)+0}}}</span></td>
								<td><span class="total">{{{sprintf('%.8f',$order->to_value)}}}</span></td>
								<td><span class="date"><small>{{{date('Y-m-d H:m', strtotime($order->created_at))}}}</span></small></td><!-- title="26 sec. ago" -->
								<td><button type="button" onclick="javascript:cancelOrder(this, {{{$order->id}}});" class="btn btn-danger btn-xs">{{trans('texts.cancel')}}</button></td>
								
								
								
							  </tr>
							@endif
						@endforeach 
					  <?php
						else: ?>
						<tr><td class="order empty" colspan="6"></td></tr>
						  <?php
						  endif;
						  ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>






	  
	  <script type="text/javascript">
	  function cancelOrder(el, order_id){
	  
		$(el).prop("disabled",true);
		//$(el).attr("disabled", "disabled");
		$.ajax({
			type: 'post',
			url: '/docancel',
			datatype: 'json',
			data: {isAjax: 1, order_id: order_id },
			beforeSend: function(request) {
				return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
			},
			success:function(response) {
				var obj = $.parseJSON(response); 
				//socket.emit( 'subscribeAllMarkets', obj.message_socket);
				var title ='Order removal';
				var msg ='';
				  
				  if(obj.status == 'success'){
					//app.BrainSocket.message('doTrade',obj.message_socket);
					//socket.emit( 'doTrade', obj.message_socket);
					
					msg = obj.message;
					$('#yourorder-'+order_id).fadeOut();
					
				  }else{
					msg = obj.message;
				  }

				BootstrapDialog.show({
					title: title,
					message: msg
				});
			}, error:function(response) {
				showMessageSingle('{{{ trans('texts.error') }}}', 'error');
			}
		});
		
			<?php
			/*
			$.post('<?php echo action('OrderController@doCancel')?>', {isAjax: 1, order_id: order_id }, function(response){
				  var obj = $.parseJSON(response); 
				  var title ='Order removal';
				  var msg ='';
				  
				  if(obj.status == 'success'){
					//app.BrainSocket.message('doTrade',obj.message_socket);
					socket.emit( 'doTrade', obj.message_socket);
					
					msg = '<p style="color:#008B5D; font-weight:bold;text-align:center;">'+obj.message+'</p>';
					$('#yourorder-'+order_id).fadeOut();
					
				  }else{
					msg = '<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>';
				  }

				BootstrapDialog.show({
					title: title,
					message: msg
				});
				
				
				  console.log('Obj: ',obj);
			});
			*/
			?>
		  }
	  </script>
