<?php
function curl_update($port, $docId){
	$url = "http://localhost:".$port."/solr/collection1/select?q=".urlencode("docId:$docId")."&wt=json";
	$json = file_get_contents($url);
	$data = json_decode($json, true);
	$data = $data['response']['docs'];
	$clicks = $data[0]['clicks'];
	$clicks = $clicks + 1;
	$array = ['add'=>['doc'=>['docId'=>$docId,'clicks'=>['set'=>$clicks]]]];
	$json =  json_encode($array);
	$ch = curl_init("http://localhost:".$port."/solr/collection1/update/?commit=true");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	$response = curl_exec($ch);
	return $response;
}
if(isset($_POST['docId']) and !empty($_POST['docId'])){
	$docId = $_POST['docId'];
	$port = $_POST['port'];
	curl_update($port, $docId);
}
else{
	header("Location: ../index.php");
}
?>