<?php

function dbh(){
$hostname = 'localhost';
$username = 'root';
$password = '';

	try {
	    $conn = new PDO("mysql:host=$hostname;dbname=justchat", $username, $password);
	    }
	catch(PDOException $e)
	    {
	    echo $e->getMessage();
	    }
	return $conn;
 }
?>
