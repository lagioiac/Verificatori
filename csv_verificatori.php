<?php
ob_start();
include 'config.php';
include 'db/mysql.php';
include 'class/PersonaleUOTClass.php';
include 'class/QualificheClass.php';
include 'class/RuoloClass.php';

// Connessione al database
$db = new DbConnect();
$db->open() or die($db->error());

// Inizializza la variabile $outcsv con l'header della tabella CSV
$outcsv = 'XF;Cognome;Nome;Denominazione UOT;Qualifica;Cell;Tel. fisso;eMail;Comp. PRESSIONE;Comp. SOLLEVAMENTO;Comp. RISCALDAMENTO;Comp. TERRA;Dispon. per altre UOT;Disponibile in (P)resenza, (R)emoto o entrambi (X);Note' . "\n";

// Query per ottenere i dati dalla tabella personaleuot
$query = "
    SELECT p.XF, p.cognome, p.nome, u.denominazione AS denominazione_uot, q.qualifica, 
           p.cell, p.telfisso, p.email, p.flgP, p.flgS, p.flgR, p.flgT, 
           p.flgAltreUot, p.disponibile, p.note 
    FROM personaleuot p
    LEFT JOIN uot u ON p.iduot = u.IdUot
    LEFT JOIN qualifica q ON p.idqualifica = q.idQualifica";
$result = $db->query($query);

// Popola la variabile $outcsv con i dati della tabella
if (mysqli_num_rows($result)) {
    while ($row = $db->fetchassoc($result)) {
        $outcsv .= $row['XF'] . ';';
        $outcsv .= $row['cognome'] . ';';
        $outcsv .= $row['nome'] . ';';
        $outcsv .= $row['denominazione_uot'] . ';';
        $outcsv .= $row['qualifica'] . ';';
        $outcsv .= $row['cell'] . ';';
        $outcsv .= $row['telfisso'] . ';';
        $outcsv .= $row['email'] . ';';
        $outcsv .= $row['flgP'] == 1 ? 'X;' : ';';
        $outcsv .= $row['flgS'] == 1 ? 'X;' : ';';
        $outcsv .= $row['flgR'] == 1 ? 'X;' : ';';
        $outcsv .= $row['flgT'] == 1 ? 'X;' : ';';
        $outcsv .= $row['flgAltreUot'] == 1 ? 'X;' : ';';
        $outcsv .= $row['disponibile'] . ';';
        $outcsv .= $row['note'] . ';';
        $outcsv .= "\n";
    }
}

// Chiudi la connessione al database
$db->close();

// Imposta le intestazioni per il download del file CSV
$filename = "Export_Verificatori.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);

// Output del contenuto CSV
echo $outcsv;
exit;
ob_end_clean();
?>






