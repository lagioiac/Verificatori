<?php
// Questo file gestisce il processo di login degli utenti.

// Inizia la sessione - Questo assicura che session_start() venga chiamata solo se una sessione non è già stata avviata.
if (session_status() == PHP_SESSION_NONE) 
{
    session_start();
}
// Potrebbe esserci un errore nel file ‘home.php’ che impedisce al PHP di renderizzare la pagina.
// Verifica se ci sono errori nel file ‘home.php’. Abilita la visualizzazione degli errori in PHP 
// per aiutare a individuare il problema
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Includi il file di configurazione per l'accesso al database
include("config.php");

// Verifica se i dati del modulo di login sono stati inviati
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // Ottieni i valori dei campi di input dal modulo di login
    $email = $_POST['email'];
    $password = $_POST['password'];
	
	// Potrebbe esserci un errore nel file ‘home.php’ che impedisce al PHP di renderizzare la pagina.
	// Verifica se ci sono errori nel file ‘home.php’. Abilita la visualizzazione degli errori in PHP 
	// per aiutare a individuare il problema
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// echo "email : " . $email . "\n";
	// echo "password : " . $password . "\n";
	// echo "Tutto bene? ";
    
	// Esegue una query per verificare se le credenziali dell'utente sono corrette
    $item = $FS->login($email, $password);
	if ($item) 
	{
		foreach ($item as $k => $v) 
		{
			// Se le credenziali sono corrette, imposta le variabili di sessione e reindirizza l'utente alla home
			if ($k != "passwd")
			   $_SESSION["user"]["$k"] = $v;
			// Potrebbe esserci un errore nel file ‘home.php’ che impedisce al PHP di renderizzare la pagina.
			// Verifica se ci sono errori nel file ‘home.php’. Abilita la visualizzazione degli errori in PHP 
			// per aiutare a individuare il problema
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		}
		//$FS->indicatoreLog($_SESSION['user']);    
		ob_end_clean();
		//Header("location: home.php");
		echo "<script>document.location.href='home.php';</script>";

		// Potrebbe esserci un errore nel file ‘home.php’ che impedisce al PHP di renderizzare la pagina.
		// Verifica se ci sono errori nel file ‘home.php’. Abilita la visualizzazione degli errori in PHP 
		// per aiutare a individuare il problema
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		exit;
	}
	// Altrimenti, mostra un messaggio di errore e chiedi di reinserire le credenziali
	else 
	{
		$_SESSION['login-error'] = "<div class='alert alert-error alert-login'>Utente non riconosciuto</div>";
		//Header("location: {$_SERVER['HTTP_REFERER']}");
		echo "<script>document.location.href='".$_SERVER['HTTP_REFERER']."';</script>";
		// Potrebbe esserci un errore nel file ‘home.php’ che impedisce al PHP di renderizzare la pagina.
		// Verifica se ci sono errori nel file ‘home.php’. Abilita la visualizzazione degli errori in PHP 
		// per aiutare a individuare il problema
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		exit;
		exit;
	}
}
?>
