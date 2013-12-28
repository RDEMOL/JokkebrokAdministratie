<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Jokkebrok Administratie</title>
		<link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<!--<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>-->
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
			    <div class="navbar-header">
			        <a class="navbar-brand" href="#">Jokkebrok Administratie</a>"
			    </div>
				<ul class="nav navbar-nav">
					<li class="active"><a href='?page=dashboard'>Dashboard</a></li>
					<li><a href='?page=aanwezigheden'>Aanwezigheden</a></li>
					<li><a href='?page=kinderen'>Kinderen</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
                    <li><a href='?action=logout'>Uitloggen</a></li>
                </ul>
			</div>
		</div>
		<script src="libs/jquery-1.10.2.min.js"></script>
		<script src="libs/bootstrap/js/bootstrap.min.js"></script>
		<script data-main="js/main" src="./libs/require.js"></script>
	</body>
</html>