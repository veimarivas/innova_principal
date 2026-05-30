@yield('css')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Layout config Js -->
<script src="{{ URL::asset('build/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ URL::asset('build/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Brand Colors (override primario naranja, forzar sidebar oscuro) -->
<link href="{{ URL::asset('build/css/brand-colors.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Admin Common (dept-*, dph-*, footer, forms) -->
<link href="{{ URL::asset('build/css/admin-common.min.css') }}" rel="stylesheet" type="text/css" />
