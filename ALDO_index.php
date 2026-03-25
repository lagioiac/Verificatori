<?php
// Inizia la sessione - Questo assicura che session_start() 
// venga chiamata solo se una sessione non è già stata avviata.
// le sessioni vengono salvate nella cartella "tmp" presente nella root di installazione 
// per la VM022 in "C:\xampp\tmp" info prese dal file php.ini che si trova sotto la root "C:\xampp\"
// le sessioni rimangono attive per 24 minuti (1440 secondi) 
// 
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
// Controlla se l'utente è già loggato
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) 
{
    $loggedIn = true;
} 
else 
{
    $loggedIn = false;
}

?>

<!DOCTYPE html>
<html lang="it"> <!-- Imposta la lingua della pagina a Italiano -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <!-- Specifica la codifica dei caratteri -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Imposta la viewport per dispositivi mobili -->
        <title>Verificatori</title> <!-- Titolo della scheda nel browser -->
        <!-- Inclusione dei file CSS -->
        <link rel="stylesheet" href="css/bootstrap.css"> 
        <link rel="stylesheet" href="css/bootstrap-responsive.css">
        <link rel="stylesheet" href="css/style.css">
        <!-- Inclusione dei file JavaScript -->
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/modernizr.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type='text/javascript' src='js/jquery.cookie.js'></script>
        <script type='text/javascript' src='js/jquery.hoverIntent.minified.js'></script>
        <script type='text/javascript' src='js/jquery.dcjqaccordion.2.7.min.js'></script>
        <script type='text/javascript' src='js/jquery.validate.min.js'></script>
        <script type="text/javascript" src="js/function.js"></script>
    </head>
    <body>
		<!-- Nel precedente codice l'inclusione dell'header veniva fatto qui con la successiva chiusura del "head" -->
		<?php include ("include/header.php"); ?> <!-- Inclusione dell'header -->
		<!-- Nel precedente codice er necessario mettere la chiusura del "head", in quanto non era chiuso volutamente, per altre ragioni, nel header.php -->
			
        <!-- parte commentata perchè inserita nel header.php
			 commenti per le righe presenti nel codice a seguire	
				class="row" indica la Riga per il layout a griglia 
				class="span12" indica la Colonna che occupa tutta la larghezza span12
				class="logo login"><i>VERIFICATORI 1.0</i> indica il titolo dell'applicazione

		inizio codice commentato 		
		
		<header> 
            <div class="container"> 
                <div class="row"> 
                    <div class="span12"> 
                        <div class="logo login"><i>VERIFICATORI 1.0</i></div> 
                    </div>
                </div>
            </div>
        </header>
		
		fine parte commentata --> 			
		
		<!-- parte di codice modificata dal vecchio php team_index.php -->
		<section id="login">
            <div class="container">
            <?php 
            if(isset($_SESSION['login-error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['login-error'] . '</div>'; // Visualizza l'errore di login se esiste
            }
			?>
            
		            
            <div class="box"> <!-- Contenitore per il form di login -->
                <div class="title">GESTIONE VERIFICHE</div> <!-- Titolo della sezione di login -->
                <div class="content"> <!-- Contenuto del form -->
					<!-- nel precedente codice si usava il metodo POST con il file controllers.php, adesso con login.php --> 
                    <form method="POST" action="login.php" id="login-form" class="form-validate"> <!-- Form di login -->
                        <input type="hidden" name="op" value="4" /> <!-- Input nascosto per l'operazione di login -->
						<div class="control-group">
						<label for="email" class="control-label">Email</label>
						<div class="controls">
							<!-- Campo per l'email -->
							<input data-rule-required="true" data-rule-email="false" name="email" type="text" value="" onfocus="if(this.value == this.defaultValue) { this.value = ''; }" onblur="if (this.value == '') { this.value = this.defaultValue;}"> 
						</div>
						</div>	
						<div class="control-group">
							<label for="password" class="control-label">Password</label>
							<div class="controls">
								<!-- Campo per la password -->
								<input data-rule-required="true" name="password" type="password" value="" onfocus="if(this.value == this.defaultValue) { this.value = ''; }" onblur="if (this.value == '') { this.value = this.defaultValue;}"> 
							</div>
						</div>	
                        <button type="submit">ACCEDI</button> <!-- Bottone di submit -->
					</form>
				<! <a href="#" class="setpass"> <! Dimenticato la password?</a> <!-- Link per il recupero della password (commentato) -->
                </div>
            </div>
        </section>
		
        <?php
				
		// Potrebbe esserci un errore nel file ‘home.php’ che impedisce al PHP di renderizzare la pagina.
		// Verifica se ci sono errori nel file ‘home.php’. Abilita la visualizzazione degli errori in PHP 
		// per aiutare a individuare il problema
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		
        // Altri eventuali codici PHP
        ?>

		<script type="text/javascript">
		$(document).ready(function()
		{
			// Mostra il popup per il recupero della password
			$('.setpass').click(function() 
			{
				$('.modale.setpass').fadeIn('fast')
			});
		  	
		  	// Chiude il popup per il recupero della password
		  	$(".closepopuppass").click(function() 
			{
				$(this).parent().parent().fadeOut();
				return false;
		  	});
		});
		</script>
    </body>
</html>
