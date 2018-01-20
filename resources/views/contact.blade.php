@extends('layouts.login')
@section('content')


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



	<div class="contact_us">
		<section class="main">
			<div class="center clearfix">
				<h5 id="logo" >
					<a href="<?=url('/', $parameters = array(), $secure = null);?>"><span></span></a>
				</h5>
			</div>
			
			<div class="nolayout_header clearfix" >
				<span >{{ trans('user_texts.support') }}</span>
			</div>
			
			<form id="contactForm" class="login clearfix" method="POST" action="{{{ URL::to('page/contact') }}}" accept-charset="UTF-8">
				<input type="hidden" name="_token" id="_token" value="{{{ Session::token() }}}">
				<div class="form-group">

						<p class="field">
							<input type="text" tabindex="1" name="email" id="email" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" value="{{{ Input::old('email') }}}" />
							<i class="icon-envelope icon-large"></i>
						</p>
						<p class="field">
							<textarea tabindex="2" name="message" id="message" placeholder="Message" value="{{{ Input::old('message') }}}" ></textarea>
							<i class="icon-envelope icon-large"></i>
						</p>
						
						<h3 style="text-align:center;">Verification</h3>
						<div align="center">
							<script type="text/javascript" src="https://www.google.com/recaptcha/api/challenge?k={{$recaptcha_publickey}}"></script>
							<script type="text/javascript" src="https://www.google.com/recaptcha/api/js/recaptcha.js"></script>
							<noscript>
							<iframe src="https://www.google.com/recaptcha/api/noscript?k={{$recaptcha_publickey}}" height="300" width="500" frameborder="0"></iframe><br/> <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea> <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/> 
							</noscript>
							<div id="captchaStatus"></div>
							<br>
						</div>
						
						<button type="submit" tabindex="2" value="Submit" class="forgot_password_button"><i class="icon-arrow-right icon-large"></i></button>

						
				</div>

			</form>



		</section>
	</div>









{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript">
	$(document).ready(function() {
		$("#contactForm").validate({
			rules: {
				email: {
					required: true,
					email: true
				},
				message: {
					required: true,
					minlength: 2
				}
			},
			messages: {
				email: "Please enter a valid email address.",
				message: "Please enter a message"
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
				data: {recaptcha_challenge_field: challengeField, recaptcha_response_field: responseField },
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