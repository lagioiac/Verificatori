

<?php
include 'config.php';
include 'include/check_user.php';

// Visualizzazione degli errori
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ricordarsi di chudere head derivante dal header.php
include 'include/header.php'; // Chiude il tag <head> aperto in header.php più avanti

include 'db/mysql.php';
require 'class/PersonaleUOTClass.php';
require 'class/QualificheClass.php';
require 'class/RuoloClass.php';

// Inizializza la connessione al database
$db = new DbConnect();
$db->open() or die($db->error());

$PersonaleUOTClass = new PersonaleUOTClass();
$QualificheClass = new QualificheClass();
$ruoloClass = new RuoloClass();

$verificatore = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['searchCognome']) && !empty($_POST['searchCognome'])) {
        $cognome = $_POST['searchCognome'];
        $verificatore = $PersonaleUOTClass->getVerificatoreByCognome($db, $cognome);
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $verificatore = $PersonaleUOTClass->getVerificatoreById($db, $id);
}

// Controllo se l'utente è un amministratore
$isAdmin = ($_SESSION['flgAdmin'] == 1);

// Query per ottenere le UOT
$query_uot = "SELECT IdUot, denominazione FROM uot ORDER BY denominazione";
$result_uot = $db->query($query_uot);
if (!$result_uot) {
    die("Errore nella query UOT: " . $db->error());
}

// Query per ottenere le Qualifiche
$query_qualifica = "SELECT idQualifica, qualifica, flgTipoQualifica FROM qualifica ORDER BY qualifica";
$result_qualifica = $db->query($query_qualifica);
if (!$result_qualifica) {
    die("Errore nella query Qualifica: " . $db->error());
}

// Query per ottenere i Ruoli
$query_ruolo = "SELECT IdRuolo, ruolo FROM ruolo ORDER BY ruolo";
$result_ruolo = $db->query($query_ruolo);
if (!$result_ruolo) {
    die("Errore nella query Ruolo: " . $db->error());
}

?>

</head> <!-- chiusura del head presente in header.php lasciato aperto volutamente -->

<body>
    <div class="container">
        <a href="verificatori.php" class="back">Indietro</a>

        <h1>Aggiungi o Modifica Verificatore</h1>

        <form method="POST" action="aggiungi_verificatore.php">
            <label for="searchCognome">Cognome del Verificatore:</label>
            <input type="text" id="searchCognome" name="searchCognome" required>
            <button type="submit">Cerca</button>
        </form>

        <?php if ($verificatore): ?>
            <div class="verificatore-details">
                <h2>Dettagli Verificatore</h2>
                <form method="POST" action="salva_verificatore.php">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($verificatore['IDXF']) ?>">
                    
                    <label for="cognome">Cognome:</label>
                    <input type="text" id="cognome" name="cognome" value="<?= htmlspecialchars($verificatore['cognome']) ?>" required>
                    
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($verificatore['nome']) ?>" required>

                    <label for="iduot">UOT:</label>
                    <select id="iduot" name="iduot">
                        <option value="">-- Seleziona una UOT --</option>
                        <?php while ($row_uot = $db->fetchassoc($result_uot)) {
                            $selected = ($row_uot['IdUot'] == $verificatore['iduot']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row_uot['IdUot']) . "' $selected>" . htmlspecialchars($row_uot['denominazione']) . "</option>";
                        } ?>
                    </select>

                    <label for="idqualifica">Qualifica:</label>
                    <select id="idqualifica" name="idqualifica">
                        <option value="">-- Seleziona una Qualifica --</option>
                        <?php while ($row_qualifica = $db->fetchassoc($result_qualifica)) {
                            $tipoQualifica = '';
                            if ($row_qualifica['flgTipoQualifica'] == 'A') {
                                $tipoQualifica = ' (Amministrativo)';
                            } elseif ($row_qualifica['flgTipoQualifica'] == 'T') {
                                $tipoQualifica = ' (Tecnico)';
                            }
                            $selected = ($row_qualifica['idQualifica'] == $verificatore['idqualifica']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row_qualifica['idQualifica']) . "' $selected>" . htmlspecialchars($row_qualifica['qualifica'] . $tipoQualifica) . "</option>";
                        } ?>
                    </select>

                    <label for="cell">Cellulare:</label>
                    <input type="text" id="cell" name="cell" value="<?= htmlspecialchars($verificatore['cell']) ?>">

                    <label for="telfisso">Telefono Fisso:</label>
                    <input type="text" id="telfisso" name="telfisso" value="<?= htmlspecialchars($verificatore['telfisso']) ?>">

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($verificatore['email']) ?>">

                    <label>Competenze:</label>
                    <div>
                        <input type="checkbox" id="flgP" name="flgP" value="1" <?= ($verificatore['flgP'] == 1) ? 'checked' : '' ?>>
                        <label for="flgP">Pressione</label>
                    </div>
                    <div>
                        <input type="checkbox" id="flgS" name="flgS" value="1" <?= ($verificatore['flgS'] == 1) ? 'checked' : '' ?>>
                        <label for="flgS">Sollevamento</label>
                    </div>
                    <div>
                        <input type="checkbox" id="flgR" name="flgR" value="1" <?= ($verificatore['flgR'] == 1) ? 'checked' : '' ?>>
                        <label for="flgR">Riscaldamento</label>
                    </div>
                    <div>
                        <input type="checkbox" id="flgT" name="flgT" value="1" <?= ($verificatore['flgT'] == 1) ? 'checked' : '' ?>>
                        <label for="flgT">Terra</label>
                    </div>

                    <label for="flgAltreUot">Disponibile per altre UOT:</label>
                    <input type="checkbox" id="flgAltreUot" name="flgAltreUot" value="1" <?= ($verificatore['flgAltreUot'] == 1) ? 'checked' : '' ?>>

                    <label for="disponibile">Disponibilità:</label>
                    <select id="disponibile" name="disponibile">
                        <option value="">-- Seleziona Disponibilità --</option>
                        <option value="P" <?= ($verificatore['disponibile'] == 'P') ? 'selected' : '' ?>>Presenza</option>
                        <option value="R" <?= ($verificatore['disponibile'] == 'R') ? 'selected' : '' ?>>Remoto</option>
                        <option value="X" <?= ($verificatore['disponibile'] == 'X') ? 'selected' : '' ?>>Presenza o Remoto</option>
                    </select>

                    <label for="note">Note:</label>
                    <textarea id="note" name="note"><?= htmlspecialchars($verificatore['note']) ?></textarea>

                    <label for="idruolo">Ruolo:</label>
                    <select id="idruolo" name="idruolo">
                        <option value="">-- Seleziona un Ruolo --</option>
                        <?php while ($row_ruolo = $db->fetchassoc($result_ruolo)) {
                            $selected = ($row_ruolo['IdRuolo'] == $verificatore['idruolo']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row_ruolo['IdRuolo']) . "' $selected>" . htmlspecialchars($row_ruolo['ruolo']) . "</option>";
                        } ?>
                    </select>

                    <?php if ($isAdmin): ?>
                        <button type="submit">Salva</button>
                    <?php endif; ?>
                </form>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>Nessun verificatore trovato con il cognome inserito.</p>
        <?php endif; ?>

        <?php if ($isAdmin && !$verificatore): ?>
            <a href="inserisci_verificatore.php" class="button">Inserisci Nuovo Verificatore</a>
        <?php endif; ?>
    </div>

<?php include 'include/footer.php'; ?>

</body>
</html>
