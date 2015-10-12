<?php
$login = "";
$form = "hide";
session_start();
error_reporting(0);
require_once('../libs/config.php');
require_once('../libs/database.php');
if(isset($_POST['username']) and !empty($_POST['username']) and isset($_POST['password']) and !empty($_POST['password'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	if($username == "admin" and $password == "admin"){
		$_SESSION['admin'] = md5('admin');
	}
}
if(isset($_SESSION['admin']) and !empty($_SESSION['admin']) and $_SESSION['admin'] == md5('admin')){
	$form = '';
	$login = 'hide';
}
$name = 'news';
if(isset($_POST['name']) and !empty($_POST['name'])){
	$name = mysql_real_escape_string($_POST['name']);
	$port = mysql_real_escape_string($_POST['port']);
	$sort_factor = mysql_real_escape_string($_POST['sort_factor']);
	$fl = mysql_real_escape_string($_POST['fl']);
	$df = mysql_real_escape_string($_POST['df']);
	$qf = mysql_real_escape_string($_POST['qf']);
	$qf_boosts = mysql_real_escape_string($_POST['qf_boosts']);
	$mm = mysql_real_escape_string($_POST['mm']);
	$pf = mysql_real_escape_string($_POST['pf']);
	$pf_boosts = mysql_real_escape_string($_POST['pf_boosts']);
	$ps = mysql_real_escape_string($_POST['ps']);
	$qs = mysql_real_escape_string($_POST['qs']);
	$tie = mysql_real_escape_string($_POST['tie']);
	$key_factor = mysql_real_escape_string($_POST['key_factor']);
	$hl = 0;
	$hl_phrase = 0;
	$hl_multiterm = 0;
	if(isset($_POST['hl'])){
			$hl = 1;
	}
	if(isset($_POST['hl_phrase'])){
			$hl_phrase = 1;
	}	
	if(isset($_POST['hl_multiterm'])){
			$hl_multiterm = 1;
	}
	$query = "DELETE FROM `parameters` WHERE `index_name`='$name'";
	$sql = mysql_query($query);
	$query = "INSERT INTO `parameters` VALUES('$name','$port','$sort_factor','$fl','$df','$qf','$qf_boosts','$mm','$pf','$pf_boosts','$ps','$qs','$tie','$hl','$hl_phrase','$hl_multiterm','$key_factor')";
	$sql = mysql_query($query);
}

$ad_port = 1001;
$ad_sort_factor = "";
$ad_fl = "";
$ad_df = "";
$ad_qf = "";
$ad_qf_boosts = "";
$ad_mm = "";
$ad_pf = "";
$ad_pf_boosts = "";
$ad_ps = "";
$ad_qs = "";
$ad_tie = "";
$ad_key_factor = "";
$ad_ad_factor = "";
$ad_hl = "";
$ad_hl_phrase = "";
$ad_hl_multiterm = "";

$news_port = 1002;
$news_sort_factor = 0;
$news_fl = "";
$news_df = "";
$news_qf = "";
$news_qf_boosts = "";
$news_mm = "";
$news_pf = "";
$news_pf_boosts = "";
$news_ps = "";
$news_qs = "";
$news_tie = "";
$news_key_factor = "";
$news_ad_factor = "";
$news_hl = "";
$news_hl_phrase = "";
$news_hl_multiterm = "";

$array = ["news", "ad"];
foreach($array as $index){
	$query = "SELECT * FROM `parameters` WHERE `index_name`='$index'";
	$sql = mysql_query($query);
	$data = mysql_fetch_assoc($sql);
	foreach($data as $key=>$value){
		${$index.'_'.$key} = $value;
	}
}

function value($name, $index){
	global ${$index.'_'.$name};
	echo 'value="'.${$index.'_'.$name}.'" ';
}

?>
<!Doctype html>
<html>
<a  class="side" href="../index.php"><span>H<br>O<br>M<br>E<br></span></a>
<head>
	<title>Oxymoron Admin</title>
	<link rel="stylesheet" href="../css/styles.css" />
	<link rel="stylesheet" href="../css/responsive.css" />
	<script type="text/javascript" src="../scripts/jquery.js" ></script>
	<script type="text/javascript" src="../scripts/admin.js"></script>
	<?php
	if($name == 'ad'){
		echo '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#ad").click();
		});
		</script>
		';
	}
	?>
</head>
<body>
<div class="header">
	<div class="tab"></div>
	<a href="index.php">Oxymoron </a>
	<span class="mini">admin</span>
	<form class="header_form <?php echo $login; ?>" action="index.php" method="POST">
		<input class="header_submit header_login header_login_submit" id="header_submit" type="submit" value="Sign In" />
		<input class="header_textbox header_login" name="password" type="password" placeholder="Enter password here ..." autocomplete="off" />
		<input class="header_textbox header_login" name="username" type="text" placeholder="Enter username here ..." autocomplete="off" />
		<span class="header_text">Log In: </span>
	</form>
	<a href="logout.php" class="header_logout" <?php if($form == "hide") echo "style='display:none;'"; ?>>Log Out</a>
