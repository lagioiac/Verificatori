<?php
ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/CorsoFormazioneClass.php';
require 'class/CompetenzeClass.php';
require 'class/RegioneClass.php';
require 'class/ProvinciaClass.php';
require 'class/ComuneClass.php';
require 'class/AttivitaIndustrialeClass.php';

$corsoFormazioneClass=new CorsoFormazioneClass();
$competenzaClass=new CompetenzeClass();
$regioneClass=new RegioneClass();
$provinciaClass=new ProvinciaClass();
$comuneClass = new ComuneClass();
$attivitaClass = new AttivitaIndustrialeClass();

$post = array();

$db = new DbConnect();

$db->open() or die($db->error());
$op = $_REQUEST['op'];

switch ($op) {
    case 1: //corso di formazione
        if ($_POST["corso"] == "" || $_POST["anno"] == "") {
            ob_end_clean();
            header("Location: dati.php?msg");
            exit();
        }
        $risultato = $corsoFormazioneClass->insertCorsoFormazione($db, $_POST);
        break;

    case 2: //competenza
        if ($_POST["competenza"] == "") {
            ob_end_clean();
            header("Location: dati.php?msg");
            exit();
        }
        $risultato = $competenzaClass->insertCompetenza($db, $_POST);
        break;
        
    case 3: //regione
        if ($_POST["regione"] == "") {
            ob_end_clean();
            header("Location: dati.php?msg");
            exit();
        }
        $risultato = $regioneClass->insertRegione($db, $_POST);
        break;
    case 4: //provincia
        if ($_POST["provincia"] == "" || $_POST["regioni"] == "") {
            ob_end_clean();
            header("Location: dati.php?msg");
            exit();
        }
        $risultato = $provinciaClass->insertProvincia($db, $_POST);
        
        $ris=$provinciaClass->getLastRecord($db);
        $row=$db->fetchassoc2($ris);
        $idprov=$db->mysqli_real_escape($row["provinciaId"]);
        $prov=$db->mysqli_real_escape($row["prov"]);
        //Inserisci la provincia come comune
        //biosogna fare il get dell'id della provincia!!!!!!
        $ris2 = $comuneClass->insertAutomaticoComune($db, $prov, $idprov);
        break;
    case 5: //comune
        if ($_POST["comune"] == "" || $_POST["province"] == "") {
            ob_end_clean();
            header("Location: dati.php?msg");
            exit();
        }
        $risultato = $comuneClass->insertComune($db, $_POST);
        break;
    case 6: //attività industriale
        if ($_POST["attivita"] == "") {
            ob_end_clean();
            header("Location: dati.php?msg");
            exit();
        }
        $risultato = $attivitaClass->insertAttivitaIndustriale($db, $_POST);
        break;
}
ob_end_clean();
header("Location: dati.php?succes" . $risultato);

?>
