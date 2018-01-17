<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<!-- Withdraw -->
		<div id="form_deposit">
			<div style="color:red">@if(isset($error_message)) {{$error_message}} @endif</div>
			<h2>{{{ trans('texts.withdraw')}}} - {{$current_coin}}</h2> 
			<!-- <div class="warning box">Once submitted, all requests <strong>MUST</strong> be confirmed via email. Please only contact support if you have not received the confirmation email.</div> -->
			Your current available {{$current_coin}} balance: <strong>{{$balance}}</strong>
			<h3>Your Withdraw Address</h3>
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
			@if($wallet->enable_withdraw)
				<form id="wihtdrawForm" class="form-horizontal" method="POST" action="{{{ Confide::checkAction('UserController@doWithdraw') ?: URL::to('/user/withdraw') }}}">
					<input type="hidden" name="_token" id="_token" value="{{{ Session::getToken() }}}">
					<div class="control-group">
						<label class="col-lg-2 control-label">{{$current_coin}} Amount</label>
						<div class="col-lg-10">
						  <input type="text" class="form-control input-sm" id="amount" name="amount" required="">
						</div>
					</div>
					<div class="control-group">
					  <label class="col-lg-2 control-label">Withdraw Fee</label>
					  <div class="col-lg-10">
						<b id="withdraw_fee" class="control-label">{{$fee_withdraw}}</b> <b>{{{ $current_coin }}}</b>
					  </div>
					</div>         
					<div class="control-group">
					  <label class="col-lg-2 control-label">{{{ trans('texts.net_total')}}}</label>
					  <div class="col-lg-10">
					   <b id="net_total" class="control-label">0.00</b> <b>{{{ $current_coin }}}</b><br /><br />
					  </div>
					</div>
				   
					
				  <div class="control-group">
					<label class="col-lg-2 control-label col-lg-pull-0">Receive Address</label>
					<div class="col-lg-10">
					  <input type="text" id="address" class="form-control" name="address" required="">
					</div>
				  </div>
				 
				  <div class="control-group">
					<label class="col-lg-2 control-label col-lg-pull-0">Confirm Password</label>
					<div class="col-lg-10">
					  <input type="password" class="form-control" id="inputPassword" name="password" required="">
					</div>
				  </div>
				  <div class="control-group col-lg-10 margintop20">
					<strong>Ensure all details are correct before submitting.</strong><font color=red> External addresses only. Do not send to another {{{ Config::get('config_custom.company_name') }}} address.</font> Every request must be confirmed by email before it will be processed, most withdrawals are processed within 10 minutes of email confirmation. For security reasons, larger withdrawals can take longer as they may require manual verification. 
					<div class="col-lg-10">	 
					  <input type="hidden" name="fee_withdraw" id="fee_withdraw" value="{{$fee_withdraw}}"> 	      
					  <input type="hidden" name="wallet_id" id="wallet_id" value="{{$wallet_id}}">
					  <button type="submit" class="btn btn-primary">{{trans('texts.withdraw')}}</button>
					</div>
				  </div>
				</form>
				{{ HTML::script('assets/js/jquery.validate.min.js') }}
				<script type="text/javascript">	
					$(document).ready(function() { 
					$('#amount').keyup(function(event) {
						var amount =parseFloat( $('#amount').val());		    
						var fee = parseFloat($('#fee_withdraw').val());
						var total = amount-fee;		   
						$('#net_total').html(total.toFixed(8));
					});           
						$("#wihtdrawForm").validate({
							rules: {
								amount: {
								  required: true,
								  number: true
								}                   
							},
							messages: {
								amount: {
									required: "Please enter amount.",
									number: "Please enter a number."
								},
								address: "Please enter receive address.",
								password: {
									required: "Please provide a password.",                        
								}
							}
						 });           
				   });
				</script>
			@else
				<div class="alert alert-error alert-danger">
					{{Lang::get('texts.notify_withdraw_disable',array('coin'=>$wallet->name))}}
				</div>
			@endif
		</div>
	</div>
</div>
