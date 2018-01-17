<div id="header" class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="mini-submenu">
			<button aria-expanded="false" class="navbar-toggle collapsed mini-submenu-toggle" data-toggle="offcanvas" type="button" >
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
		</div>
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo url('/', $parameters = array(), $secure = null);?>">
			<img src="<?php echo url('/', $parameters = array(), $secure = null).'/assets/images/logo_favicon.png'?>">{{{ Config::get('config_custom.company_name') }}}
			</a>
			<button data-target="#navbar-main" data-toggle="collapse" type="button" class="navbar-toggle collapsed" aria-expanded="false">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
		</div>
		
		<div class="navbar-collapse collapse" id="navbar-main">
			<ul class="nav navbar-nav">
				<li @if(Request::is('page/voting')) {{'class="active"'}} @endif>{{ HTML::link('page/voting', trans('user_texts.voting'), array('class' => Request::is('page/voting')?'active':'')) }}</li>
				<li @if(Request::is('page/fees')) {{'class="active"'}} @endif>{{ HTML::link('page/fees', trans('user_texts.fees'), array('class' => Request::is('page/fees')?'active':'')) }}</li>
				@if(isset($menu_pages))
				@foreach($menu_pages as $menu_page)
				<li @if(Request::is('post/'.$menu_page->permalink)) {{'class="active"'}} @endif>{{ HTML::link('post/'.$menu_page->permalink, $menu_page->title, array('class' => Request::is('post/'.$menu_page->permalink)?'active':'')) }}</li>
				@endforeach
				@endif
				<li @if(Request::is('page/api')) {{'class="active"'}} @endif>{{ HTML::link('page/api', trans('user_texts.api'), array('class' => Request::is('page/api')?'active':'')) }}</li>
			</ul>
			<!--Lang menu  start-->
			<?php
				/*
				   @if(!empty($loc))            
				    	<li>
				            <a href="#" class="dropdown-toggle text-small" data-toggle="dropdown">{{trans('frontend_texts.select_language')}} <span class="caret"></span></a>                
				            <ul class="dropdown-menu" data-role="dropdown">
				      	      @foreach($loc as $locale)
				                   <li @if(Session::get( 'locale' )==$locale) {{'class="active"'}} @endif>{{ HTML::link('locale/'.$locale, trans('frontend_texts.'.$locale),array('class' => 'text-small')) }}</li>
				             @endforeach
				          </ul>
				       </li>
				   @endif
				
				
				http://laravel-vsjr.blogspot.se/2013/08/managing-laravel-4-localization-language.html
				@if(!empty($loc))            
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Lang::get( 'frontend_texts.select_language' ) }} <b class="caret"></b></a>
						<ul class="dropdown-menu">
							@foreach( $loc as $mKey => $mLanguage )
								<li>{{ HTML::linkAction( 'BaseController@setLocale', $mLanguage, $mKey ) }}</li>
							@endforeach
						</ul>
					</li>
				@endif
				*/
				?>
			<!--Lang menu  stop-->
			<ul class="nav navbar-nav navbar-right">
				<li>
					<!-- This selector markup is completely customizable -->
					<div class="columnSelectorWrapper">
						<input id="colSelect1" type="checkbox" class="hidden">
						<div id="columnMarketSelector" class="columnMarketSelector">
							<!-- this div is where the column selector is added -->
						</div>
					</div>
					<!-- Bootstrap popover button -->
					<button type="button" id="popoverMarketSelector" class="btn btn-default navbar-btn hide">Show/Hide Columns</button>
					<div class="hidden">
						<div id="popoverMarketSelectorTarget"></div>
					</div>
				</li>
				<li>
					<form class="navbar-form" role="search">
						<div class="input-group">
							<input id="btc_market_search" type="search" data-column="1" placeholder="{{ trans('texts.search_market')}}" class="market_search form-control" />
							<span class="input-group-addon"><i class="fa fa-search fa-lg"></i></span>
						</div>
					</form>
				</li>
				@if ( Auth::guest() )
				<li @if(Request::is('register')) {{'class="active"'}} @endif><a href="<?=url('/', $parameters = array(), $secure = null);?>/user/register" class="navitem login {{Request::is('register')?'active':''}}">{{trans('user_texts.register')}}</a></li>
				<li @if(Request::is('login')) {{'class="active"'}} @endif><a href="<?=url('/', $parameters = array(), $secure = null);?>/login" class="navitem login {{Request::is('login')?'active':''}}">{{trans('user_texts.login')}}</a></li>
				@else
				<li class="">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{trans('user_texts.hello')}} {{Confide::user()->username}} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li @if(Request::is('user/profile/balances')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/balances', trans('user_texts.wallets')) }}</li>
						<li @if(Request::is('user/profile/dashboard')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/dashboard', trans('user_texts.dashboard')) }}</li>
						<li @if(Request::is('user/profile')) {{'class="active"'}} @endif>{{ HTML::link('user/profile', trans('user_texts.profile')) }}</li>
						<li @if(Request::is('user/profile/two-factor-auth')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/two-factor-auth', trans('user_texts.security')) }}</li>
						<li @if(Request::is('user/profile/orders')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/orders', trans('user_texts.orders')) }}</li>
						<li @if(Request::is('user/profile/trade-history')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/trade-history', trans('user_texts.trade_history')) }}</li>
						<li>{{ HTML::link('user/logout', trans('user_texts.logout')) }}</li>
					</ul>
				</li>
				@endif
			</ul>
		</div>
	</div>
</div>
<?php
	/*
	<div id="header" class="navbar navbar-default">
	  <div class="navbar-header">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	    </button>
	    <h1 id="logo" class="left"><a class="navbar-brand" href="<?=url('/', $parameters = array(), $secure = null);?>"><span></span></a></h1>
</div>
<!--  <h1 id="logo" class="left"><a href="<?=url('/', $parameters = array(), $secure = null);?>/"><span></span> </a></h1>  -->
<div class="navbar-collapse collapse navbar-responsive-collapse">
	<ul class="nav navbar-nav">
		<li @if(Request::is('page/voting')) {{'class="active"'}} @endif>{{ HTML::link('page/voting', trans('user_texts.voting'), array('class' => Request::is('page/voting')?'active':'')) }}</li>
		<li @if(Request::is('page/fees')) {{'class="active"'}} @endif>{{ HTML::link('page/fees', trans('user_texts.fees'), array('class' => Request::is('page/fees')?'active':'')) }}</li>
		@if(isset($menu_pages))
		@foreach($menu_pages as $menu_page)
		<li @if(Request::is('post/'.$menu_page->permalink)) {{'class="active"'}} @endif>{{ HTML::link('post/'.$menu_page->permalink, $menu_page->title, array('class' => Request::is('post/'.$menu_page->permalink)?'active':'')) }}</li>
		@endforeach
		@endif
		<li @if(Request::is('page/api')) {{'class="active"'}} @endif>{{ HTML::link('page/api', trans('user_texts.api'), array('class' => Request::is('page/api')?'active':'')) }}</li>
		<?php
			/*
			
			    </ul>
			    <ul class="nav navbar-nav navbar-right">
			      @if ( Auth::guest() )
			        <li @if(Request::is('register')) {{'class="active"'}} @endif><a href="<?=url('/', $parameters = array(), $secure = null);?>/user/register" class="navitem login {{Request::is('register')?'active':''}}">{{trans('user_texts.register')}}</a></li>
		<li @if(Request::is('login')) {{'class="active"'}} @endif><a href="<?=url('/', $parameters = array(), $secure = null);?>/login" class="navitem login {{Request::is('login')?'active':''}}">{{trans('user_texts.login')}}</a></li>
		@else
		<li class="">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{trans('user_texts.hello')}} {{Confide::user()->username}} <b class="caret"></b></a>
			<ul class="dropdown-menu">
				<li @if(Request::is('user/profile/balances')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/balances', trans('user_texts.wallets')) }}</li>
				<li @if(Request::is('user/profile/dashboard')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/dashboard', trans('user_texts.dashboard')) }}</li>
				<li @if(Request::is('user/profile')) {{'class="active"'}} @endif>{{ HTML::link('user/profile', trans('user_texts.profile')) }}</li>
				<li @if(Request::is('user/profile/two-factor-auth')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/two-factor-auth', trans('user_texts.security')) }}</li>
				<li @if(Request::is('user/profile/orders')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/orders', trans('user_texts.orders')) }}</li>
				<li @if(Request::is('user/profile/trade-history')) {{'class="active"'}} @endif>{{ HTML::link('user/profile/trade-history', trans('user_texts.trade_history')) }}</li>
				<li>{{ HTML::link('user/logout', trans('user_texts.logout')) }}</li>
			</ul>
		</li>
		@endif
	</ul>
</div>
<div class="clear"></div>
</div>
*/
?>
