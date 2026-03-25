<?php

include("config.php");
ob_start();
$op = $_REQUEST['op'];
$now = getdate();
$UT = new Utility();

// unset($_SESSION['login-error']);
// Simulazione autenticazione utente
$email = $_POST['email']; // Assumendo che l'email sia usata per l'autenticazione
$password = $_POST['password']; // Assumendo che la password sia usata per l'autenticazione

// echo "email : " . $email . "\n";
// echo "password : " . $password . "\n";
// echo "Tutto bene? ";
$item = $FS->login($email, $password);

// inserire messaggio di errore

//Controllo delle credenziali
if ($item) //se corrette...
{
	$_SESSION['user'] = $email; // Assumendo che $email sia l'utente autenticato
	
	/* foreach ($item as $k => $v) 
	{
		if ($k != "passwd")
		$_SESSION["user"]["$k"] = $v;
	}*/
	
	//$FS->indicatoreLog($_SESSION['user']);    
	ob_end_clean();
	// Header("location: home.php");
	// carica la pagina home se le credenziali sono corrette dopo aver assegnato true alla variabile di sessione
	$_SESSION['loggedin'] = true;
	echo "<script>document.location.href='home.php';</script>";
	
	// Aggiunta di Aldo - inizio
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true)
	{
		// Reindirizza l'utente alla pagina originale se esiste, altrimenti alla home
		$redirect_to = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'home.php';
		unset($_SESSION['redirect_to']);
		header("Location: " . $redirect_to);
		 
	}
	// fine aggiunta di Aldo
	exit;
}
else 
{
	// Se le credenziali non sono valide, reindirizza al login con un messaggio di errore
	$_SESSION['login-error'] = "<div class='alert alert-error alert-login'>Utente non riconosciuto</div>";
	//Header("location: {$_SERVER['HTTP_REFERER']}");
	header("Location: index.php?error=invalid_credentials");
	echo "<script>document.location.href='".$_SERVER['HTTP_REFERER']."';</script>";
	exit;
}
// fine controllo credenziali


// da verificare cosa fa lo switch
switch ($op) 
{
	case 6:
	$tipo = $_GET['tipo'];
	$q = $_GET['term'];
	$item = $FS->get_json($q, $tipo);
	if ($item) 
	{
		$vet = array();
		foreach ($item as $k => $val) 
		{
			$vet[] = '{"id":"' . $k . '","label":"' . $val . '", "value":"' . $val . '"}';
		}
		$product = implode(",", $vet);
		echo"[" . $product . "]";
	}
	break;
	
	case 1:	//restituisce il nome della regione dal nome della provincia
		$id = (int) $_POST('id');
		$item = $FS->get_regione($id);
		if ($item) {
			print_r($item['nomeregione']);
		}
		break;
}

?>
