<?php
ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspezioneClass.php';
require 'class/IspettoreClass.php';
require 'class/UotClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$ispezioneClass = new IspezioneClass();
$ispezioni=$ispezioneClass->getElencoTotaleNoteIspezioni($db);

//scrivi intestazione tabella
$outcsv='Anno'.';';
$outcsv.='Azienda - Ragione Sociale'.';';
$outcsv.='Attivita'.';';
$outcsv.='Comune'.';';
$outcsv.='Note'.';'."\n";

//scrivi la tabella
if(mysqli_num_rows($ispezioni)){
    $annocurr="";
    while ($row=$db->fetchassoc2($ispezioni)) {
        if(($row["Note"]!="") && ($row["Note"]!="NULL")){
            if($annocurr!=$row["Anno"]){
                $annocurr=$row["Anno"];  //Anno
                $outcsv.=$annocurr.';';  //Anno
            }else{
                $outcsv.=';';  //Anno come sopra
            }
            $outcsv.=$row["Azienda"].';';  //azienda
            $outcsv.=$row["Attivita"].';';  //attività
            $outcsv.=$row["Comune"].';';  //località comune
            $outcsv.=$row["Note"].';';
            $outcsv.="\n";
        }
    }
}

// Download the file

$filename = "ispezioni_note.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();

?>
