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
$ispettoreClass= new IspettoreClass();

if ($_REQUEST["anno"] != "") {
    $annocurr=$_REQUEST["anno"];

    $ispezioni_sospese=$ispezioneClass->getIspezioniSospeseAnno($db, $annocurr);

    //scrivi intestazione tabella
    $outcsv='Anno'.';';
    $outcsv.='Azienda - Ragione Sociale'.';';
    $outcsv.='Attivita'.';';
    $outcsv.='Comune'.';';
    $outcsv.='Provincia'.';';
    $outcsv.='Regione'.';';
    $outcsv.='Ispettore'.';';
    $outcsv.='UOT'.';';
    $outcsv.='Uditore'.';';
    $outcsv.='UOT'.';';
    $outcsv.='Note'.';'."\n";

    //scrivi la tabella

    if(mysqli_num_rows($ispezioni_sospese)){
        while ($row=$db->fetchassoc2($ispezioni_sospese)) {
            $outcsv.=$annocurr.';';  //Anno come sopra

            $outcsv.=$row["Azienda"].';';  //azienda
            $outcsv.=$row["Attivita"].';';  //attività
            $outcsv.=$row["Comune"].';';  //località comune
            $outcsv.=$row["Provincia"].';';  //provincia
            $outcsv.=$row["Regione"].';';  //regione
            //get Ispettore se c'è
            if($row["Ispettore"]!=NULL){
                $ispett=$ispettoreClass->getBigliettoVisita($db, $db->mysqli_real_escape($row["Ispettore"]));
                $row2=$db->fetchassoc2($ispett);
                $outcsv.=$row2["Cognome"].' '.$row2["Nome"].';';
                //UOT
                $outcsv.=$row2["UOT"].';';

                //get Uditore, se esiste
                if($row["Uditore"]!=NULL){
                    $ispett=$ispettoreClass->getBigliettoVisita($db, $db->mysqli_real_escape($row["Uditore"]));
                    $row2=$db->fetchassoc2($ispett);
                    $outcsv.=$row2["Cognome"].' '.$row2["Nome"].';';
                    //UOT
                    $outcsv.=$row2["UOT"].';';
                } else {
                    $outcsv.=';'.';';
                }
            }else{
                $outcsv.=';'.';'.';'.';';
            }
            //get note se esistono
            if($row["Note"]!=NULL){
                $outcsv.=$row["Note"].';';
            }else {
                $outcsv.=';';
            }
            

            $outcsv.="\n";
        }
    }
}
// Download the file

$filename = "ispezioni_sospese_".$annocurr.".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();


?>
