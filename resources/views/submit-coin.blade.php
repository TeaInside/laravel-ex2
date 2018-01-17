@extends('layouts.default')
@section('content')

<h2>Coin Submission Form</h2>
@if ( Session::get('notice') )
    <div class="alert">{{ Session::get('notice') }}</div>
@endif
@if ( Session::get('error') )
    <div class="alert alert-error alert-danger">
        @if ( is_array(Session::get('error')) )
            {{ head(Session::get('error')) }}
        @endif
    </div>
@endif

To get your coin listed on the exchange, it must first be submitted to the vote page.
<br><br>
Coins at the top of the Vote list will have a much greater chance to be listed 
<br><br>
Paid listings are also Available for 3 BTC. This guarantees your coin will be listed within 48hours.
<br><br>
These rules apply to both paid and free listings<BR>
1 - Must have source code on github<BR>
2 - Must have a 3 digit currency code<BR>
3 - Must not be scam, excessive premine etc!<BR>
4 - Must inform us of any updates<br><br>





<form id="contactForm" method="POST" action="{{{ URL::to('page/submit-coin') }}}" accept-charset="UTF-8">
	<input type="hidden" name="_token" id="_token" value="{{{ Session::getToken() }}}">
	<table class="table table-striped table-hover register">
		<tbody>
			@if ($email == '')
			<tr>
				<th style="width:180px;">Email Address <small>Required</small></th>
				<td><input minlength="2" type="email" required="" name="email" id="email" value="{{{ Input::old('email') }}}"></td>
			</tr>
			@endif
			<tr>
				<th style="width:180px;">Name of coin <small>Required</small></th>
				<td><input minlength="2" type="text" required="" name="coin_name" id="coin_name" value="{{{ Input::old('coin_name') }}}"></td>
			</tr>
			<tr>
				<th style="width:180px;">3 digit Ticker <small>Required</small></th>
				<td><input maxlength="3" type="text" required="" name="coin_ticker" id="coin_ticker" value="{{{ Input::old('coin_ticker') }}}"></td>
			</tr>
			<tr>
				<th style="width:180px;">Forum Thread <small>Required</small></th>
				<td><input type="text" required="" name="coin_thread" id="coin_thread" value="{{{ Input::old('coin_thread') }}}"></td>
			</tr>
			<tr>
				<th style="width:180px;">Are you the Developer?</th>
				<td>
					<input checked="checked" type="radio" name="coin_dev" id="coin_dev_yes" value="Yes">
					<label for="coin_dev_yes"><span><span></span></span>Yes</label>
					
					<input type="radio" name="coin_dev" id="coin_dev_no" value="No">
					<label for="coin_dev_no"><span><span></span></span>No</label>
					
					
				</td>
			</tr>
			<tr>
				<th>Comments</th>
				<td><textarea name="comments" id="comments" class="form-control">{{{ Input::old('comments') }}}</textarea><br>
					<span>Plain text only, any HTML codes will be striped</span>
				</td>
			</tr>
		</tbody>
	</table>
	<h3 style="text-align:center;">Verification</h3>
	<div align="center">
		<script type="text/javascript" src="https://www.google.com/recaptcha/api/challenge?k={{$recaptcha_publickey}}"></script>
		<script type="text/javascript" src="https://www.google.com/recaptcha/api/js/recaptcha.js"></script>
		<noscript>
		&lt;iframe src="https://www.google.com/recaptcha/api/noscript?k={{$recaptcha_publickey}}" height="300" width="500" frameborder="0"&gt;&lt;/iframe&gt;&lt;br/&gt;
		&lt;textarea name="recaptcha_challenge_field" rows="3" cols="40"&gt;&lt;/textarea&gt;
		&lt;input type="hidden" name="recaptcha_response_field" value="manual_challenge"/&gt;
		</noscript>
		<div id="captchaStatus"></div>
		<br>
	</div>
	<div class="text-center">
		<button type="submit" class="btn btn-primary">Submit Form</button>
	</div>
</form>

{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript">
	$(document).ready(function() {
		$("#contactForm").validate({
			rules: {
				email: {
					required: true,
					email: true
				},
				coin_name: {
					required: true,
					minlength: 2
				},
				coin_ticker: {
					required: true,
					maxlength: 3
				},
				coin_thread: {
					required: true,
					url: true
				}
			},
			messages: {
				email: "Please enter a valid email address."
			}
		});

		$("#contactForm").submit(function(event) {
			$('#contactForm').fadeOut();
			event.preventDefault();
			var challengeField = $("input#recaptcha_challenge_field").val();
			var responseField = $("input#recaptcha_response_field").val();       
			
			$.ajax({
				type: 'post',
				url: '<?php echo action('UserController@checkCaptcha')?>',
				datatype: 'json',
				data: {recaptcha_challenge_field: challengeField, recaptcha_response_field: responseField  },
				beforeSend: function(request) {
					return request.setRequestHeader('X-CSRF-Token', $("#_token").val());
				},
				success:function(response) {
					if(response == 1){   
						document.getElementById("contactForm").submit();                  
						return true;
					}else{
						$('#contactForm').fadeIn();
						$("#captchaStatus").html("<label class='error'>Your captcha is incorrect. Please try again</label>");
						Recaptcha.reload();
						return false;
					}
				}, error:function(response) {
					showMessageSingle('{{{ trans('texts.error') }}}', 'error');
				}
			});
			
			/*
			$.post('<?php echo action('UserController@checkCaptcha')?>', {recaptcha_challenge_field: challengeField, recaptcha_response_field: responseField }, function(response){
				if(response == 1)
				{   
					document.getElementById("contactForm").submit();                  
					return true;
				}
				else
				{
					$('#contactForm').fadeIn();
					$("#captchaStatus").html("<label class='error'>Your captcha is incorrect. Please try again</label>");
					Recaptcha.reload();
					return false;
				}
			});
			*/
		});
   });
</script>
@stop