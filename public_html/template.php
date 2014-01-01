<?php
if(!$this){
    exit;
}
?>
<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo $this->getTitle(); ?> - Jokkebrok Administratie</title>
        <script src="libs/jquery-1.10.2.min.js"></script>
		<link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	    <link href="libs/bootstrap-datepicker/css/datepicker.css" rel="stylesheet">
		<style type="text/css">
			html, body {
				height: 100%;
			}
			body {
				padding-top: 50px;
			}
			#wrap {
				min-height: 100%;
				height: auto;
				/* Negative indent footer by its height */
				margin: 0 auto -60px;
				/* Pad bottom by footer height */
				padding: 0 0 60px;
			}
            #content-container{
                margin-top:20px;
            }
			/* Set the fixed height of the footer here */
			#footer {
				height: 60px;
				background-color: #f5f5f5;
			}
		</style>
	</head>
	<body>
		<div id="wrap">
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="?">Jokkebrok Administratie</a>"
					</div>
					<ul class="nav navbar-nav" id="navbar">
						<li id="dashboard">
							<a href='?page=dashboard'>Dashboard</a>
						</li>
						<li id="aanwezigheden">
							<a href='?page=aanwezigheden'>Aanwezigheden</a>
						</li>
						<li id="kinderen">
							<a href='?page=kinderen'>Kinderen</a>
						</li>
						<li id="uitstappen">
							<a href='?page=uitstappen'>Uitstappen</a>
						</li>
						<li id="instellingen">
							<a href='?page=instellingen'>Instellingen</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
					    <li style="text-align: center;">
					        <span class="text-info">Het is vandaag<br><?php $vandaag = new SpeelpleinDag(); echo $vandaag->getFullDatum(); ?>.
					        </span>
					    </li>
						<li>
							<a href='?action=logout'>Uitloggen</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="container" id="content-container">
                <!--<h1><?php echo $this->getTitle(); ?></h1>-->
				<?php echo $this->getContent(); ?>

			</div>
		</div>
		<div id="footer">
			<div class="container">
				<p class="text-muted text-center">
					&copy; 2013-2014 Jokkebrok Administratie by Floris Kint &amp; Roderick Demol
				</p>
			</div>
		</div>
		<script src="libs/bootstrap/js/bootstrap.min.js"></script>
		<script src="libs/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script>
		    $('#navbar li#<?php echo $this->getCurrentTab(); ?>').addClass('active');
		</script>
		<script data-main="js/main" src="./libs/require.js"></script>
	</body>
</html>