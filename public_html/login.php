<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Jokkebrok Administratie</title>
		<link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<style type="text/css">
			.form-signin {
				max-width: 330px;
				margin: 0 auto;
			}
			.form-signin .form-control {
				position: relative;
				font-size: 16px;
				height: auto;
				padding: 10px;
				-webkit-box-sizing: border-box;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<form class="form-signin" action="index.php?action=login" method="post">
				<h2>Gelieve in te loggen</h2>
				<?php
				if(isset($_GET['action']) && $_GET['action']=='login'){
					?>
					<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Login mislukt, probeer opnieuw!</div>
					<?php
				}
				?>
				<input name="username" type="text" placeholder="Gebruikersnaam" autofocus="" class="form-control">
				<input name="password" type="password" placeholder="Wachtwoord" class="form-control">
				<button class="btn btn-lg btn-primary btn-block" type="submit" class="form-control">
					Log in
				</button>
			</form>
		</div>
		<script src="libs/jquery-1.10.2.min.js"></script>
		<script src="libs/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>