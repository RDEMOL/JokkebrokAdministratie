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
        <script data-main="js/main" src="./libs/require.js"></script>
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
			#navContainer[max-width="980px"] #datumDiv {
			    display:none;
			}
		</style>
	</head>
	<body>
		<div id="wrap">
			<div class="navbar navbar-inverse navbar-fixed-top" id="navContainer" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="?">Jokkebrok</a>
					</div>
					<div class="collapse navbar-collapse">
    					<ul class="nav navbar-nav" id="navbar">
    						<li id="dashboard">
    							<a href='?page=dashboard'>Dashboard</a>
    						</li>
    						<li id="aanwezigheden">
    							<a href='?page=aanwezigheden'>Aanwezig</a>
    						</li>
    						<li id="kinderen">
    							<a href='?page=kinderen'>Kinderen</a>
    						</li>
    						<li id="uitstappen">
    							<a href='?page=uitstappen'>Uitstappen</a>
    						</li>
    						</ul>
    						<ul class="nav navbar-nav pull-right">
    						<li id="instellingen">
    							<a href='?page=instellingen'><span class="glyphicon glyphicon-cog"></span></a>
    						</li>
    					     <li>
    					        <a href='#' class="text-info">
    					            <?php 
    					            $vandaag = new SpeelpleinDag(); 
    					            $full_datum = $vandaag->getFullDatum(); 
                                    $day_of_week = $vandaag->getDayOfWeek();
    					            //$sep = strpos($full_datum, ' '); 
    					            //$full_datum = substr($full_datum, 0, $sep)."<br>".substr($full_datum, $sep+1, strlen($full_datum)-$sep-1);
    					            //echo $full_datum;
    					            echo $day_of_week;//."<br>".$vandaag->getDatum(); 
    					            ?>
    					        </a>
    					    </li>
    						<li>
    							<a href='?action=logout'><span class="glyphicon glyphicon-log-out"></span></a>
    						</li>
    						
                            <!--<li id="datumDiv">
                                <a href='#'>
                                    <?php
                                    $vandaag = new SpeelpleinDag();
                                    echo $vandaag->getFullDatum();
                                    ?>
                                </a>
                            </li>-->
    					</ul>
    					<!--<div id="dateDiv">
    					    <?php
                            $vandaag = new SpeelpleinDag();
                            echo $vandaag->getFullDatum();
                            ?>
    					</div>-->
    					<script>
    					    $(document).ready(function(){
    					      // $('#dateDiv'). 
    					    });
    					</script>
					</div>
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
		<script src="libs/typeahead.min.js"></script>
		<script>
		    $('#navbar li#<?php echo $this->getCurrentTab(); ?>').addClass('active');
		</script>
	</body>
</html>