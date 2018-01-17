<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
		  
		<!-- Security -->
		<div id="security">
			<h2>{{{ trans('user_texts.security')}}}</h2>
			
			@if(empty(Auth::user()->two_factor_auth))
			<?php
			/*	<h4 class="alert alert-danger">{{{ trans("user_texts.two_factor_auth")}}}: <span id="twofaStatus">{{{trans('user_texts.disabled')}}}</span></h4>
				{{ Clef::button( 'register', 'https://sweedx.com/user/profile/two-factor-auth/clef' ,Session::getToken()  , 'blue|white', 'button|flat' ) }}*/
			?>
			<h4 class="alert alert-danger">Two-Factor Authentication: <span id="twofaStatus">Disabled</span></h4>
				<script type="text/javascript" src="https://clef.io/v3/clef.js" data-type="register" class="clef-button" data-app-id="5459e328304d5260c44c4c3065103022" data-color="blue" data-style="button" data-redirect-url="https://sweedx.com/user/profile/two-factor-auth/clef" data-state="nLbXQcxSDf3dXvZ5c5Sxb2d5C6jekh5vwKouzFx6" ></script>
			
							<!-- <p>
				You can activate Two-Factor Authentication for free with <a href="https://getclef.com/" target="_blank">Clef</a>.<br />
				After creating an account at <a href="https://getclef.com/" target="_blank">Clef</a> then just activate 2fa through the button below.<br />
				</p> -->
			
			@else
				<h4 class="alert alert-success">{{{ trans("user_texts.two_factor_auth")}}}: <span id="twofaStatus">{{{trans('user_texts.enabled')}}}</span></h4>
				<button type="submit" id="disable-two-factor-auth" class="btn btn-danger">{{{ trans('user_texts.disable')}}} {{{ trans("user_texts.two_factor_auth")}}}</button>	
			@endif
				<p>
				You can activate {{{ trans("user_texts.two_factor_auth")}}} for free with <a href="https://getclef.com/" target="_blank">Clef</a>.<br />
				After creating an account at <a href="https://getclef.com/" target="_blank">Clef</a> then just activate 2fa through the button below.<br />
				</p>
				
					  @if ( Session::get('notice') )
						  
						  <br /><br />
						  <div class="alert alert-info">{{{ Session::get('notice') }}}</div>
					  @endif
					  
				
			
			
			
			
			
			@if(!empty(Auth::user()->two_factor_auth))

			<script type="text/javascript">	
				$(function(){ 
					$('#disable-two-factor-auth').click(function(e) {    	
						
						
						$.ajax({
							type: 'post',
							url: '<?php echo action('AuthController@removeTwoFactorAuth')?>',
							datatype: 'json',
							data: {isAjax: 1},
							beforeSend: function(request) {
								return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
							},
							success:function(response) {
								var obj = $.parseJSON(response);

								if(obj.status == 'error')
									showMessage(obj.messages,'error');
								else
									location.reload();


							}, error:function(response) {
								//showMessageSingle('{{{ trans('texts.error') }}}', 'error');
							}
						});
						
						

						
						
					});
				});
				</script>
			@endif
		</div>
		{{HTML::style('assets/css/flags.authy.css')}}
		{{HTML::style('assets/css/form.authy.css')}}
		{{ HTML::script('assets/js/form.authy.js') }}


		</div>
	</div>
</div>