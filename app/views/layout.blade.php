<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/flat-ui.min.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/notifIt.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/myStyle.css') }}" rel="stylesheet">
	
	<style type="text/css">
		@yield('style')
	</style>
</head>
<body>
	<div id="container">
		@yield('content')
	</div>
	
	<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="{{ URL::asset('assets/js/flat-ui.min.js') }}"></script>
	<script src="{{ URL::asset('assets/js/notifIt.js') }}"></script>
	<script src="{{ URL::asset('assets/js/prettify.js') }}"></script>
	<script src="{{ URL::asset('assets/js/application.js') }}"></script>

	@yield('script')
</body>
</html>