<?php
$query = $_GET['query'];
if(isset($_GET['query']) and !empty($_GET['query']) and isset($_GET['docId']) and $_GET['docId']!=""){
	$query = $_GET['query'];
	$docId = $_GET['docId'];
	$url = "http://localhost:1002/solr/collection1/select?q=docId:".urlencode($docId)."&wt=json";
	$json = file_get_contents($url);
	$news = json_decode($json, true);
	$news = $news['response']['docs'];
	$article = null;
	if(isset($news[0]) and !empty($news[0])) $article = $news[0];
	else header("Location: index.php?query=$query");
}
else header("Location: index.php?query=$query");
function format($str){
	$array = str_split($str);
	$count = 0;
	$string = "<div class='tab'></div><div class='tab'></div><div class='tab'></div><div class='tab'></div><div class='tab'></div>";
	for($i=0; $i<count($array); $i++){
		if($array[$i] == '.' and isset($array[$i+1]) and $array[$i+1] == ' ') $count++;
		$string .= $array[$i];
		if($count == 5){
			$count = 0;
			$string .= '<br><br><div class="tab"></div><div class="tab"></div><div class="tab"></div><div class="tab"></div><div class="tab"></div>';
			$string .= ucfirst($array[$i+1]);
			$i++;
		}
	}
	return $string;
}
?>
<!Doctype html>
<html>
<head>
	<title>Oxymoron Articles</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<div class="article">
	<div class="title"><?php echo ucwords(strtolower($article['title'])); ?></div>
	<div class="info">
		<span class="left">
		<?php
		if(isset($article['date']) and !empty($article['date'])) echo $article['date'];
		if(isset($article['date']) and !empty($article['date']) and isset($article['place']) and !empty($article['place'])) echo ', ';
		if(isset($article['place']) and !empty($article['place'])) echo $article['place']; 
		?>
		</span>
		<span class="right">
		<?php
		if(isset($article['author']) and !empty($article['author'])) echo 'By '.$article['author']; 
		?>
		</span>
	</div>
	<hr><br>
	<div class="content"><?php echo format($article['content']); ?></div>
	<a class="back" href="<?php echo "index.php?query=$query"; ?>">&#8617; Go Back.</a><br><br>
</div>
</body>
</html>