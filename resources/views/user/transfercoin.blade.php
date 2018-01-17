<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">


		<!-- Security -->
		<script type="text/javascript">
		 var RecaptchaOptions = {
			theme : 'clean'
		 };
		 </script>
		<div id="form_transfer">
			<div class="panel panel-default">
			  <div class="panel-heading">{{trans('texts.transfer_coin',array('coin'=>$current_coin))}}</div>
			  <div class="panel-body">
				<div style="color:red">@if(isset($error_message)) {{$error_message}} @endif</div>
				<!-- <div class="warning box">Once submitted, all requests <strong>MUST</strong> be confirmed via email. Please only contact support if you have not received the confirmation email.</div> -->
				Your current available {{$current_coin}} balance: <strong>{{$balance}}</strong>
				<h3>Your Deposit Address</h3>
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
					<div class="alert">{{ Session::get('notice') }}</div>
				@endif  
				@if ( Session::get('success') )
					<div class="alert alert-dismissable alert-success">
					  <button type="button" class="close" data-dismiss="alert">×</button>
					  {{Session::get('success')}}
					</div>
				@endif  
				<form id="transferForm" class="form-horizontal" method="POST" action="{{{ URL::action('UserController@doTransfer') }}}" style="display: inline-block;width: 112%;">
					<div class="control-group">
						<label class="col-lg-2 control-label">{{$current_coin}} Amount</label>
						<div class="col-lg-10">
						  <input type="text" class="form-control input-sm" id="amount" name="amount" required="">
						</div>
					</div>
					<div class="control-group">
						<label class="col-lg-2 control-label">Receive Trade Key</label>
						<div class="col-lg-10">
						  <input type="text" id="trade_key" class="form-control" name="trade_key" required="">
						</div>
					</div>
					<div class="control-group">
						<label class="col-lg-2 control-label">Confirm Password</label>
						<div class="col-lg-10">
						  <input type="password" class="form-control" id="inputPassword" name="password" required="">
						</div>
					</div>
					<div class="control-group">
						<label class="col-lg-2 control-label">Enter Captcha Code</label>
						<div class="col-lg-10">
						  <script type="text/javascript" src="https://www.google.com/recaptcha/api/challenge?k={{$recaptcha_publickey}}"></script>
						<script type="text/javascript" src="https://www.google.com/recaptcha/api/js/recaptcha.js"></script>
						<noscript>
						&lt;iframe src="https://www.google.com/recaptcha/api/noscript?k={{$recaptcha_publickey}}" height="300" width="500" frameborder="0"&gt;&lt;/iframe&gt;&lt;br/&gt;
						&lt;textarea name="recaptcha_challenge_field" rows="3" cols="40"&gt;&lt;/textarea&gt;
						&lt;input type="hidden" name="recaptcha_response_field" value="manual_challenge"/&gt;
						</noscript>
						<div id="captchaStatus"></div>
						</div>
					</div>
					<div class="control-group">	  	
						<div class="col-lg-10 col-lg-offset-2">
						  <input type="hidden" name="wallet_id" id="wallet_id" value="{{$wallet_id}}">
						  <button type="submit" class="btn btn-primary">{{trans('texts.transfer')}}</button>
						</div>
					</div>
				</form>
			  </div>
			</div>
			
			{{ HTML::script('assets/js/jquery.validate.min.js') }}
			<script type="text/javascript">
				$(document).ready(function() {    			
				/*$('#amount').keyup(function(event) {
					var amount =parseFloat( $('#amount').val());		    
					var fee = parseFloat($('#fee_withdraw').val());
					var total = amount-fee;		   
					$('#net_total').html(total.toFixed(8));
				});  */         
					$("#transferForm").validate({
						rules: {
							amount: {
							  required: true,
							  number: true
							},
							trade_key: "required",
							password: "required"
						},
						messages: {
							amount: {
								required: "Please enter amount.",
								number: "Please enter a number."
							},
							trade_key: "Please enter receive address.",
							password: {
								required: "Please provide a password.",                        
							}
						}
					});           

					$("#transferForm").submit(function(event) {
						event.preventDefault();
						var challengeField = $("input#recaptcha_challenge_field").val();
						var responseField = $("input#recaptcha_response_field").val(); 
						console.log('responseField',responseField);         
						$.post('<?php echo action('UserController@checkCaptcha')?>', {recaptcha_challenge_field: challengeField, recaptcha_response_field: responseField }, function(response){
							if(response == 1)
							{   
								document.getElementById("transferForm").submit();                  
								return true;
							}
							else
							{
								$("#captchaStatus").html("<label class='error'>Your captcha is incorrect. Please try again</label>");
								Recaptcha.reload();
								return false;
							}
						});
					});
			   }); 
				
		</script>
		</div>
	</div>
</div>