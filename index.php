<!Doctype html>
<html>
<a  class="side" href="index.php"><span>H<br>O<br>M<br>E<br></span></a>

<head>
	<title>Oxymoron Search</title>
	<link rel="stylesheet" href="css/styles.css" />
	<link rel="stylesheet" href="css/responsive.css" />
	<script type="text/javascript" src="scripts/jquery.js" ></script>
	<script type="text/javascript" src="scripts/query.js" ></script>
	<?php
	$query = "";
	error_reporting(0);
	if(isset($_GET['query']) and !empty($_GET['query'])){
		$query = $_GET['query'];
		if(isset($_GET['reloaded']) and !empty($_GET['reloaded'])){
			$reloaded = 'true';
		}
		echo '<script type="text/javascript">';
		echo '$(document).ready(function(){';
		echo "
		var query = '$query';
		$.post('libs/query.php',{query:query, save:'true'},function(data){
			$('#results').html(data);
		},'html');
		";
		echo '});';
		echo '</script>';
	}
	?>
</head>
<body>
<div class="header">
	<div class="tab"></div>
	<a href="index.php">Oxymoron </a>
	<span class="mini">search</span>
	<div class="tab"></div>
	<form class="header_form" action="index.php" method="GET">
		<input class="header_textbox" id="header_textbox" name="query" type="text" value="<?php echo $query; ?>" placeholder="Search here ..." autocomplete="off" />
		<input class="header_submit" id="header_submit" type="submit" value="Search" />
	</form>
</div>
<br><br><br><br>
<div id="results">
</div>
</body>
</html>