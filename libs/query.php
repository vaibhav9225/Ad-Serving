<?php
$query = "";
$adQuery = "";
session_start();
error_reporting(0);
require_once('config.php');
require_once('database.php');
require_once('functions.php');
if(isset($_POST['query']) and !empty($_POST['query'])){
	$query = $_POST['query'];
	if(isset($_POST['save'])){
		$array = explode(' ',$query.trim());
		foreach($array as $word){
			$sql_query = "SELECT * FROM `popularity` WHERE `word`='$word'";
			$sql = mysql_query($sql_query);
			$count = mysql_num_rows($sql);
			if($count == 1){
				$data = mysql_fetch_assoc($sql);
				$id = $data['id'];
				$searches = $data['searches'] + 1;
				$sql_sub_query = "UPDATE `popularity` SET `searches`='$searches' WHERE `id`='$id'";
				$sql = mysql_query($sql_sub_query);
			}
			else{
				$sql_sub_query = "INSERT INTO `popularity` VALUES('','$word','1')";
				$sql = mysql_query($sql_sub_query);
			}
		}
	}
	$query_score = 0;
	$array = explode(' ',$query.trim());
	foreach($array as $word){
		$sql_query = "SELECT * FROM `popularity` WHERE `word`='$word'";
		$sql = mysql_query($sql_query);
		$data = mysql_fetch_assoc($sql);
		$query_score += $data['searches'];
	}
	$query_score = ((double)(5 + ($query_score/$_SESSION['KEY_FACTOR'])));
	$url = build_url('news', $query);
	$json = file_get_contents($url);
	$news = json_decode($json, true);
	$url = build_url('ad', $query);
	$json = file_get_contents($url);
	$ads = json_decode($json, true);
	$queryTime = $news['responseHeader']['QTime'];
	$suggestions = $news['spellcheck']['suggestions'];
	$suggestion_count = count($suggestions);
	$news_highlights = $news['highlighting'];
	$ad_highlights = $ads['highlighting'];
	$newsResults = $news['response']['numFound'];
	$news = $news['response']['docs'];
	$newsCount = count($news);
	$ads = $ads['response']['docs'];
	$adCount = count($ads);
	if($suggestion_count > 0 and $newsCount == 0 and isset($_POST['save'])){
		$new_query = $suggestions[$suggestion_count-1];
		$url = build_url('news', $new_query);
		$json = file_get_contents($url);
		$news = json_decode($json, true);
		$queryTime = $news['responseHeader']['QTime'];
		$newsResults = $news['response']['numFound'];
		$news = $news['response']['docs'];
		$newsCount = count($news);
		echo "<div class='results'>Showing results for <b><a class='red' href='index.php?query=".$new_query."' rel='".$new_query."'>".$new_query."</a></b> instead of &nbsp;<b>".$query."</b>. </div><br>";
	}
	else if($suggestion_count > 0) echo "<div class='results'>Did you mean <b><a class='red' href='index.php?query=".$suggestions[$suggestion_count-1]."' rel='".$suggestions[$suggestion_count-1]."'>".$suggestions[$suggestion_count-1]."</a></b> ?</div><br>";
	echo "<div class='results'>Showing top<b> $newsCount</b> result(s) out of $newsResults result(s) in $queryTime milliseconds.</div>";
	echo '
	<script type="text/javascript" src="scripts/clicks.js" ></script>
	<div class="results_left">
		<br>
	';
	for($i=0; $i<$newsCount; $i++){
		$docId = $news[$i]['docId'];
		if(isset($news_highlights[$docId]['title'])) $news[$i]['title'] = $news_highlights[$docId]['title'][0];
		if(isset($news_highlights[$docId]['content'])){
			$content = "";
			$count = 0;
			foreach($news_highlights[$docId]['content'] as $line){
				$count++;
				$content .= ucfirst(trim($line)).' ... ';
				if($count == 3) break;
			}
		}
		else{
			$content = "";
			$array = explode('.',$news[$i]['content']);
			$count = 0;
			if(isset($array[0])) $content .= ucfirst(trim($array[0])).' ... ';
			if(isset($array[1])) $content .= ucfirst(trim($array[1])).' ... ';
			if(isset($array[2])) $content .= ucfirst(trim($array[2])).' ... ';
		}
		echo "
			<a class='news_clicked' href='viewer.php?query=$query&docId=$docId' rel='$docId'>".ucfirst(strtolower($news[$i]['title']))."</a><br>
			<span>$content</span>
			<br><br>
		";
	}
	echo '
	</div>
	<div class="results_right">
		<br>';
	for($i=0; $i<min($adCount, 3); $i++){
		$docId = $ads[$i]['docId'];
		if(isset($ad_highlights[$docId]['keyword'])) $ads[$i]['keyword'] = $ad_highlights[$docId]['keyword'][0];
		if(isset($ad_highlights[$docId]['desc'])) $ads[$i]['desc'] = $ad_highlights[$docId]['desc'][0];
		$score = ($ads[$i]['score']+((1+$ads[$i]['clicks'])/$_SESSION['SORT_FACTOR']));
		if($i == 0){
			echo '<b>Related Ads</b><br><br> ';
			$top_scorer = $score * $query_score;
			$price = $query_score;
		}
		else{
			$price = $query_score + (($top_scorer/$score) - $query_score) + 0.01;
		}
		$title = "The bid price is calculated based on the relevency score of the Ad and the bid price \nof the competitors.\n\n";
		$title .= "The relevency score of this ad is ".number_format($score,2)." which was calculated based on the no of\ntimes the ad was clicked and its relevancy to the query.\n\n";
		$title .= "The minimum bid price (".number_format($query_score,2).") is calculated by the popularity of the query.\nMore popular the query, higher the bid price.\n\n";
		if($i == 0) $title .= "Since this was the most relevent ad, the suggested bid price is equal to minimum bid price.";
		else $title .= "The bid price here is suggested based on the score of this ad and the ad-score (".number_format($top_scorer,2).") \nof the most relevent ad. It is calculated for maximum ROI.";
		echo "
		<a class='ad_clicked' href='".$ads[$i]['link']."' rel='$docId'>".ucfirst(strtolower($ads[$i]['keyword']))."</a><br>
		<span class='green'>".substr($ads[$i]['link'], 0, 50)."</span><br>
		<span>".ucfirst(str_replace("...", "", $ads[$i]['desc']))."</span><br>
		<span class='bid'><img src='images/info.png' class='img'/> Suggested bid price is $".number_format($price,2)." &nbsp;<span class='help' title='$title'>[ ? ]</span></span>
		<br><br>
		";
	}
	echo '
	</div> ';
	if($suggestion_count > 0){
		echo '<div class="hide" id="suggestions">';
		$suggest = $suggestions[$suggestion_count-1];
		$array = explode(' ', trim($suggest));
		$array[count($array)-1] = "";
		$suggest = implode(' ', $array);
		$suggest = trim($suggest);
		$string = "";
		$count = 0;
		foreach($suggestions[$suggestion_count-3]['suggestion'] as $word){
			$word = trim($suggest." ".$word);
			$count++;
			$string .= '<a class="suggestion" href="index.php?query='.$word.'">'.$word.'</a>';
			if($count == 3) break;
		}
		echo $string;
		echo '</div>';
	}
}
else{
	header("Location: ../index.php");
}
?>