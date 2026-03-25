<?php
include 'config.php';
include 'include/check_user.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ricordarsi di chudere head derivante dal header.php
include 'include/header.php'; // Chiude il tag <head> aperto in header.php più avanti

include 'db/mysql.php';
require 'class/PersonaleUOTClass.php';
require 'class/QualificheClass.php';
require 'class/RuoloClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$personaleUOTClass = new PersonaleUOTClass();

/* session_start(); */
$isAdmin = isset($_SESSION['flgAdmin']) && $_SESSION['flgAdmin'] == 1;

if (!$isAdmin) {
    die("Accesso non autorizzato.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post = array(
        'cognome' => $_POST['cognome'],
        'nome' => $_POST['nome'],
        'iduot' => $_POST['iduot'],
        'idqualifica' => $_POST['idqualifica'],
        'cell' => $_POST['cell'],
        'telfisso' => $_POST['telfisso'],
        'email' => $_POST['email'],
        'flgP' => isset($_POST['flgP']) ? 1 : 0,
        'flgS' => isset($_POST['flgS']) ? 1 : 0,
        'flgR' => isset($_POST['flgR']) ? 1 : 0,
        'flgT' => isset($_POST['flgT']) ? 1 : 0,
        'flgAltreUot' => isset($_POST['flgAltreUot']) ? 1 : 0,
        'disponibile' => $_POST['disponibile'],
        'note' => $_POST['note'],
        'idruolo' => $_POST['idruolo']
    );

    $result = $personaleUOTClass->insertPersonaleUOT($db, $post);

    if ($result) {
        header("Location: verificatori.php?succes");
        exit();
    } else {
        echo "Errore nell'inserimento del verificatore.";
    }
} else {
    // Recupera le opzioni per i dropdown
    $uots = $db->query("SELECT IdUot, denominazione FROM uot ORDER BY denominazione");
    $qualifiche = $db->query("SELECT idQualifica, qualifica FROM qualifica ORDER BY qualifica");
    $ruoli = $db->query("SELECT IdRuolo, ruolo FROM ruolo ORDER BY ruolo");

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Inserisci Verificatore</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
    <div class="container">
        <h1>Inserisci Nuovo Verificatore</h1>
        <form method="POST" action="inserisci_verificatore.php">
            <label for="cognome">*Cognome:</label>
            <input type="text" id="cognome" name="cognome" required>
            <br>
            <label for="nome">*Nome:</label>
            <input type="text" id="nome" name="nome" required>
            <br>
            
            <label for="iduot">*UOT:</label>
            <select id="searchuot" name="searchuot">
                <option value="">-- Seleziona una UOT --</option>
                <?php
                $query_uot = "SELECT IdUot, denominazione FROM uot ORDER BY denominazione";
                $result_uot = $db->query($query_uot);
                if (!$result_uot) {
                    die("Errore nella query UOT: " . $db->error());
                }
                while ($row_uot = $db->fetchassoc($result_uot)) {
                    $selected = isset($_POST['$searchuot']) && $_POST['$searchuot'] == $row_uot['IdUot'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row_uot['IdUot']) . "' $selected>" . htmlspecialchars($row_uot['denominazione']) . "</option>";
                } ?>
            </select>
            <br>
            
            <label for="idqualifica">*Qualifica:</label>
            <select id="searchqualifica" name="searchqualifica">
                <option value="">-- Seleziona una Qualifica --</option>
                <?php
                $query_qualifica = "SELECT idQualifica, qualifica FROM qualifica ORDER BY idQualifica";
                $result_qualifica = $db->query($query_qualifica);
                if (!$result_qualifica) {
                    die("Errore nella query QUALIFICA: " . $db->error());
                }
                while ($row_qualifica = $db->fetchassoc($result_qualifica)) {
                    $selected = isset($_POST['$searchqualifica']) && $_POST['$searchqualifica'] == $row_qualifica['idQualifica'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row_qualifica['idQualifica']) . "' $selected>" . htmlspecialchars($row_qualifica['qualifica']) . "</option>";
                } ?>
            </select>            
            <br>
            
            <label for="cell">*Cell:</label>
            <input type="text" id="cell" name="cell">
            <br>
            
            <label for="telfisso">Tel Fisso:</label>
            <input type="text" id="telfisso" name="telfisso">
            <br>
            
            <label for="email">*Email:</label>
            <input type="email" id="email" name="email">
            <br>
            
            <br>
            <label for="flgP">Indica le eventuali competenze tra:</label>
            <label for="flgP">PRESSIONE </label>
            <input type="checkbox" id="flgP" name="flgP">
            <br> <br>
            <label for="flgS">SOLLEVAMENTO </label>
            <input type="checkbox" id="flgS" name="flgS">
            <br><br>
            <label for="flgR">RISCALDAMENTO </label>
            <input type="checkbox" id="flgR" name="flgR">
            <br><br>
            <label for="flgT">TERRA </label>
            <input type="checkbox" id="flgT" name="flgT">
            <br> <br>
            
            <label for="flgAltreUot">Spunta se il dipendente è disponibile a prestare la sua attività presso altre UOT:</label>
            <input type="checkbox" id="flgAltreUot" name="flgAltreUot">
            <br><br>
            
            <label for="disponibile">Disponibilità:</label>
            <select id="disponibile" name="disponibile" required>
                <option value="">Nessuna selezione</option>
                <option value="P">PRESENZA</option>
                <option value="R">REMOTO</option>
                <option value="X">PRESENZA O REMOTO</option>    
            </select>
            <br><br>
            
            <label for="note">Note:</label>
            <textarea id="note" name="note"></textarea>
            <br>

            <br>
            <button type="submit">Salva</button>
        </form>
        <a href="verificatori.php" class="button">Indietro</a>
    </div>

    <?php include 'include/footer.php'; ?>
    </body>
    </html>
    <?php
}
?>


