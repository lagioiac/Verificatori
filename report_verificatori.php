<?php
// report_verificatori.php

// Includi il file di configurazione e la connessione al database
include 'config.php';
include 'db/mysql.php';
include 'include/check_user.php';

// ricordarsi di chudere head derivante dal header.php
include 'include/header.php';

// Connessione al database
$db = new DbConnect();
$db->open() or die($db->error());

// Inizializzazione delle variabili di conteggio
$nPersonaleUOT = 0;
$nVerificatoriAttivi = 0;
$nVerificatoriStandby = 0;
$xf_processed = array(); // Array per tenere traccia dei valori "XF" già trattati

// Esegui la query per ottenere tutti i record dalla tabella "personaleuot"
$PersonaleUOT = $db->query("SELECT * FROM personaleuot") or die($db->error());

if (mysqli_num_rows($PersonaleUOT)) {
    while ($row = $db->fetchassoc2($PersonaleUOT)) {
        if (!in_array($row["XF"], $xf_processed)) {
            $xf_processed[] = $row["XF"];
            $nPersonaleUOT++;
            if ($row["idruolo"] == 1 || $row["idruolo"] == 2) {
                $nVerificatoriAttivi++;
            } elseif ($row["idruolo"] == 3) {
                $nVerificatoriStandby++;
            }
        }
    }
} else {
    $nPersonaleUOT = 0;
}

// Inizializza i contatori delle competenze
$nVerificatoriP = 0;
$nVerificatoriS = 0;
$nVerificatoriR = 0;
$nVerificatoriT = 0;
$flgVerificatorePresente = 0;

// Contatori per regione
$contatoriRegioni = [];

// Query per ottenere i dati unici basati su 'XF'
$query = "
    SELECT DISTINCT p.XF, p.flgP, p.flgS, p.flgR, p.flgT, r.regione
    FROM personaleuot p
    JOIN uot u ON p.iduot = u.IdUot
    JOIN comune c ON u.idcomune = c.idComune
    JOIN provincia pr ON c.idprovincia = pr.idProvincia
    JOIN regione r ON pr.idregione = r.idRegione
";

$result = $db->query($query) or die($db->error());

while ($row = $db->fetchassoc($result)) {

    // Contatori nazionali
    if ($row['flgP'] == 1) $nVerificatoriP++;
    if ($row['flgS'] == 1) $nVerificatoriS++;
    if ($row['flgR'] == 1) $nVerificatoriR++;
    if ($row['flgT'] == 1) $nVerificatoriT++;

    // Contatori per regione
    $regione = $row['regione'];
    if (!isset($contatoriRegioni[$regione])) {
        $contatoriRegioni[$regione] = ['P' => 0, 'S' => 0, 'R' => 0, 'T' => 0, 'Verificatori_TOT' => 0];
        $flgVerificatorePresente=0;
    }
    if ($row['flgP'] == 1) {
        $contatoriRegioni[$regione]['P']++;
        $flgVerificatorePresente = 1; 
    }
    
    if ($row['flgS'] == 1) {
        $contatoriRegioni[$regione]['S']++;
        $flgVerificatorePresente = 1; 
    }    
    if ($row['flgR'] == 1) {
        $contatoriRegioni[$regione]['R']++;
        $flgVerificatorePresente = 1; 
    }    
    if ($row['flgT'] == 1) {
        $contatoriRegioni[$regione]['T']++;
        $flgVerificatorePresente = 1; 
    }   
    if ($flgVerificatorePresente == 1){
        $contatoriRegioni[$regione]['Verificatori_TOT']++;
        $flgVerificatorePresente=0;
    }
}
?>

<!DOCTYPE html>

<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Report Verificatori</title>
    <link rel="stylesheet" href="css/style.css">
</head>


<body>
    <div class="container">
        
        <a href="verificatori.php" class="back">Indietro</a>
        
        <h1 class="center-title">Report Verificatori</h1>
        
        <a>File PHP corrente o da dove si proviene nel caso di report: </a><?= $pageName?>

        <h2>Personale nelle UOT</h2>
        <table>
            <tr><th>Descrizione</th><th>Numero</th></tr>
            <tr><td>Personale UOT totale (compreso gli Amministrativi)</td><td><?= $nPersonaleUOT ?></td></tr>
            <tr><td>Verificatori ATTIVI nelle UOT (ossia coloro che hanno almeno una competenza tra P, S, R, e T</td><td><?= $nVerificatoriAttivi ?></td></tr>
            <tr><td>Verificatori in STANDBY nelle UOT</td><td><?= $nVerificatoriStandby ?></td></tr>
            <tr><td>Personale UOT rimanente</td><td><?= ($nPersonaleUOT - $nVerificatoriAttivi - $nVerificatoriStandby) ?></td></tr>
            <tr><th> </th><th> </th></tr> <!-- chiusura della tabella con riga verde - th ripetuto n volte per quante colonne ci sono -->
        </table>
        
        <div class="row spacer"></div> <!-- Riga vuota sopra - aldo -->
        <h2>Numero Totale dei Verificatori a livello Nazionale presenti nelle UOT</h2>
        <table>
            <tr><th>Competenza</th><th>Numero Verificatori</th></tr>
            <tr><td>Pressione (P)</td><td><?= $nVerificatoriP ?></td></tr>
            <tr><td>Sollevamento (S)</td><td><?= $nVerificatoriS ?></td></tr>
            <tr><td>Riscaldamento (R)</td><td><?= $nVerificatoriR ?></td></tr>
            <tr><td>Terre e protezione dalle scariche atmosferiche (T)</td><td><?= $nVerificatoriT ?></td></tr>
            <tr><th> </th><th> </th></tr> <!-- chiusura della tabella con riga verde - th ripetuto n volte per quante colonne ci sono -->
        </table>

        <div class="row spacer"></div> <!-- Riga vuota sopra - aldo -->
        <h2>Conteggio dei Verificatori a livello Regionale</h2>
        <table>
            <tr>
                <th>Regione</th>
                <th>Pressione (P)</th>
                <th>Sollevamento (S)</th>
                <th>Riscaldamento (R)</th>
                <th>Terre e protezione dalle scariche atmosferiche (T)</th>
                <th>Verificatori totali</th>
            </tr>
            <?php foreach ($contatoriRegioni as $regione => $contatori): ?>
            <tr>
                <td><?= $regione ?></td>
                <td><?= $contatori['P'] ?></td>
                <td><?= $contatori['S'] ?></td>
                <td><?= $contatori['R'] ?></td>
                <td><?= $contatori['T'] ?></td>
                <td><?= $contatori['Verificatori_TOT'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr><th> </th><th> </th><th> </th><th> </th><th> </th><th> </th></tr> <!-- chiusura della tabella con riga verde - th ripetuto n volte per quante colonne ci sono -->
            
        </table>
        
        <div class="back-button">
            <a href="verificatori.php" class="back">Indietro</a>
        </div>
        
         
    </div>
</body>
</head> <!-- chiusura sezione head presente dentro il file header.php -->
</html>