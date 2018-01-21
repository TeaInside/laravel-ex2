@extends('layouts.login')
@section('content')




		<div >


		  <section class="main">
			
			<div class="center clearfix">
				<h5 id="logo" >
					<a href="<?=url('/', $parameters = array(), $secure = null);?>"><span></span></a>
				</h5>
			</div>
			
			<div class="nolayout_header clearfix" >
				<span >{{trans('user_texts.title_login')}}</span>
			</div>
			
				<form id="registerForm" class="login clearfix" method="POST" action="{{{ Auth::check('UserController@do_login') ?: URL::to('/user/login') }}}" >
				<div class="form-group">
				  <input type="hidden" name="_token" value="{{{ Session::token() }}}">
						<p class="field">
							<input type="text" tabindex="1" name="email" id="email" placeholder="{{{ Lang::get('confide::confide.username') }}}" value="{{{ Request::old('email') }}}" />
							<i class="icon-user icon-large"></i>
						</p>
						<p class="field">
								<input type="password" tabindex="2" name="password" id="password" placeholder="{{{ Lang::get('confide::confide.password') }}}">
								<i class="icon-lock icon-large"></i>
						</p>
						<p class="submit">
							<button type="button" tabindex="3" onclick="_tryLogin()" value="{{{ Lang::get('confide::confide.login.submit') }}}"><i class="icon-arrow-right icon-large"></i></button>
							<input type="submit" style="display: none;" />
						</p>
						
						<p class="field">
						  <br />
						  <div class="right">
							  <input tabindex="4" type="checkbox" name="remember" id="remember" value="1">
							  <label for="remember"><span><span></span></span>{{{ Lang::get('confide::confide.login.remember') }}}</label>
						  </div>
						</p>  

						
						
							
						
					</div>  
				</form>
				<div class="signup_forgot_field">
					<div class="sign_up">
						<a href="{{{ Auth::check('UserController@register') }}}">{{{ Lang::get('confide::confide.signup.desc') }}}</a>
					</div>
					<div class="forgot_password">						
						<a href="{{{ (Auth::check('UserController@forgot_password')) ?: 'forgot' }}}">{{{ Lang::get('confide::confide.login.forgot_password') }}}</a>
					</div>
				</div>
			</section>
			
			
			



			  @if ( Session::get('error') )
				  <div class="alert alert-error">{{{ Session::get('error') }}}</div>
			  @endif

			  @if ( Session::get('notice') )
				  <div class="alert">{{{ Session::get('notice') }}}</div>
			  @endif

			<form id="login_verify_1" onsubmit="return _tryVerify()" action="{{{ Auth::check('AuthController@ajVerifyToken') ?: URL::to('/user/verify_token') }}}" method="post" style="margin-bottom:4px;display:none;">
					<input type="text" id="token" name="token" placeholder="{{trans('user_texts.token')}}">  
					<input type="hidden" id="authy_id" name="authy_id">  
					<button type="submit" class="btn btn-primary" id="do_verify" onclick="_tryVerify()">{{trans('user_texts.verify')}}</button>
			</form>
		</div>

<script type="text/javascript"> 
  $('#registerForm').keypress(function(e) {
      if (e.keyCode == '13') {
          _tryLogin();
      }
  }); 
  
  function checkEnter(e) {
    var characterCode;
    if (e && e.which) {
        e = e;
        characterCode = e.which;
    } else {
        e = event;
        characterCode = e.keyCode;
    } if (characterCode == 13) {
        push_talk();
        return false;
    } else {
        return true;
    }
}


    function _tryVerify(){
      var token = $('#login_verify_1').find('#token').val();   
      var authy_id = $('#login_verify_1 #authy_id').val();       
      $.post('<?php echo action('AuthController@ajVerifyToken')?>', {isAjax: 1, token: token,authy_id:authy_id }, function(response){
          var obj = $.parseJSON(response);
          console.log('ajVerifyToken: ',obj);
          if(obj.status == 'success'){
				var title = '{{{ Lang::get('confide::confide.login.submit') }}}';
				var msg = 'Logging in... please wait';
				
				BootstrapDialog.show({
					title: title,
					message: msg
				});
				
            $("#registerForm").submit();
          }else {
		  
            var title = '{{{ Lang::get('confide::confide.login.submit') }}}';
			var msg = obj.message;
			//alert(obj.message);
			
			BootstrapDialog.show({
				title: title,
				message: msg
			});
			
			
          }
      });
      return false;
    }
    function _tryLogin(){
        var email = $('#registerForm #email').val();
        var password = $('#registerForm #password').val();            
        $.post('<?php echo action('UserController@firstAuth')?>', {isAjax: 1, email: email, password: password }, function(response){
            console.log('before Obj: ',obj);
            var obj = $.parseJSON(response);
            console.log('Obj: ',obj);
            if(obj.status == 'one_login_success'){                  
              var title = '{{{ Lang::get('confide::confide.login.submit') }}}';
				var msg = 'Logging in... please wait';
				
				BootstrapDialog.show({
					title: title,
					message: msg
				});
				
			  $("#registerForm").submit();
              //return true;              
            }else if(obj.status == 'two_login'){
				$('#registerForm').hide();
				$('#login_verify_1').show();
				$('#login_verify_1 #authy_id').val(obj.authy_id);
            }else {
				var title = '{{{ Lang::get('confide::confide.login.submit') }}}';
				var msg = obj.message;
				//alert(obj.message);
				
				BootstrapDialog.show({
					title: title,
					message: msg
				});
				
				
            }
        });
        return false;
    }
</script>
@stop
