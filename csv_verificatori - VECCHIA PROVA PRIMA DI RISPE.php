<?php
include 'config.php';
include 'db/mysql.php';
require 'class/PersonaleUOTClass.php';
require 'class/QualificheClass.php';
require 'class/RuoloClass.php';

// Percorso della cartella di download e nome del file
$directory = 'Download';
$filename = 'Export_Verificatori.csv';
$filepath = $directory . "/" . $filename; 

// Crea la cartella di download se non esiste
if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}

// Elimina il file esistente se già presente
if (file_exists($filepath)) {
    unlink($filepath);
}

try {
    // Crea un puntatore file con l"output PHP
    $output = fopen($filepath, 'w');

    if ($output === false) {
        throw new Exception('Non è stato possibile creare il file.');
    }

    // Scrive l'header della tabella CSV
    fputcsv($output, array(
        'XF', 'Cognome', 'Nome', 'Denominazione UOT', 'Qualifica', 'Cell', 'Tel. fisso', 'eMail',
        'Comp. PRESSIONE', 'Comp. SOLLEVAMENTO', 'Comp. RISCALDAMENTO', 'Comp. TERRA',
        'Dispon. per altre UOT', 'Disponibile in (P)resenza, (R)emoto o entrambi (X)', 'Note'
    ), ';');

    // Connessione al database
    $db = new DbConnect();
    $db->open() or die($db->error());

    // Query per ottenere i dati dalla tabella personaleuot
    $query = "
        SELECT p.XF, p.cognome, p.nome, u.denominazione AS denominazione_uot, q.qualifica, 
               p.cell, p.telfisso, p.email, p.flgP, p.flgS, p.flgR, p.flgT, 
               p.flgAltreUot, p.disponibile, p.note 
        FROM personaleuot p
        LEFT JOIN uot u ON p.iduot = u.IdUot
        LEFT JOIN qualifica q ON p.idqualifica = q.idQualifica";

    $result = $db->query($query);

    // Scrive i dati nel file CSV
    while ($row = $db->fetchassoc($result)) {
        fputcsv($output, array(
            $row['XF'], 
            $row['cognome'], 
            $row['nome'], 
            $row['denominazione_uot'], 
            $row['qualifica'], 
            $row['cell'], 
            $row['telfisso'], 
            $row['email'], 
            $row['flgP'] == 1 ? 'X' : '', 
            $row['flgS'] == 1 ? 'X' : '', 
            $row['flgR'] == 1 ? 'X' : '', 
            $row['flgT'] == 1 ? 'X' : '', 
            $row['flgAltreUot'] == 1 ? 'X' : '', 
            $row['disponibile'], 
            $row['note']
        ), ';');
    }

    // Chiude la connessione al database
    $db->close();
    fclose($output);

    // Successo
    http_response_code(200);
    echo 'Success';

} catch (Exception $e) {
    // Errore
    http_response_code(500);
    echo 'Errore: ' . $e->getMessage();
}
?>





