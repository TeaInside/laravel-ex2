<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
			
		<!-- Security -->
		<div id="coin_deposits">
			<h2>{{{ trans('texts.coin_withdrawals')}}} @if(isset($current_coin)) {{' - '.$current_coin}} @endif</h2> 
			@if ( Session::get('error') )
				<div class="alert alert-error alert-danger">
					<button type="button" class="close" data-dismiss="alert">×</button>
					@if ( is_array(Session::get('error')) )
						{{ head(Session::get('error')) }}
					@else
						{{ Session::get('error') }}
					@endif
				</div>
			@endif

			@if ( Session::get('notice') )
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">×</button>
					{{ Session::get('notice') }}
				</div>
			@endif     
			Below is a list of withdrawals that you have made.
			<br><br>
			<span class="text-high">To make a new withdrawal, please visit the {{ HTML::link('user/profile/balances', trans('user_texts.balance')) }} page and select the Withdraw option under the actions menu for the coin.</span>
			<br><br>
			<form class="form-inline" method="POST" action="{{Request::url()}}">
				<input type="hidden" name="_token" id="_token" value="{{{ Session::token() }}}">
				@if($filter=='')
					<label>{{{ trans('texts.coin')}}}</label>        
					<select id="pair" style="margin-right: 20px;" name="wallet" class="form-control">
						<option value="" selected="selected">{{trans('texts.all')}}</option>
						@foreach($wallets as $key=> $wallet)
							<option value="{{$wallet['id']}}">{{$wallet->type}}</option>
						@endforeach
					</select>
				@endif
				<label>{{{ trans('texts.type')}}}</label>
				<select id="type" name="status" style="margin-right: 20px;" class="form-control">
					<option value="" selected="selected">{{trans('texts.all')}}</option>
					<option value="0">{{trans('texts.pending')}}</option>
					<option value="1">{{trans('texts.complete')}}</option>
				</select>        
				<button type="submit" class="btn btn-primary" name="do_filter">{{trans('texts.filter')}}</button>
			</form>
			<table class="table table-striped">
				<tbody>
					<tr>
						<th>{{{ trans('texts.date')}}}</th>
						<th>{{{ trans('texts.coin')}}}</th>
						<th>{{{ trans('texts.amount')}}}</th>
						<th>{{{ trans('texts.fee')}}}</th>
						<th>{{{ trans('texts.receiving_address')}}}</th>
						<!-- <th>{{{ trans('texts.confirmations')}}}</th> -->
						<th>{{{ trans('texts.status')}}}</th>
						<th>{{{ trans('texts.action')}}}</th>	            
					</tr>
					<?php
					//var_dump($withdrawals);
					?>
					
					@foreach($withdrawals as $withdrawal)
						<tr id="withdraw_{{{$withdrawal->id}}}">
							<td>{{$withdrawal->created_at}}</td>
							<td>{{$withdrawal->type}}</td>
							<td>{{sprintf('%.8f',$withdrawal->amount)}}</td>
							<td>{{sprintf('%.8f',$withdrawal->fee_amount)}}</td>
							<td>{{$withdrawal->to_address}}</td>
							<!-- <td>{{$withdrawal->confirmations}}</td> -->
							@if($withdrawal->status)          
								<td><b style="color:green">{{ ucwords(trans('texts.complete')) }}</b></td>  
							@else  
								<td><b style="color:red">{{ ucwords(trans('texts.pending')) }}</b></td> 
							@endif	 
							<td>@if(!$withdrawal->status)<a href="javascript:cancelWithdraw({{{$withdrawal->id}}});">{{trans('texts.cancel')}}</a>@endif</td>       		
						</tr>
					@endforeach
				</tbody>
			</table>

			<script type="text/javascript">
		  function cancelWithdraw(withdraw_id){
				
				$.ajax({
					type: 'post',
					url: '<?php echo action('UserController@cancelWithdraw')?>',
					datatype: 'json',
					data: {isAjax: 1, withdraw_id: withdraw_id },
					beforeSend: function(request) {
						return request.setRequestHeader('X-CSRF-Token', $("#_token").val());
					},
					success:function(response) {
						var obj = $.parseJSON(response); 
						var title, msg;
						
						title= '{{{ trans('texts.coin_withdrawals')}}} {{trans('texts.cancel')}}';
						if(obj.status == 'success'){               
							msg = '<p style="color:#008B5D; font-weight:bold;text-align:center;">'+obj.message+'</p>';
							$('#withdraw_'+withdraw_id).remove();
						}else{
							msg = '<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>';
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
				
				/*
				$.post('<?php echo action('UserController@cancelWithdraw')?>', {isAjax: 1, withdraw_id: withdraw_id }, function(response){
					  var obj = $.parseJSON(response); 
					  if(obj.status == 'success'){               
						$('#messageModal .modal-body').html('<p style="color:#008B5D; font-weight:bold;text-align:center;">'+obj.message+'</p>');            
						$('#messageModal').on('hidden.bs.modal', function (e) {              
						  location.reload();
						});
					  }else{
						$('#messageModal .modal-body').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
					  }              
					  $('#messageModal').modal({show:true});  
					  console.log('Obj: ',obj);
					});
				*/
			  }
		  </script>
		</div>
	</div>
</div>