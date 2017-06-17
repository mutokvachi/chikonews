<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="login-box">
		<header>
			<div>Login</div>
		</header>
		
		<div class="content">
			<br><br>
			<form method="post" action="{{ route('authorization') }}">
				@if(isset($errors) && $errors->first('email') || session('status'))
					<i class="fa fa-envelope-o error"></i>
				@else
					<i class="fa fa-envelope-o"></i>
				@endif
				
				<input type="text" name="email" placeholder="Email" class="style" value="{{ old('email') }}">
				<br><br>
				@if(isset($errors) && $errors->first('password') || session('status'))
					<i class="fa fa-lock error"></i>
				@else
					<i class="fa fa-lock"></i>
				@endif
				<input type="password" name="password" placeholder="Password" class="style" value="{{ old('password') }}">
				<br>
				<div class="send_style">
					<button type="submit" name="send" class="" value><i class="fa fa-long-arrow-right"></i></button>
				</div>
				{{ csrf_field() }}
			</form>
		</div>
	</div>
</body>
</html>