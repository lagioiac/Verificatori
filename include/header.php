
<!-- aggiunta di Aldo -->
<?php
// Inizia la sessione - Questo assicura che session_start() venga chiamata solo se una sessione non è già stata avviata.
if (session_status() == PHP_SESSION_NONE) 
{
    session_start();
}

// Determina la pagina attiva
$current_page = basename($_SERVER['PHP_SELF']);

?>
<!-- fine aggiunta di Aldo -->

<!DOCTYPE html>

<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<html lang="it">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Verificatori</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="robots" content="index,follow">
	<meta name="author" content="Team Informatico del DIT">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/style.css">
	<!--[if IE 8]><link rel="stylesheet" type="text/css" href="css/ie8.css"></link><![endif]-->
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/modernizr.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type='text/javascript' src='js/jquery.cookie.js'></script>
	<script type='text/javascript' src='js/jquery.hoverIntent.minified.js'></script>
	<script type='text/javascript' src='js/jquery.dcjqaccordion.2.7.min.js'></script>

	<!-- inizio codice aggiunto aldo -->
	
	<script type='text/javascript' src='js/jquery.validate.min.js'></script>	
	
	<!-- fine codice aggiunto aldo -->		
	
	<script type="text/javascript" src="js/function.js"></script>

	<style>
		.spacer 
		{
		height: 20px; /* Spazio vuoto da poter mettere prima della/e scritta/e negli header  */
		}
	</style>

	<div class="container">								
		<div class="row spacer"></div> <!-- Riga vuota sopra - aldo -->
		<div class="row">
			<div class="span12">
				<div class="logo">
					<!-- Aggiunta della scritta "DIT Coordinamento UOT" con lo stile richiesto - aldo -->
					<span style="display: block; font-weight: bold; font-size: 2em;">DIT</span>
					<span style="display: block; font-weight: bold; font-size: 1em; font-style: italic;">Coordinamento UOT</span>
					<span style="float: right; font-weight: bold; font-size: 1em; font-style: italic;">VERIFICATORI 1.0</span>
				</div>
				
				<div class="row spacer"></div> <!-- Riga vuota sotto - aldo -->
				
				<!-- Logo e Versione dell'applicativo - aldo -->
				<div class="logo">
				
					<!-- Nome dell'applicativo e Versione dell'applicativo da mettere in alto, 
						 sopra la barra blu... - aldo --> 
						 
					<!-- <span style="float: right; font-weight: bold; font-style: italic;">VERIFICATORI 1.0</span> 
						 <span style="float: right; color: blue; font-style: italic;">1.0</span> -->
					
					
				
				</div>
				
				<!-- Barra di navigazione blu - aldo -->
				<!-- vecchia impostazione iniziale 
				<div style="background-color: #002e5f; color: white; padding: 10px 0;">
					<div style="float: left;"> -->
				<div class="nav-container">
					<!-- <div class="nav-left"> -->
						<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
							<!-- Menu di navigazione, visibile solo se l'utente è loggato - aldo -->
							<!-- <nav class="navbar-menu"> -->
								<ul class="navbar-menu">
								<!-- <ul style="list-style: none; margin: 0; padding: 0;"> -->

									<!-- Menu sulla barra blu stile INAIL -->
									<li><a href="home.php" class="<?php echo $current_page == 'home.php' ? 'active' : ''; ?>">Home</a></li> 
									<li><a href="verificatori.php" class="<?php echo $current_page == 'verificatori.php' ? 'active' : ''; ?>">Personale UOT</a></li>
									<li><a href="uot.php" class="<?php echo $current_page == 'uot.php' ? 'active' : ''; ?>">UOT</a></li>
									<li><a href="stabilimenti.php" class="<?php echo $current_page == 'stabilimenti.php' ? 'active' : ''; ?>">Siti/Aziende</a></li>
									<li><a href="verifiche.php" class="<?php echo $current_page == 'verifiche.php' ? 'active' : ''; ?>">Verifiche</a></li>
									<li><a href="pianificazione.php" class="<?php echo $current_page == 'pianificazione.php' ? 'active' : ''; ?>">Pianificazione</a></li>
							
									<!-- prova 2 - aldo ...
									<li style="display: inline; margin-right: 10px;"><a href="home.php" class="<?php echo $current_page == 'home.php' ? 'active' : ''; ?>">Home</a></li>
									<li style="display: inline; margin-right: 10px;"><a href="verificatori.php" class="<?php echo $current_page == 'verificatori.php' ? 'active' : ''; ?>">Verificatori</a></li>
									<li style="display: inline; margin-right: 10px;"><a href="uot.php" class="<?php echo $current_page == 'uot.php' ? 'active' : ''; ?>">UOT</a></li>
									<li style="display: inline; margin-right: 10px;"><a href="stabilimenti.php" class="<?php echo $current_page == 'stabilimenti.php' ? 'active' : ''; ?>">Stabilimenti</a></li>
									<li style="display: inline; margin-right: 10px;"><a href="verifiche.php" class="<?php echo $current_page == 'verifiche.php' ? 'active' : ''; ?>">Verifiche</a></li>
									<li style="display: inline; margin-right: 10px;"><a href="pianificazione.php" class="<?php echo $current_page == 'pianificazione.php' ? 'active' : ''; ?>">Pianificazione</a></li>
									--> 
									
									<!-- prova 1 - aldo ...
									<li style="display: inline; margin-right: 10px;"><a href="home.php" style="color: white;">Home</a></li> 
									<li style="display: inline; margin-right: 10px;"><a href="verificatori.php" style="color: white;">Verificatori</a></li>
									<li style="display: inline; margin-right: 10px;"><a href="uot.php" style="color: white;">UOT</a></li>
									<li style="display: inline; margin-right: 10px;"><a href="stabilimenti.php" style="color: white;">Stabilimenti</a></li>
									<li style="display: inline; margin-right: 10px;"><a href="verifiche.php" style="color: white;">Verifiche</a></li>
									<li style="display: inline; margin-right: 10px;"><a href="pianificazione.php" style="color: white;">Pianificazione</a></li>
									-->
								</ul>
							<!-- </nav> -->
						<?php endif; ?>
					<!-- </div> -->
					<!-- vecchia impostazione 
					<div style="float: right;"> -->
					<div class="nav-right">
						<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): {?>
							<!-- Link per il logout, visibile solo se l'utente è loggato -->
							<a href="logout.php" class="login-logout">ESCI</a>
  
							<!-- rappresentazione 1 Aldo...
							<a href="logout.php" style="background-color: #1E6665; color: white; padding: 5px 10px; text-decoration: none;">Logout</a>
							-->
						<?php } else: ?>
							<!-- Link per il login, visibile solo se l'utente non è loggato -->
							<a href="login.php" class="login-logout">ACCEDI AL SERVIZIO</a>
							<!-- rappresentazione 1 Aldo...
							<a href="login.php" style="background-color: #1E6665; color: white; padding: 5px 10px; text-decoration: none;">Login</a>
							-->
						<?php endif; ?>
					</div>
					<div class="clear"></div> <!-- Pulisce i float -->
					<!-- istruzione di pulizia precedente prova 1 
					<div style="clear: both;"></div> --> 
                                        <!-- ***************** Inizio Barra Verde con Nome Utente ***************** -->

				</div>
                                <!-- Fine della barra di navigazione blu/verde -->
                                
                                <!-- Barra verde con nome utente, visibile solo se l'utente è loggato -->
                                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                                   <div class="green-bar">
                                       <div class="green-bar-left">
                                           <?php echo htmlspecialchars($_SESSION['user']); ?>
                                       </div>
                                       <div class="green-bar-right">
                                           <a href="home.php">
                                                <img src="img/MyHome.png" alt="Home" class="icon-home">
                                                My Home
                                            </a>
                                       </div>
                                   </div>   
                                <?php endif; ?>
                       
                                
				<!-- riga di codice da mettere se si toglie il menu "dati criteri" presente nella home.php
					 conviene per lasciare spazio dopo la barra blu di navigazione ... Aldo
				<div class="row spacer"></div> <!-- Riga vuota sopra - aldo -->
			</div>	
		</div>
	</div>






    