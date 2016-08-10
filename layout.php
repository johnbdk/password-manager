<?php session_start(); ?>
<?php if(!isset($_SESSION['username'])) header("Location:index.php"); ?>
<!DOCTYPE html>
<html id="html-layout">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<title>Seve Password Manager</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.js"></script> 
	<script src="client.js"></script>
</head>
<body id="body-layout">

<div class="top-bar-container">
	<div class="left-top-bar">
		<a href="layout.php">
			<img id="img-seve-logo" src="img/seve_logo.jpg" alt="seve">
		</a>
	</div>
	<div class="right-top-bar">
		<p>Logged in as <strong><?php require_once("server.php"); echo $_SESSION["username"];?></strong></p>
		<a id="logout" href="#">Log-out</a>
	</div>
</div>

<div class="parent-box-container">
	<div class="child-left-box">
		<div class="table-responsive" style="overflow:auto" >
			<table id="table-rslt" class="table table-hover table-striped">
				<thead>
					<tr>
						<th>id</th>
						<th>Account Name</th>
						<th>Username</th>
						<th>Password</th>
						<th>Comment</th>
						<th>URL</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="no-rslt">
			<p><strong><i>No results yet</i></strong></p>
		</div>
	</div>
	<div class="child-right-box">
		<div class="center-accord">
			<button class="accordion" id="accordion-search">Search</button>
			<div class="panel">
				<form class="form-inline form-padd">
					<div class="form-group">
						<label class="sr-only" for="search-account-name">Account Name</label>
						<input type="text" class="form-control" id="search-account-name" placeholder="Account Name" required>
						<span>&nbsp;&nbsp;<strong>,</strong>&nbsp;&nbsp;</span>
					</div>
					<div class="form-group">
						<label class="sr-only" for="search-username">Username</label>
						<input type="text" class="form-control" id="search-username" placeholder="Username">
						<span>&nbsp;&nbsp;<strong>or/and</strong>&nbsp;&nbsp;</span>
					</div>
					<div class="form-group">
						<label class="sr-only" for="search-url">URL</label>
						<input type="text" class="form-control" id="search-url" placeholder="URL">
					</div>
					<button id="btn-search" type="submit" class="btn btn-default">Search</button>	
				</form>
			</div>
			<button class="accordion">Add</button>
			<div class="panel">
				<form class="form-inline form-padd val-form-add">
					<div class="form-group">
						<label class="sr-only" for="add-account-name">Account Name</label>
						<input type="text" class="form-control" id="add-account-name" name="accountName" placeholder="Account Name">
					</div>
					<div class="form-group">
						<label class="sr-only" for="add-username">Username</label>
						<input type="text" class="form-control" id="add-username" name="username" placeholder="Username">
					</div>
					<div class="form-group">
						<label class="sr-only" for="add-password">Password</label>
						<input type="password" class="form-control checkpass1" id="add-password" name="password" placeholder="Password">
					</div>
					<div class="form-group">
						<label class="sr-only" for="add-confirm">Confirm</label>
						<input type="password" class="form-control" id="add-confirm" name="confirmPassword" placeholder="Confirm Password">
					</div>
					<div class="form-group">
						<label class="sr-only" for="add-comment">Comment</label>
						<input type="text" class="form-control" id="add-comment" placeholder="Comment">
					</div>
					<div class="form-group">
						<label class="sr-only" for="add-url">URL</label>
						<input type="url" class="form-control" id="add-url"  placeholder="URL">
					</div>
					<button id="btn-add" type="submit" class="btn btn-default">Submit</button>	
				</form>
				<div class="error-msg"></div>
			</div>
			<button class="accordion">Update</button>
			<div class="panel">
				<p id="update-msg">Search for a record first.</p>
				<form class="form-inline form-padd form-update">
					<div class="form-group">
						<label class="control-label" for="upd-rslt-username">Pick a username from result&nbsp;&nbsp;</label>
						<input type="text" class="form-control" id="upd-rslt-username" placeholder="Username">
					</div>
					<button id="btn-rslt-upd" type="submit" class="btn btn-default">Pick</button>
				</form>
				<div class="form-update" id="msg-new-pass">
					<p>Optional changing new password<p>
				</div>
				<form class="form-inline form-padd form-update val-form-upd" id="form-upd-id">
					<div class="form-group">
						<label class="sr-only" for="upd-account-name">Account Name</label>
						<input type="text" class="form-control" id="upd-account-name" name="accountName" placeholder="Account Name">
					</div>
					<div class="form-group">
						<label class="sr-only" for="upd-username">Username</label>
						<input type="text" class="form-control" id="upd-username" name="username" placeholder="Username">
					</div>
					<div class="form-group">
						<label class="sr-only" for="upd-password">Password</label>
						<input type="password" class="form-control" id="upd-password" placeholder="Password" disabled="true">
					</div>
					<div class="form-group">
						<label class="sr-only" for="upd-new-password">New Password</label>
						<input type="password" class="form-control checkpass2" id="upd-new-password" name="password" placeholder="New Password">
					</div>
					<div class="form-group">
						<label class="sr-only" for="upd-confirm">Confirm</label>
						<input type="password" class="form-control" id="upd-confirm" name="confirmPassword" placeholder="Confirm Password">
					</div>
					<div class="form-group">
						<label class="sr-only" for="upd-comment">Comment</label>
						<input type="text" class="form-control" id="upd-comment" placeholder="Comment">
					</div>
					<div class="form-group">
						<label class="sr-only" for="upd-url">URL</label>
						<input type="url" class="form-control" id="upd-url" placeholder="URL">
					</div>
					<button id="btn-upd" type="submit" class="btn btn-default">Update</button>	
				</form>
			</div>
		</div>
	</div>
</div>

</body>
</html>