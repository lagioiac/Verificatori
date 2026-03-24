<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspettoreClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$ispettoreClass= new IspettoreClass();
$elenconoteispettori=$ispettoreClass->getElencoTotaleNoteIspettori($db);

//scrivi intestazione tabella
$outcsv='UOT'.';';
$outcsv.='Cognome'.';';
$outcsv.='Nome'.';';
$outcsv.='Ruolo'.';';
$outcsv.='Note'.';'."\n";

//scrivi la tabella
if(mysqli_num_rows($elenconoteispettori)){
    $uotcurr="";
    while ($row=$db->fetchassoc2($elenconoteispettori)) {
        if(($row["Note"]!="") && ($row["Note"]!="NULL")){
            if($uotcurr!=$row["UOT"]){
                $uotcurr=$row["UOT"];  //UOT
                $outcsv.=$uotcurr.';';  //UOT
            }else{
                $outcsv.=';';  //UOT come sopra
            }
            if($row["Ruolo"]=="esperto"){
                $outcsv.=$row["Cognome"].';'.$row["Nome"].';'.'esperto;'.$row["Note"].';';  //ispettore
            }elseif($row["Ruolo"]=="uditore"){
                $outcsv.=$row["Cognome"].';'.$row["Nome"].';'.'uditore;'.$row["Note"].';';  //uditore
            }elseif($row["Ruolo"]=="nuovo"){
                $outcsv.=$row["Cognome"].' '.$row["Nome"].';'.'nuovo;'.$row["Note"].';';  //nuovo
            }
            $outcsv.="\n";
        }
    }
}
// Download the file

$filename = "elenco_note_ispettori.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();

?>
