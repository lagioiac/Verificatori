<?php

    // Imposta la durata della sessione a 1 ora (3600 secondi)
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    /*
    ini_set('display_errors',1); //il valore era 0 anizchè 1
    ini_set('display_startup_errors', 1); // riga di codice aggiunta
    error_reporting(E_ALL); // il valore tra parentesi era zero
    */
    
    ini_set('display_errors',0); 
    error_reporting(0); 
    
    
    session_start();
    define("WEB_ROOT","");
    define("DBHOST","localhost");
    //locale
    $PATHSITE = "localhost/Verificatori";
    define("DBNAME","dbverifiche");
    define("DBUSER","root");
    define("DBPASSWD","");  //secret
    //Variabili globali 29/06/2017
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
    $FS = new Verificatori($mysqli); //incluso in Manager.php
    $UT = new Utility();
    
?>