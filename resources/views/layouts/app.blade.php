<!DOCTYPE html>

<html lang="en" class="light-style">

<head>
    <title>@yield('title')</title>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="{{ asset('/admin/css/bootstrap.css') }}" class="theme-settings-bootstrap-css">
    <link rel="stylesheet" href="{{ asset('/admin/css/styles.css') }}" class="theme-settings-appwork-css">
    <link rel="stylesheet" href="{{ asset('/admin/css/theme-corporate.css') }}" class="theme-settings-theme-css">
    <link rel="stylesheet" href="{{ asset('/admin/css/colors.css') }}" class="theme-settings-colors-css">
    <link rel="stylesheet" href="{{ asset('/admin/css/uikit.css') }}">

    <!-- Load polyfills -->
    <script src="{{ asset('/admin/js/polyfills.js') }}"></script>
    <script>document['documentMode']===10&&document.write('<script src="../../../../../polyfill.io/v3/polyfill.min8a7a.js?features=Intl.~locale.en"><\/script>')</script>

    <script src="{{ asset('/admin/js/material-ripple.js') }}"></script>
    <script src="{{ asset('/admin/js/layout-helpers.js') }}"></script>

    <!-- Core scripts -->
    <script src="{{ asset('/admin/js/pace.js') }}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Libs -->
    <link rel="stylesheet" href="{{ asset('/admin/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('/admin/css/authentication.css') }}">
</head>

<body>
<div class="page-loader">
    <div class="bg-primary"></div>
</div>

<!-- Content -->

<div class="authentication-wrapper authentication-2 ui-bg-cover ui-bg-overlay-container px-4" style="background-image: url('/public/admin/img/bg.jpg');">
    <div class="ui-bg-overlay bg-dark opacity-25"></div>

    <div class="authentication-inner py-5">

        @yield('content')

    </div>
</div>

<!-- / Content -->

<!-- Core scripts -->
<script src="{{ asset('/admin/libs/popper/popper.js') }}"></script>
<script src="{{ asset('/admin/js/bootstrap.js') }}"></script>
<script src="{{ asset('/admin/js/sidenav.js') }}"></script>

<!-- Libs -->
<script src="{{ asset('/admin/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

@yield('scripts')

</body>

</html>
