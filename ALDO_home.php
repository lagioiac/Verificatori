<?php 

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

include("config.php"); 
$pageName = "HOME"; // per tenere traccia della navigazione - pagina HOME 
include("include/check_user.php"); // Include il file per il controllo dell'utente loggato 
include("include/header.php"); // Include il file header.php per il layout della pagina 

?>


<body>
<header>
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="logo">Verificatori</div>
                <div class="info"> 
                    <a href="indicatori.php?an=0"><img src="img/kpi2.png" alt="icon"></a>
                    <a href="dati.php">Dati</a>
                    <a href="criteri.php">Criteri</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>

<section id="home">
    <div class="container">
        <div class="row">
			<!-- Presentazione voci al centro della pagina -->
            <a href="verificatori.php" class="span6">
                <span><img src="img/home-verificatore.png" alt="icon"></span>
                <p>Verificatori</p> <!-- Testo del tasto -->
            </a>
            <a href="uot.php" class="span6">
                <span><img src="img/home-uot.png" alt="icon"></span> 
                <p>UOT</p> <!-- Testo del tasto -->
            </a>
            <a href="stabilimenti.php" class="span6">
                <span><img src="img/home-stabilimenti.png" alt="icon"></span>
                <p>Stabilimenti</p> <!-- Testo del tasto -->
            </a>
            <a href="verifiche.php" class="span6">
                <span><img src="img/home-verifiche.png" alt="icon"></span>
                <p>Verifiche</p> <!-- Testo del tasto -->
            </a>
            <a href="pianificazione.php" class="span6">
                <span><img src="img/home-pianificazione.png" alt="icon"></span>
                <p>Pianificazione</p> <!-- Testo del tasto -->
            </a>
        </div>
    </div>
</section>

<?php include 'include/footer.php'; // Include il file footer.php per il layout della pagina ?>

</body>
</html>