</div>
<br><br><br>
<div class="form <?php echo $form; ?>">
<h1><span id="news" class="red">News Parameters</span><span class="tab"></span> | <span class="tab"></span><span id="ad">Ad Parameters</span></h1>
<hr><br>
<form id="news_form" method="POST" action="index.php">
<div class="input hide"><span></span> <input type="text" name="name" value="news"/></div>
<div class="input"><span>Default Port</span> <input class="form_textbox" type="text" name="port"  <?php value('port', 'news'); ?>/></div>
<div class="input"><span>Sort Factor</span> <input class="form_textbox" type="text" name="sort_factor" <?php value('sort_factor', 'news'); ?>/></div>
<div class="input"><span>Field Lists</span> <input class="form_textbox" type="text" name="fl"  <?php value('fl', 'news'); ?>/></div>
<div class="input"><span>Default Field</span> <input class="form_textbox" type="text" name="df" <?php value('df', 'news'); ?>/></div>
<div class="input"><span>Query Fields</span> <input class="form_textbox" type="text" name="qf" <?php value('qf', 'news'); ?>/></div>
<div class="input"><span>Query Field Boosts</span> <input class="form_textbox" type="text" name="qf_boosts" <?php value('qf_boosts', 'news'); ?>/></div>
<div class="input"><span>Minimum Word Match (%)</span> <input class="form_textbox" type="text" name="mm" <?php value('mm', 'news'); ?>/></div>
<div class="input"><span>Phrase Field</span> <input class="form_textbox" type="text" name="pf" <?php value('pf', 'news'); ?>/></div>
<div class="input"><span>Phrase Field Boost</span> <input class="form_textbox" type="text" name="pf_boosts" <?php value('pf_boosts', 'news'); ?>/></div>
<div class="input"><span>Phrase Slop</span> <input class="form_textbox" type="text" name="ps" <?php value('ps', 'news'); ?>/></div>
<div class="input"><span>Query Slop</span> <input class="form_textbox" type="text" name="qs" <?php value('qs', 'news'); ?>/></div>
<div class="input"><span>Tie Breaker</span> <input class="form_textbox" type="text" name="tie" <?php value('tie', 'news'); ?>/></div>
<div class="input"><span>Enable Highlighting</span> <input class="form_textbox form_checkbox" type="checkbox" name="hl" <?php if($news_hl == '1') echo 'checked'; ?>/></div>
<div class="input"><span>Phrase Highlighting</span> <input class="form_textbox form_checkbox" type="checkbox" name="hl_phrase" <?php if($news_hl_phrase == '1') echo 'checked'; ?>/></div>
<div class="input"><span>Multiterm Highlighting</span> <input class="form_textbox form_checkbox" value="" type="checkbox" name="hl_multiterm" <?php if($news_hl_multiterm == '1') echo 'checked'; ?>/></div>
<div class="input hide"><span></span> <input type="text" name="key_factor" value="0"/></div>
<div class="input"><input class="form_submit" type="submit" value="Save" /></div>
</form>
<form id="ad_form" class="hide" method="POST" action="index.php">
<div class="input hide"><span></span> <input type="text" name="name" value="ad"/></div>
<div class="input"><span>Default Port</span> <input class="form_textbox" type="text" name="port"  <?php value('port', 'ad'); ?>/></div>
<div class="input"><span>Sort Factor</span> <input class="form_textbox" type="text" name="sort_factor" <?php value('sort_factor', 'ad'); ?>/></div>
<div class="input"><span>Field Lists</span> <input class="form_textbox" type="text" name="fl" <?php value('fl', 'ad'); ?>/></div>
<div class="input"><span>Default Field</span> <input class="form_textbox" type="text" name="df" <?php value('df', 'ad'); ?>/></div>
<div class="input"><span>Query Fields</span> <input class="form_textbox" type="text" name="qf" <?php value('qf', 'ad'); ?>/></div>
<div class="input"><span>Query Field Boosts</span> <input class="form_textbox" type="text" name="qf_boosts" <?php value('qf_boosts', 'ad'); ?>/></div>
<div class="input"><span>Minimum Word Match (%)</span> <input class="form_textbox" type="text" name="mm" <?php value('mm', 'ad'); ?>/></div>
<div class="input"><span>Phrase Field</span> <input class="form_textbox" type="text" name="pf" <?php value('pf', 'ad'); ?>/></div>
<div class="input"><span>Phrase Field Boost</span> <input class="form_textbox" type="text" name="pf_boosts" <?php value('pf_boosts', 'ad'); ?>/></div>
<div class="input"><span>Phrase Slop</span> <input class="form_textbox" type="text" name="ps" <?php value('ps', 'ad'); ?>/></div>
<div class="input"><span>Query Slop</span> <input class="form_textbox" type="text" name="qs" <?php value('qs', 'ad'); ?>/></div>
<div class="input"><span>Tie Breaker</span> <input class="form_textbox" type="text" name="tie" <?php value('tie', 'ad'); ?>/></div>
<div class="input"><span>Key Factor</span> <input class="form_textbox" type="text" name="key_factor" <?php value('key_factor', 'ad'); ?>/></div>
<div class="input"><span>Enable Highlighting</span> <input class="form_textbox form_checkbox" type="checkbox" name="hl" <?php if($ad_hl == '1') echo 'checked'; ?>/></div>
<div class="input"><span>Phrase Highlighting</span> <input class="form_textbox form_checkbox" type="checkbox" name="hl_phrase" <?php if($ad_hl_phrase == '1') echo 'checked'; ?>/></div>
<div class="input"><span>Multiterm Highlighting</span> <input class="form_textbox form_checkbox" type="checkbox" name="hl_multiterm" <?php if($ad_hl_multiterm == '1') echo 'checked'; ?>/></div>
<div class="input"><input class="form_submit" type="submit" value="Save" /></div>
</form>
<br><br><br>
</div>
<body>
</html>