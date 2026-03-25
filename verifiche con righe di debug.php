<?php // Inizio PHP
include 'config.php';
include 'db/mysql.php';
include 'class/PersonaleUOTClass.php';
include 'class/QualificheClass.php';
include 'class/RuoloClass.php';
include 'include/check_user.php';

// ricordarsi di chudere head derivante dal header.php
include 'include/header.php'; 


ob_start();
$pageMenu = "verifiche";
$pageName = $current_page;

$db = new DbConnect();
$db->open() or die($db->error());



// Debug: Verifica se la connessione al database è aperta
if ($db->connect_errno) {
    die("Failed to connect to MySQL: " . $db->connect_error);
} else {
    echo "Connessione al DB aperta con successo.<br>";
}


// Query per ottenere tutti i codici ATECO
$query_ateco = "SELECT idAttivita, attivita FROM attivita ORDER BY idAttivita";
$result_ateco = $db->query($query_ateco);


// Debug: Verifica se la query sui codici ATECO ha restituito risultati
if (!$result_ateco) {
    die("Query sui codici ATECO fallita: " . $db->error);
} else {
    echo "Query sui codici ATECO eseguita con successo.<br>";
}




$filter_applied = false; // Flag per controllare se un filtro è stato applicato

$query = "SELECT verifiche.*, statoverifica.stato, statoverifica.iconastato FROM verifiche 
          LEFT JOIN statoverifica ON verifiche.idstatoverifica = statoverifica.IdStato
          WHERE 1";

if (!empty($_POST['search_codice_ateco'])) {
    $query .= " AND idattivita = '" . $db->mysqli_real_escape_string($_POST['search_codice_ateco']) . "'";
    $filter_applied = true;
}
if (!empty($_POST['search_stato_verifica'])) {
    $query .= " AND statoverifica.stato LIKE '%" . $db->mysqli_real_escape_string($_POST['search_stato_verifica']) . "%'";
    $filter_applied = true;
}
if (!empty($_POST['search_uot'])) {
    $query .= " AND iduot = '" . $db->mysqli_real_escape_string($_POST['search_uot']) . "'";
    $filter_applied = true;
}
if (!empty($_POST['search_verificatore'])) {
    $query .= " AND idverificatore = '" . $db->mysqli_real_escape_string($_POST['search_verificatore']) . "'";
    $filter_applied = true;
}
if (!empty($_POST['search_regione'])) {
    $query .= " AND regione LIKE '%" . $db->mysqli_real_escape_string($_POST['search_regione']) . "%'";
    $filter_applied = true;
}

$result = $db->query($query);



// Debug: Verifica se la query sulle verifiche ha restituito risultati
if (!$result) {
    die("Query sulle verifiche fallita: " . $db->error);
} else {
    echo "Query sulle verifiche eseguita con successo.<br>";
}



$totalVerifiche = $db->num_rows($result);
?>




<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifiche</title>
    <link rel="stylesheet" href="css/style.css">
</head>

</head> <!-- chiusura sezione head presente dentro il file header.php -->

<body>
<div class="container">
    <h1 style="text-align: center; font-size: 2em; font-weight: bold;">Verifiche</h1>
    
    <!-- Modulo di Ricerca -->
    <form method="POST" action="verifiche.php">
        <select name="search_codice_ateco">
            <option value="">Seleziona un Codice ATECO</option>
            <?php while ($ateco = $db->fetchassoc($result_ateco)) { ?>
                <option value="<?= htmlspecialchars($ateco['idAttivita']) ?>" <?= isset($_POST['search_codice_ateco']) && $_POST['search_codice_ateco'] == $ateco['idAttivita'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ateco['idAttivita']) ?> - <?= htmlspecialchars($ateco['attivita']) ?>
                </option>
            <?php } ?>
        </select>            
        <input type="text" name="search_stato_verifica" placeholder="Cerca per stato della verifica">
        <input type="text" name="search_uot" placeholder="Cerca per UOT">
        <input type="text" name="search_verificatore" placeholder="Cerca per verificatore">
        <input type="text" name="search_regione" placeholder="Cerca per regione">
        <button type="submit">Cerca</button>
    </form>

    <!-- Contatore Verifiche -->
    <h2 style="text-align: center; font-size: 1.5em; font-weight: bold;">
        <?= $filter_applied ? "Totale verifiche da filtro applicato: " : "Totale verifiche: " ?>
        <?= $totalVerifiche ?>
    </h2>

    <!-- Tasto per aggiungere una nuova verifica -->
    <div class="add-new-verification">
        <a href="aggiungi_verifica.php" class="btn btn-primary">Aggiungi Nuova Verifica</a>
    </div>

    <!-- Tabella Verifiche -->
    <div class="table-container">
        <table class="report-table">
            <thead>
                <tr>
                    <th>ID Verifica</th>
                    <th>Verificatore</th>
                    <th>Sito Verifica</th>
                    <th>UOT</th>
                    <th>Data Assegnazione</th>
                    <th>Data Inizio</th>
                    <th>Data Sospensione</th>
                    <th>Data Fine</th>
                    <th>Tipo Verifica</th>
                    <th>Stato</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($totalVerifiche > 0) {
                    while ($row = $db->fetchassoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['idVerifica']}</td>";
                        echo "<td>{$row['idverificatore']}</td>";
                        echo "<td>{$row['idSito_Verifica']}</td>";
                        echo "<td>{$row['iduot']}</td>";
                        echo "<td>{$row['dataAssegnazioneVer']}</td>";
                        echo "<td>{$row['dataInizioVer']}</td>";
                        echo "<td>{$row['dataSospensioneVer']}</td>";
                        echo "<td>{$row['dataFineVer']}</td>";
                        echo "<td>{$row['idtipoverifiche']}</td>";
                        echo "<td><img src=\"{$row['iconastato']}\" alt=\"{$row['stato']}\">{$row['stato']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>Nessuna verifica trovata.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>