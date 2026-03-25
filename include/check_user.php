<?php

	// Inizia la sessione - Questo assicura che session_start() venga chiamata solo se una sessione non è già stata avviata.
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}


	// Controlla se l'utente è loggato 
	if (!isset($_SESSION['user']) || empty($_SESSION['user']))
	{
		// L'utente non è loggato, salva la pagina attuale per il redirect dopo il login
		$_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
		
		// Reindirizza al login con un messaggio di errore
		header("Location: index.php?error=not_logged_in");
		exit;
	}
        
        // Verifica che la variabile di sessione flgAdmin sia impostata
        /*if (!isset($_SESSION['flgAdmin'])) {
            // Se non è impostata, recupera l'informazione dal database
            require 'config.php';
            $email = $_SESSION['user'];
            $query = "SELECT flgAdmin FROM utenti WHERE email = '".$mysqli->mysqli_real_escape_string($email)."'";
            $result = $mysqli->query($query);
            if ($result) {
                $row = $result->fetch_assoc();
                $_SESSION['flgAdmin'] = $row['flgAdmin'];
            } else {
                $_SESSION['flgAdmin'] = 0; // Imposta flgAdmin a 0 se non viene trovato
            }
        }*/


	/*  vecchio controllo da RISPE  
	if(!$_SESSION['user'])
	{
		 Header("location: index.php");
		 exit; 
	}
	*/
?>