<?php
include 'config.php'; // Include la configurazione prima di qualsiasi altro codice
include 'include/check_user.php';

// ricordarsi di chudere head derivante dal header.php
include 'include/header.php'; // Chiude il tag <head> aperto in header.php più avanti

include 'db/mysql.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = new DbConnect();
$db->open() or die($db->error());

require 'class/PersonaleUOTClass.php';
require 'class/QualificheClass.php';
require 'class/RuoloClass.php';

$personaleUOTClass = new PersonaleUOTClass();
$qualificheClass = new QualificheClass();
$ruoloClass = new RuoloClass();

$isAdmin = isset($_SESSION['flgAdmin']) && $_SESSION['flgAdmin'] == 1;

$verificatore = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cognome'])) {
    $verificatore = $personaleUOTClass->getVerificatoreByCognome($db, $_POST['cognome']);
} elseif (isset($_GET['id'])) {
    $verificatore = $personaleUOTClass->getVerificatoreById($db, $_GET['id']);
}

// Recupera le opzioni per i dropdown
$uots = $db->query("SELECT IdUot, denominazione FROM uot ORDER BY denominazione");
$qualifiche = $db->query("SELECT idQualifica, qualifica FROM qualifica ORDER BY qualifica");
$ruoli = $db->query("SELECT IdRuolo, ruolo FROM ruolo ORDER BY ruolo");


?>
</head>
<body>
<div class="container">
    
    <br>
    <!-- Tasto per tornare indietro -->
    <a href="verificatori.php" class="back">Indietro</a>
    <br>
    
    <h1>Aggiungi* o Modifica* Verificatore</h1><br>
    <form method="POST" action="aggiungi_verificatore.php">
        <label for="cognome">Inserisci il Cognome da cercare:</label>
        
        
        <input type="text" id="cognome" name="cognome" placeholder="Cognome" value="<?= isset($_POST['cognome']) ? htmlspecialchars($_POST['cognome']) : '' ?>">
        
        
        
        <button type="submit" class="button">Cerca</button>
    </form>
   
    <?php if ($verificatore): ?>
        <div class="verificatore-details">
            <form method="POST" action="salva_verificatore.php">
                <input type="hidden" name="IDXF" value="<?= $verificatore['IDXF'] ?>">
                <label>Cognome:</label>
                <span><?= htmlspecialchars($verificatore['cognome']) ?></span>
                <br>
                <br><label>Nome:</label>
                <span><?= htmlspecialchars($verificatore['nome']) ?></span>
                <br>

                <!-- *** inizio modifica: recupera denominazione UOT *** -->
                <br><label>Sede di afferenza:</label>
                <span>
                    <?php
                    $uotQuery = $db->query("SELECT denominazione FROM uot WHERE IdUot = " . $verificatore['iduot']);
                    $uotResult = $db->fetchassoc($uotQuery);
                    echo htmlspecialchars($uotResult['denominazione']);
                    ?>
                </span>
                <br>
                <!-- *** fine modifica *** -->
             
                <!-- *** inizio modifica: recupera qualifica *** -->
                <br><label>Qualifica:</label>
                <span>
                    <?php
                    $qualificaQuery = $db->query("SELECT qualifica FROM qualifica WHERE idQualifica = " . $verificatore['idqualifica']);
                    $qualificaResult = $db->fetchassoc($qualificaQuery);
                    echo htmlspecialchars($qualificaResult['qualifica']);
                    ?>
                </span>
                <br>
                <!-- *** fine modifica *** -->
                
                <br><label>Cell:</label>
                <span><?= htmlspecialchars($verificatore['cell']) ?></span>
                <br>
                <br><label>Tel Fisso:</label>
                <span><?= htmlspecialchars($verificatore['telfisso']) ?></span>
                <br>
                <br><label>Email:</label>
                <span><?= htmlspecialchars($verificatore['email']) ?></span>
                <br>
                <br><label>Competenza da Verificatore:</label>
                <span>
                    <?php $flgCompetenza = 0;?>
                    <?= $verificatore['flgP'] ? 'Pressione ' : '' ; $flgCompetenza = 1 ?>
                    <?= $verificatore['flgS'] ? 'Sollevamento ' : ''; $flgCompetenza = 1  ?>
                    <?= $verificatore['flgR'] ? 'Riscaldamento ' : ''; $flgCompetenza = 1  ?>
                    <?= $verificatore['flgT'] ? 'Terra ' : ''; $flgCompetenza = 1  ?>
                    <?php if (!$flgCompetenza) ?>
                        <label>Nessuna competenza</label>
                </span>
                <br>
                <br><label>Disponibilità ad esercitare presso altra UOT:</label>
                <?php if ($verificatore['flgAltreUot']) { ?>
                    <span>
                        <?php
                        if ($verificatore['disponibile'] == 'P') {
                            echo 'solo in PRESENZA';
                        } elseif ($verificatore['disponibile'] == 'R') {
                            echo 'solo da REMOTO';
                        } elseif ($verificatore['disponibile'] == 'X') {
                            echo 'sia in PRESENZA che da REMOTO';
                        }
                        else 
                            echo 'Nessuna disponibilità';
                        ?>
                    </span>
                <?php } else 
                    echo 'Nessuna disponibilità';
                ?>
                <br>
                <br><label>Note:</label>
                <?php if (htmlspecialchars($verificatore['note'])) { ?>
                    <span><?= htmlspecialchars($verificatore['note']) ?></span>
                <?php } else 
                    echo 'Nulla da segnalare';
                ?>
                <br>
                               
                <!-- *** inizio modifica: recupera ruolo *** -->
                <!--
                <br><label>Ruolo:</label>
                <span>
                    <?php /*
                    $ruoloQuery = $db->query("SELECT ruolo FROM ruolo WHERE IdRuolo = " . $verificatore['idruolo']);
                    $ruoloResult = $db->fetchassoc($ruoloQuery);
                    echo htmlspecialchars($ruoloResult['ruolo']);
                    */ ?>
                </span>
                <br>-->
                <!-- *** fine modifica *** -->
     
                <?php if ($isAdmin): ?>
                    <button type="button" onclick="abilitaModifica()">Modifica</button>
                    <button type="submit" style="display:none;" id="salvaButton">Salva</button>
                <?php endif; ?>
            </form>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p>Nessun verificatore trovato con il cognome inserito.</p>
    <?php endif; ?>

    <?php if ($isAdmin && !$verificatore): ?>
        <br>
        <a href="inserisci_verificatore.php" class="button"> Inserisci Nuovo Verificatore </a>
    <?php else: ?> 
        <br><label>*N.B. Soltanto chi preventivamente autorizzato può eseguire un inserimento o una modifica nel DB </label>
    <?php endif; ?>
</div>

<?php include 'include/footer.php'; ?>

<script>
function abilitaModifica() {
    const elements = document.querySelectorAll('.verificatore-details span');
    elements.forEach(element => {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = element.previousElementSibling.innerText.toLowerCase();
        input.value = element.innerText.trim();
        element.parentElement.replaceChild(input, element);
    });

    document.getElementById('salvaButton').style.display = 'inline';
}
</script>

</body>
</html>



