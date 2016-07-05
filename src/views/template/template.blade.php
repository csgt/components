<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<title>CORE DEV</title>

	{{ HTML::style('css/bootstrap.min.css'); }}
	{{ HTML::style('css/bootstrap-theme.min.css'); }}
	{{ HTML::style('css/bootstrap-datetimepicker.min.css'); }}
	{{ HTML::style('css/bootstrap-select.css'); }}
	{{ HTML::style('css/dataTables.bootstrap.css'); }}
	{{ HTML::style('css/core.css'); }}
  {{ HTML::style('css/bootstrapValidator.min.css'); }}

	{{ HTML::script('js/jquery.min.js') }}
	{{ HTML::script('js/moment-with-locales.min.js'); }}
	{{ HTML::script('js/bootstrap.min.js'); }}
	{{ HTML::script('js/bootstrap-datetimepicker.min.js'); }}
	{{ HTML::script('js/bootstrap-select.js'); }}
	{{ HTML::script('js/jquery.dataTables.min.js') }}
	{{ HTML::script('js/dataTables.bootstrap.js'); }}
  {{ HTML::script('js/bootstrapValidator.min.js'); }}

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script>
		$(document).ready(function(){
			$(".alert").delay(5000).fadeOut('slow');
		});
	</script>
	</head>
	<body>
		{{Session::get('menu')}} 
		<div class="main">
			Este es el menu
			<div class="container">
				@yield('content')
			</div>
		</div>
		<div class="footer">
			<p class="text-muted text-center">
				<a href="http://cs.com.gt" target="_blank">Compuservice Webdesigns </a> &copy; {{ date('Y') }}
			</p>
		</div>
	</body>
</html>