@extends('layouts.nolayout')
@section('content')


<div class="row" id="page_forgotpass">
	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-lock fa-lg"></span> {{{ Config::get('config_custom.company_name') }}} - {{{ Lang::get('confide::confide.forgot.title') }}}</div> 
			<div class="panel-body">
			
				<hr class="colorgraph">
				
					<form class="form-horizontal" role="form" id="forgotForm" method="POST" class="login clearfix" action="{{ (Auth::check('UserController@do_forgot_password')) ?: URL::to('/user/forgot') }}" accept-charset="UTF-8">
				

					<input type="hidden" name="_token" id="_token" value="{{{ Session::token() }}}">
			  
					<fieldset>
						

						<div class="form-group">
							<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
									<input type="text" class="form-control" tabindex="1" name="email" id="email" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" value="{{{ Request::old('email') }}}" required>
							</div>
						</div>
						
						
						<div class="form-group">
							
							<button tabindex="2" class="btn btn-lg btn-success btn-block" type="button" tabindex="2" onclick="_tryForgotPassword()" class="forgot_password_button">{{{ Lang::get('confide::confide.forgot.submit') }}}</button>
						</div>
					
					
					</fieldset>
					</form>
				
				
				<div>
				


					@if ( Session::get('error') )
						<div class="alert alert-error alert-danger">{{{ Session::get('error') }}}</div>
					@endif

					@if ( Session::get('notice') )
						<div class="alert alert-info">{{{ Session::get('notice') }}}</div>
					@endif
					
					
				</div>
				
				
			</div>
			<div class="panel-footer">

			
			</div>
		</div>
	</div>
</div>



{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript"> 

  
$(document).ready(function() {
		
    function _tryForgotPassword(){
		
		
	
        var email = $('#forgotForm #email').val();
        
		$.ajax({
			type: 'post',
			url: '<?php echo action('UserController@forgot_password')?>',
			datatype: 'json',
			data: {isAjax: 1, email: email },
			beforeSend: function(request) {
				return request.setRequestHeader('X-CSRF-Token', $("#_token").val());
			},
			success:function(response) {
              var title = '{{{ Lang::get('confide::confide.forgot.title') }}}';
				var msg = response;
				
				$('#email').val('');
				BootstrapDialog.show({
					title: title,
					message: msg
				});
			}, error:function(response) {
				showMessageSingle('{{{ trans('texts.error') }}}', response);
			}
		});
	
		/*
		$.post('<?php echo action('UserController@forgot_password')?>', {isAjax: 1, email: email}, function(response){

              var title = '{{{ Lang::get('confide::confide.login.submit') }}}';
				var msg = response;
				
				BootstrapDialog.show({
					title: title,
					message: msg
				});

        });
		*/
        return false;
    }
	
	$('#email').keypress(function(e) {
      if (e.keyCode == '13') {
          _tryForgotPassword();
      }
  }); 
  


</script>

<!-- End Reset password -->
@stop
