<?php
session_start();
error_reporting(0);
function build_url($index, $user_query, $indent=false){
	$query = "SELECT * FROM `parameters` WHERE `index_name`='$index'";
	$sql = mysql_query($query);
	$data = mysql_fetch_assoc($sql);
	foreach($data as $key=>$value){
		${$index}[$key] = $value;
	}
	$index = ${$index}['index_name'];
	unset(${$index}['index_name']);
	$url = "http://localhost:".${$index}['port']."/solr/collection1/select?wt=json&defType=dismax";
	$suburl = "!dismax";
	$sub = $index.'_sub';
	${$sub} = array();
	unset(${$index}['port']);
	define('SORT_FACTOR',${$index}['sort_factor']);
	$_SESSION['SORT_FACTOR'] = ${$index}['sort_factor'];
	$_SESSION['KEY_FACTOR'] = ${$index}['key_factor'];
	if($index == 'news'){
		unset(${$index}['sort_factor']);
		unset(${$index}['key_factor']);
	}
	${$index}['hl'] = ${$index}['hl'] ? 'true' : 'false';
	${$index}['hl.snippets'] = 10;
	${$index}['hl.simple.pre'] = urlencode("<span class='highlight'>");
	${$index}['hl.simple.post'] = urlencode("</span>");
	${$index}['hl.usePhraseHighlighter'] = ${$index}['hl_phrase'] ? 'true' : 'false';
	${$index}['hl.highlightMultiTerm'] = ${$index}['hl_multiterm'] ? 'true' : 'false';
	unset(${$index}['hl_phrase']);
	unset(${$index}['hl_multiterm']);
	$fl_array = explode(',',${$index}['fl']);
	${$index}['fl'] = $fl_array[0];
	for($i=1; $i<count($fl_array); $i++){
		${$index}['fl'] .= ','.$fl_array[$i];
	}
	${$sub}['fl'] = "'".${$index}['fl']."'";
	${$sub}['df'] = "'".${$index}['df']."'";
	$qf_array = explode(',',${$index}['qf']);
	$qf_boosts_array = explode(',',${$index}['qf_boosts']);
	${$index}['qf'] = $qf_array[0].'^'.$qf_boosts_array[0];
	for($i=1; $i<count($qf_array); $i++){
		${$index}['qf'] .= ' '.$qf_array[$i].'^'.$qf_boosts_array[$i];
	}
	${$sub}['qf'] = "'".${$index}['qf']."'";
	${$sub}['mm'] = "'".${$index}['mm']."%'";
	${$index}['mm'] = urlencode(${$index}['mm'].'%');
	${$index}['fl'] = urlencode(${$index}['fl']);
	${$index}['qf'] = urlencode(${$index}['qf']);
	unset(${$index}['qf_boosts']);
	${$index}['pf'] = explode(',', ${$index}['pf'])[0].'^'.${$index}['pf_boosts'];
	${$sub}['pf'] = "'".${$index}['pf']."'";
	${$sub}['ps'] = ${$index}['ps'];
	${$sub}['qs'] = ${$index}['qs'];
	${$sub}['tie'] = ${$index}['tie'];
	unset(${$index}['pf_boosts']);
	foreach(${$index} as $key=>$value){
		$url .= '&'.$key.'='.$value;
	}
	foreach(${$sub} as $key=>$value){
		$suburl .= ' '.$key.'='.$value;
	}
	$suburl .= " v='".$user_query."'";
	$user_query = urlencode($user_query);
	if($indent == '') $indent = false;
	$sort = "sum(div(sum(1,clicks), ".SORT_FACTOR."), query({".$suburl."}, 0.01)) desc";
	$sort = urlencode($sort);
	return $url."&q=$user_query&indent=$indent&sort=$sort";
}
?>