<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<!-- Orders History -->
		<?php
		$query_string = '';
		foreach (Request::query() as $key => $value) {
			if($key!='pager_page') $query_string .= $key."=".$value."&";
		}
		$query_string = trim($query_string,'&');
		if($query_string!='') $query_string = "&".$query_string;
		?>
		<div id="orders_history">
			<h2>{{{ trans('texts.orders_history')}}} @if(isset($current_coin)) {{' - '.$current_coin}} @endif</h2>

			<form class="form-inline" method="GET" action="{{Request::url()}}">
				<input type="hidden" name="_token" value="{{{ Session::token() }}}">
				@if($filter=='')
					<label>{{{ trans('texts.market')}}}</label>        
					<select id="pair" style="margin-right: 20px;" name="market" class="form-control">
						 <option value="" @if(isset($_GET['market']) == '') selected @endif>{{trans('texts.all')}}</option>
							@foreach($markets as $key=> $market)
								<option value="{{$market['id']}}" @if(isset($_GET['market']) && $_GET['market']==$market['id']) selected @endif>{{ strtoupper($market['wallet_from'].'/'.$market['wallet_to'])}}</option>
							@endforeach
					</select>
				@endif
				<label>{{{ trans('texts.type')}}}</label>
				<select id="type" name="type" style="margin-right: 20px;" class="form-control">
					<option value="" @if(isset($_GET['type']) == '') selected @endif>{{trans('texts.all')}}</option>
						<option value="sell" @if(isset($_GET['type']) && $_GET['type'] == 'sell') selected @endif>{{trans('texts.sell')}}</option>
						<option value="buy" @if(isset($_GET['type']) && $_GET['type'] == 'buy') selected @endif>{{trans('texts.buy')}}</option>
				</select>
				<label>{{{ trans('texts.show')}}}</label>
				<select id="view" name="status" class="form-control">
					<option value="" @if(isset($_GET['status']) == '') selected @endif>{{trans('texts.all')}}</option>
						<option value="active" @if(isset($_GET['status']) && $_GET['status'] == 'active') selected @endif>{{trans('texts.active')}}</option>
						<option value="filled" @if(isset($_GET['status']) && $_GET['status'] == 'filled') selected @endif>{{trans('texts.filled')}}</option>
						<option value="partly filled" @if(isset($_GET['status']) && $_GET['status'] == 'partly_filled') selected @endif>{{trans('texts.partially_filled')}}</option>

				</select>
				<button type="submit" class="btn btn-primary" name="do_filter">{{trans('texts.filter')}}</button>
			</form>
		   
			<table class="table table-striped" id="marketOrders">
				<tbody>
				<tr>
					<th>{{{ trans('texts.market')}}}</th>
					<th>{{{ trans('texts.type')}}}</th>
					<th>{{{ trans('texts.price')}}}</th>
					<th>{{{ trans('texts.amount')}}}</th>
					<th>{{{ trans('texts.total')}}}</th>
					<th>{{{ trans('texts.status')}}}</th>
				   <!--  <th>{{{ trans('texts.action')}}}</th> -->
				</tr>
				<?php 
				
					//$active = array('active','partially_filled'); 
					$active = array('active','partly_filled'); 
				
				?>
				@foreach($ordershistories as $ordershistory)
					<tr id="order_id_{{{$ordershistory->id}}}">
						<td>{{$markets[$ordershistory->market_id]['wallet_from'].'/'.$markets[$ordershistory->market_id]['wallet_to']}}</td>
						@if($ordershistory->type == 'sell')          
							<td><b style="color:red">{{ ucwords($ordershistory->type) }}</b></td>            
						@else          
							<td><b style="color:green">{{ ucwords($ordershistory->type) }}</b></td>
						 @endif
						<td>{{sprintf('%.8f',$ordershistory->price)}}</td>
						<td>{{sprintf('%.8f',$ordershistory->from_value)}}</td>
						<td>{{sprintf('%.8f',$ordershistory->to_value)}}</td>
						<td><?php 
							//str_replace(' ', '_', $ordershistory->status); 
							if ($ordershistory->status =='partly_filled')
								echo trans('texts.partially_filled');
							else
								echo trans('texts.'.$ordershistory->status);
							?>
						</td>
						<td>@if(in_array($ordershistory->status,$active)) 
							
							<button type="button" onclick="javascript:cancelOrder({{{$ordershistory->id}}});" class="btn btn-danger btn-xs">{{trans('texts.cancel')}}</button>
						@endif</td>
					</tr>
				@endforeach  
				</tbody>
			</table>
			<div id="pager"></div>
		</div>

		<script type="text/javascript">
		function cancelOrder(order_id){


				var title = '{{{ trans('user_texts.market_order')}}}';
				var msg ='';
				
				$.ajax({
					type: 'post',
					url: '<?php echo action('OrderController@doCancel')?>',
					datatype: 'json',
					data: {isAjax: 1, order_id: order_id },
					beforeSend: function(request) {
						return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
					},
					success:function(response) {
						var obj = $.parseJSON(response);
						//app.BrainSocket.message('doTrade',obj.message_socket);          

						
						
						if(obj.status == 'success'){
							msg = obj.message;
							$('#order_id_'+order_id).fadeOut(500);
						}else{
							msg = obj.message;
						}
					
						BootstrapDialog.show({
							title: title,
							message: msg
						});			
						console.log('Obj: ',obj);
					}, error:function(response) {
						showMessageSingle('{{{ trans('texts.error') }}}', 'error');
					}
				});
				
		}
		</script>
		{{ HTML::script('assets/js/bootstrap-paginator.js') }}
		<script type="text/javascript">
		var options = {
				currentPage: <?php echo $cur_page ?>,
				totalPages: <?php echo $total_pages ?>,
				alignment:'right',
				pageUrl: function(type, page, current){ console.log('Page: ',page);
					var url="<?php echo URL::to('user/profile/orders'); ?>";
					<?php if(!empty($filter)){ ?>
						url="<?php echo URL::to('user/profile/orders').'/'.$filter; ?>"; 
					<?php }?>
					console.log('url: ',url);
					console.log('query_string: ','<?php echo $query_string ?>');
					return url+'?pager_page='+page+'<?php echo $query_string ?>';
				}
			}
			$('#pager').bootstrapPaginator(options);
			$('#pager').find('ul').addClass('pagination');
		</script>
	</div>
</div>