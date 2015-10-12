<?php
error_reporting(0);
$connection = mysql_connect(DatabaseServer, DatabaseUsername, DatabasePassword);
if(!$connection) die('<li>The connection to the database failed.</li>');
else{
	$database = mysql_select_db(DatabaseName, $connection);
	if (!$database) die('<li>The connection to the database failed.</li>');
}
?>