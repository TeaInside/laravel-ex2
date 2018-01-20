@extends('layouts.nolayout')
@section('content')
<div class="row" id="page_login">
	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="fa fa-lock fa-lg"></span> {{{ Config::get('config_custom.company_name') }}} - {{trans('user_texts.login')}}</div> 
			<div class="panel-body">
			
				<hr class="colorgraph">
				
				@if ( Session::get('two_factor_authentication') )
					<div class="login" id="two_factor_box">
						{{trans('user_texts.installed_two_factor_auth') }}<br /><br />
						
						{{ trans('user_texts.login_with_two_factor') }}
						
						{{ Clef::button( 'login', 'https://sweedx.com/two-factor-auth/login2fa' ,Session::token()  , 'blue|white', 'button|flat' ) }}
					</div>						
			
			
				@else
					<form class="form-horizontal" role="form" id="registerForm" method="POST" action="{{{ Auth::check('UserController@do_login') ?: URL::to('/user/login') }}}" >
				

					<input type="hidden" name="_token" id="_token" value="{{{ Session::token() }}}">
			  
					<fieldset>
						
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
								<input type="text" class="form-control" tabindex="1" name="email" id="email" placeholder="{{{ Lang::get('confide::confide.username') }}}" value="{{{ Request::old('email') }}}" required/>
								
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
									<input type="password" class="form-control" tabindex="2" name="password" id="password" placeholder="{{{ Lang::get('confide::confide.password') }}}" required>
							</div>
						</div>
						
						<div class="checkbox right">
							<label for="remember">
								<input tabindex="3" type="checkbox" name="remember" id="remember" value="1">
							  {{ Lang::get('confide::confide.login.remember') }}
							</label>
						</div>
						
						<div class="form-group">
							<input tabindex="4" class="btn btn-lg btn-success btn-block" tabindex="4" type="submit" value="Login" >
						</div>
					
					
					</fieldset>
					</form>
				@endif
				
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
				<div class="sign_up">
					<a href="{{{ route('register') }}}">{{{ Lang::get('confide::confide.signup.desc') }}}</a>
				</div>
				<div class="forgot_password">						
					<a href="{{{ route('forgot_password') }}}">{{{ Lang::get('confide::confide.forgot.title') }}}</a>
				</div>
			
			</div>
		</div>
	</div>
</div>
	
@stop
