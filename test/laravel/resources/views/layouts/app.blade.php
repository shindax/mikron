<!DOCTYPE html>
<html lang="en">
	<head>
		<title></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<script type='text/javascript' src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


		<link rel="stylesheet" href="{!! asset('/font/css/open-iconic-bootstrap.min.css') !!}" type="text/css">
		<link rel="stylesheet" href="{!! asset('/font/css/open-iconic-bootstrap.css') !!}" type="text/css">
		<link rel="stylesheet/scss" type="text/css" href="{!! asset('/font/css/open-iconic-bootstrap.scss') !!}">
		<link rel="stylesheet/less" type="text/css" href="{!! asset('/font/css/open-iconic-bootstrap.less') !!}">
		<link rel="stylesheet/styl" type="text/css" href="{!! asset('/font/css/open-iconic-bootstrap.styl') !!}">
		
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	</head>
	<body>
		@yield('content')
	</body>
</html>