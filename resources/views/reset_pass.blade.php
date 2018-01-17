@extends('layouts.nolayout')
@section('content')
<div >
	<section class="main">
	
		<div class="center clearfix">
			<h5 id="logo" >
				<a href="<?=url('/', $parameters = array(), $secure = null);?>"><span></span></a>
			</h5>
		</div>
		
		<div class="nolayout_header clearfix" >
			<span >{{trans('frontend_texts.reset_pass')}}</span>
		</div>
		
		<form method="POST" action="{{{ (Confide::checkAction('UserController@do_reset_password'))    ?: URL::to('/user/reset') }}}" accept-charset="UTF-8" class="reset_pas login clearfix">
			<input type="hidden" name="token" value="{{{ $token }}}">
			<input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
			
			<table class="register">
			<tbody>
			  <tr>
				<th style="width:180px;">{{{ Lang::get('confide::confide.password') }}}</th>
				<td>
					<p class="field">
					<input class="form-control" placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">
					<i class="fa fa-lock fa-lg"></i>
					</p>
				</td>
			  </tr>
			  <tr>
				<th>{{{ Lang::get('confide::confide.password_confirmation') }}}</th>
				<td>
					<p class="field">
					<input class="form-control" placeholder="{{{ Lang::get('confide::confide.password_confirmation') }}}" type="password" name="password_confirmation" id="password_confirmation">
					<i class="fa fa-lock fa-lg"></i>
					</p>
				</td>
			  </tr>
			</tbody>
		  </table>
			@if ( Session::get('error') )
				<div class="alert alert-error alert-danger">{{{ Session::get('error') }}}</div>
			@endif

			@if ( Session::get('notice') )
				<div class="alert">{{{ Session::get('notice') }}}</div>
			@endif
			<div class="form-group ">
			  <button tabindex="3" type="submit" class="btn btn-primary">{{{ Lang::get('confide::confide.forgot.submit') }}}</button>
			</div>
		</form>
	</section>
</div>
@stop