<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
		<!-- Edit Profile -->
		<div id="edit_profile">
			<h2>Account Profile</h2> 
			@if ( Session::get('error') )
				<div class="alert alert-error alert-danger">
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
			<form id="registerForm" method="POST" action="{{{ Auth::check('UserController@updateSetting') ?: URL::to('/user/update-setting') }}}">    
			<input type="hidden" name="_token" value="{{{ Session::token() }}}">
			<h3>Your Details</h3>
			<table class="table table-striped register">
				<tbody>
					<tr>
						<th style="width:180px;">{{trans('user_texts.fullname')}}</th>
						<td><input type="text" value="{{$profile['fullname']}}" id="fullname" name="fullname" required="" minlength="2" class="valid form-control"></td>
					</tr>
					<tr>
						<th>USERNAME</th>
						<td><input type="text" value="{{$profile['username']}}" disabled="disabled" id="username" name="username" class="valid form-control"></td>
					</tr>
					<tr>
						<th>{{{ Lang::get('confide::confide.e_mail') }}}</th>

						<td>
							<input type="text" value="{{$profile['email']}}" disabled="disabled" id="email" name="email" class="valid form-control">
							<span class="right">Please contact Support if you wish to change your email address.</span>
						</td>
						<script type="text/javascript">
							/* &lt;![CDATA[ */
							(function(){try{var s,a,i,j,r,c,l,b=document.getElementsByTagName("script");l=b[b.length-1].previousSibling;a=l.getAttribute('data-cfemail');if(a){s='';r=parseInt(a.substr(0,2),16);for(j=2;a.length-j;j+=2){c=parseInt(a.substr(j,2),16)^r;s+=String.fromCharCode(c);}s=document.createTextNode(s);l.parentNode.replaceChild(s,l);}}catch(e){}})();
							/* ]]&gt; */
						</script>
						
					</tr>
					<tr>
						<th>Auto-Logout</th>
						<td>
							<select name="timeout" class="valid form-control">
								<option value="45 minutes" @if(trim($profile['timeout'])=='45 minutes') selected @endif>Default (45 Minutes)</option>
								<option value="1 hour" @if(trim($profile['timeout'])=='1 hour') selected @endif>1 Hour</option>
								<option value="2 hours" @if(trim($profile['timeout'])=='2 hours') selected @endif>2 Hours</option>
								<option value="6 hours" @if(trim($profile['timeout'])=='6 hours') selected @endif>6 Hours</option>
								<option value="12 hours" @if(trim($profile['timeout'])=='12 hours') selected @endif>12 Hours</option>
								<option value="24 hours" @if(trim($profile['timeout'])=='24 hours') selected @endif>24 Hours</option>
							</select>
							<span class="right">You can change the inactivity auto-logout time above.</span>
						</td>
					</tr>
				</tbody>
			</table>
			<h3>Your Password</h3>
			You can change your password using the form below. Only enter a new password if you wish to change it.<br><br>
			<table class="table table-striped register">
				<tbody>
					<tr>
						<th style="width:180px;">{{{ Lang::get('confide::confide.password') }}}</th>
						<td>
							<input type="password" autocomplete="off" value="" id="password" name="password" class="form-control">
							<span class="right">Please enter 8 characters minimum, including 1 or more digits.</span>
						</td>
					</tr>
					<tr>
						<th>{{{ Lang::get('confide::confide.password_confirmation') }}}</th>
						<td>
							<input type="password" autocomplete="off" value="" id="password2" name="password2" class="form-control">
							<span class="right">Please type your password again.</span>
						</td>
					</tr>
				</tbody>
			</table> 
			<!-- <h3>Notification Options</h3>
			You can customize the emails that you recieve from MintPal below. Some security related emails are mandatory.<br><br>
			<table class="table register">
				<tbody>
					<tr>
						<th style="width:180px;">Invalid Login</th>
						<td><input type="checkbox" disabled="" checked="" id="login" value="login" name="notification[]">&nbsp; I wish to receive this email</td>
					</tr>
				 
					<tr>
						<th>Withdraw Confirmation</th>
						<td><input type="checkbox" disabled="" checked="" id="withdrawConfirm" value="withdrawConfirm" name="notification[]">&nbsp; I wish to receive this email</td>
					</tr>
				</tbody>
			</table> -->
			<br><br>
		   <!--  <input type="submit" value="Update Profile"> -->
		   <button type="submit" class="btn btn-primary">Update Profile</button>
			<br><br>
			</form>
			{{ HTML::script('assets/js/jquery.validate.min.js') }}
			<script type="text/javascript">
				$(document).ready(function() {           
					$("#registerForm").validate({
						rules: {
							fullname: "required",
							password: {
								required: false,
								minlength: 8
							},
							password2: {
								required: false,
								minlength: 8,
								equalTo: "#password"
							}
						},
						messages: {
							fullname: "Please enter your full name.",
							password: {
								required: "Please provide a password.",
								minlength: "Your password must be at least 8 characters long."
							},
							confirm_password: {
								required: "Please provide a password.",
								minlength: "Your password must be at least 8 characters long.",
								equalTo: "Please enter the same password as above."
							}
						}
					 });

					/*$("#registerForm").submit(function(event) {
					  event.preventDefault();
					  if($(this).valid()) {
						
					  }
				  });*/
			   });
			</script>
		</div>
	</div>
</div>