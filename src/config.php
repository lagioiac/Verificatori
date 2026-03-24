<?php
	ini_set('display_errors',0); 
	error_reporting(0);
	session_start();
	define("WEB_ROOT","");
	define("DBHOST","localhost");

	//locale
	$PATHSITE = "localhost/dbverifiche"; // Path del DB usato 
	define("DBNAME","dbverifiche"); // nome del DB usato
	define("DBUSER","root");
	define("DBPASSWD","");  //secret
	
	//Variabili globali 
	define("STATO_CONCLUSA",5);
	define("STATO_ARCHIVIATA",1);

	include 'classlibrary/Manager.php';
	include 'classlibrary/Utility.php';
	
	$page_now = basename($_SERVER['PHP_SELF']); 
	$mysqli = new mysqli(DBHOST,DBUSER,DBPASSWD,DBNAME);
	if (mysqli_connect_errno()) {
		echo "Errore in connessione al DBMS: " . mysqli_connect_error();
		exit();
	}
	$sitename = "Verificatori";
	$FS = new Verificatori($mysqli);
	$UT = new Utility();
?>