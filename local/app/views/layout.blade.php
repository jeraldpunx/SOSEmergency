<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link href="{{ URL::asset('assets/css/flat-ui.min.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/myStyle.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/notifIt.css') }}" rel="stylesheet">
	@yield('style')

	<script type="text/javascript">
		function init() {
			window.addEventListener('scroll', function(e){
				var distanceY = window.pageYOffset || document.documentElement.scrollTop,
					shrinkOn = 200,
					header = document.querySelector("header");
				if (distanceY > shrinkOn) {
					classie.add(header,"smaller");
				} else {
					if (classie.has(header,"smaller")) {
						classie.remove(header,"smaller");
					}
				}
			});
		}
		window.onload = init();
	</script>
		
	
</head>
<body>
	<header>
<<<<<<< HEAD:local/app/views/layout.blade.php
		<div class="container clearfix">
			<h1 id="logo">
				<a href="{{ URL::route('home') }}"><img src="{{ URL::asset('assets/img/logo.png') }}" style="height: 100%;"></a>
			</h1>
			<nav>
				<a href="{{ URL::route('realtimemap') }}">Live Map</a>
				<a href="{{ URL::route('mapview') }}">Manage</a>
				<a href="">View Request</a>
			</nav>
		</div>
=======
	    <div class="container clearfix">
	        <h1 id="logo">
	            <a href="{{ URL::route('home') }}"><img src="assets/img/logo.png" style="height: 100%;"></a>
	        </h1>
	        <nav>
	           <!--  <a href="">Lorem</a>
	            <a href="">Ipsum</a>
	            <a href="">Dolor</a> -->
	        </nav>
	    </div>
>>>>>>> origin/master:app/views/layout.blade.php
	</header><!-- /header -->

	<div id="wrapper">
		@yield('content')
	</div>

	<footer>
		John Carlo Mamites | Jerald Patalinghug | Kevin Rey Tabada
		<br>
		Copyright SOS Emergency Response &copy; 2015 TMP&trade;.
	</footer>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="{{ URL::asset('assets/js/classie.js') }}"></script>
	<script src="{{ URL::asset('assets/js/flat-ui.min.js') }}"></script>
	<script src="{{ URL::asset('assets/js/notifIt.js') }}"></script>
	<script src="{{ URL::asset('assets/js/pace.min.js') }}"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.js"></script>
	<script src="{{ URL::asset('assets/js/application.js') }}"></script>


	@yield('script')
</body>
</html>