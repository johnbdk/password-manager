<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<title>Seve Log-in authorization</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script src="client.js"></script>
</head>
<body id="body-login">

<div class="login-page">
	<a href="index.php">
		<img id="img-seve-logo" src="img/seve_logo.jpg" alt="seve">
	</a>
	<div class="centered">
		<div class="form-title col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<h3 id="title"><strong>Password Manager</strong></h3>
			<br><br>
		</div>
		<div class="form-centered">
			<form class="form-horizontal">
				<div class="form-group">
					<label for="input-username" class="col-xs-12 col-sm-3 col-md-3 control-label">Username</label>
					<div class="col-xs-12 col-sm-9 col-md-7">
						<input id="username" class="form-control" type="text" placeholder="enter username">
					</div>
				</div>
				<div class="form-group">
					<label for="input-password" class="col-xs-12 col-sm-3 col-md-3 col-lg-3 control-label">Password</label>
					<div class="col-xs-12 col-sm-9 col-md-7">
						<input id="password" class="form-control" type="password" placeholder="enter password">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-3 col-md-3">
						<button id="btn-login" type="submit" class="btn btn-default btn-md">LOGIN</button>
				</div>
			</form>
		</div>
		<div id="login-error">
			<p>Invalid Login or Password!<br>Please try again.</p>
		</div>
		<div id="login-successful">
			<p>Login was Successful.</p>
		</div>
	</div>
</div>

</body>
</html>