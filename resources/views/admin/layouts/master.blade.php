<!DOCTYPE html>
<html>
<head>
    <title>
        @section('title')
        {{{ Config::get('config_custom.company_name') }}} @show
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<meta name="_token" content="{{ csrf_token() }}" />
	
    <!-- CSS are placed here -->
    {{HTML::style('assets/css/bootstrap.css')}}
    {{HTML::style('assets/css/bootstrap-responsive.css')}}  
    {{HTML::style('assets/superfish/css/superfish.css')}}
    {{HTML::style('assets/css/base.css')}}  
    {{HTML::style('assets/css/admin/style.css')}}
    {{HTML::style('assets/css/bootstrap-dialog.min.css')}}
    <!-- Scripts are placed here -->

	<?php
	/* {{ HTML::script('https://code.jquery.com/jquery-2.1.1.min.js') }} */
	?>
    {{ HTML::script('assets/js/jquery-1.10.2.min.js') }} 
    {{ HTML::script('assets/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/bootstrap-dialog.min.js') }}
    {{ HTML::script('assets/superfish/js/superfish.js') }}
</head>

<body>

<!-- Container -->

    <!-- Header -->
    @include('admin.layouts.header')
    <!-- End Header -->
    <!-- Content -->
    <!-- Main Content -->
    <div id="container" class="clear">
        <!-- Main content -->
        <div class="main-contain">
            @yield('content')
        </div>
        <!-- Sidebar right -->
        <?php if(isset($issidebar) && $issidebar){ ?>
        <div class="sidebarright">
            @yield('sidebarright')
        </div>
        <?php } ?>
    </div>
    <!-- Footer -->
    @include('admin.layouts.footer')
</body>
</html>
