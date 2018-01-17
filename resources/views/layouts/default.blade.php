<!DOCTYPE html>
<html>
	<head>


	<!-- Set the viewport so this responsive site displays correctly on mobile devices -->
    <meta name="viewport" content="width=device-width">
		<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
		<link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
		<title> 
			@section('title')
				{{{ Config::get('config_custom.company_name_domain') }}} - {{{ Config::get('config_custom.company_slogan') }}}
			@show
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<meta name="_token" content="{{ csrf_token() }}" />
		
		<!-- CSS are placed here -->
		
		<!-- Latest compiled and minified CSS -->
		<?php /* 
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> 
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		*/ ?>
		
		{{ HTML::style('assets/css/bootstrap.min.css') }}
		
		{{ HTML::style('assets/css/bootstrap-dialog.min.css') }}
		
		
		{{ HTML::style('assets/css/main.css') }}	
		{{ HTML::style('assets/css/style.css') }}	
		
		{{ HTML::style('assets/css/pnotify.custom.min.css') }}	


	
	<?php /* {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js') }} */?>

	
	
	<!-- Scripts are placed here -->
	<?php /* {{ HTML::script('assets/js/jquery-1.10.2.js') }} 	*/ ?>
	<?php /* {{ HTML::script('https://code.jquery.com/jquery-2.1.1.min.js') }}  */ ?>	
	<?php /* https://cdnjs.com/libraries/pnotify */ ?>
	{{ HTML::script('assets/js/jquery-2.1.1.min.js') }}
	{{ HTML::script('assets/js/bootstrap.min.js') }}
	
	{{ HTML::script('assets/js/pnotify.custom.min.js') }}
	{{ HTML::script('assets/js/bootstrap-dialog.min.js') }}
	{{ HTML::script('assets/js/prettyFloat.min.js') }}
	{{ HTML::script('assets/js/custom.js') }}
	
	
	<?php
	/*
	<script type="text/javascript">
    var queries = {{ json_encode(DB::getQueryLog()) }};
    console.log('//////////////////////////////// Database Queries /////////////////////////////////');
    console.log(' ');
    queries.forEach(function(query) {
        console.log('   ' + query.time + ' | ' + query.query + ' | ' + query.bindings[0]);
    });
    console.log(' ');
    console.log('///////////////////////////////// End Queries /////////////////////////////////');
	</script>
	*/
	?>


	</head>
<body class="@if ( Auth::guest() ) guest @else logged @endif">
	<!-- Header -->
    @include('layouts.header')
    <!-- End Header -->
    <!-- Content -->
	<div id="content">
		<div class="row-offcanvas row-offcanvas-left">
			<!-- Sidebar -->
			@include('layouts.sidebar')
			<!-- End Sidebar -->
			<div id="main">
				@yield('content')
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- Footer -->
		@include('layouts.footer')
	<!-- End Footer -->

	
 </body>
</html>