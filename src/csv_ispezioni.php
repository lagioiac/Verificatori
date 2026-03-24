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
$ispezioni_assegnate=$ispezioneClass->getIspezioniAssegnate($db);

//scrivi intestazione tabella
$outcsv='Anno'.';';
$outcsv.='Azienda - Ragione Sociale'.';';
$outcsv.='Attivita'.';';
$outcsv.='Comune'.';';
$outcsv.='Provincia'.';';
$outcsv.='Regione'.';';
$outcsv.='Ispettore'.';';
$outcsv.='Uditore'.';';
$outcsv.='Note'.';';
$outcsv.='Tipo Ispezione'.';'."\n";

//scrivi la tabella
if(mysqli_num_rows($ispezioni_assegnate)){
    $annocurr="";
    while ($row=$db->fetchassoc2($ispezioni_assegnate)) {
//        if($annocurr!=$row["Anno"]){
            $annocurr=$row["Anno"];  //Anno
            $outcsv.=$annocurr.';';  //Anno
//        }else{
//            $outcsv.=';';  //Anno come sopra
//        }
        $outcsv.=$row["Azienda"].';';  //azienda
        $outcsv.=$row["Attivita"].';';  //attività
        $outcsv.=$row["Comune"].';';  //località comune
        $outcsv.=$row["Provincia"].';';  //provincia
        $outcsv.=$row["Regione"].';';  //regione
        //get Ispettore
        $ispett=$ispettoreClass->getBigliettoVisita($db, $db->mysqli_real_escape($row["Ispettore"]));
        $row2=$db->fetchassoc2($ispett);
        $outcsv.=$row2["Cognome"].' '.$row2["Nome"];
        $outcsv.=' c/o INAIL '.$row2["UOT"].' '.$row2["Indirizzo"];
        $outcsv.=' - '.$row2["cap"].' '.$row2["Provincia"];
        $outcsv.=' '.$row2["PEC"].' '.$row2["email"];
        $outcsv.=' Tel.'.$row2["Tel"].'/Fax '.$row2["Fax"];
        $outcsv.=';';  //
        
        //get Uditore, se esiste
        if($row["Uditore"]!=NULL){
            $ispett=$ispettoreClass->getBigliettoVisita($db, $db->mysqli_real_escape($row["Uditore"]));
            $row2=$db->fetchassoc2($ispett);
            $outcsv.=$row2["Cognome"].' '.$row2["Nome"];
            $outcsv.=' c/o INAIL '.$row2["UOT"].' '.$row2["Indirizzo"];
            $outcsv.=' - '.$row2["cap"].' '.$row2["Provincia"];
            $outcsv.=' '.$row2["PEC"].' '.$row2["email"];
            $outcsv.=' Tel.'.$row2["Tel"].'/Fax '.$row2["Fax"];
            $outcsv.=';';
        } else {
            $outcsv.=';';
        }
        //get note se esistono
        if($row["Note"]!=NULL){
            $outcsv.=$row["Note"].';';
        }else {
            $outcsv.=';';
        }
        //Aggiunto tipo ispezione 16-02-2017
        if($row["TipoIspez"]!=NULL){
            $outcsv.=$row["TipoIspez"].';';
        }else {
            $outcsv.=';';
        }
        
        $outcsv.="\n";
    }
}


$ispezioni_dapianificare=$ispezioneClass->getIspezioniDaPianificare($db);
$outcsv.=";\n";
$outcsv.="ISPEZIONI DA PIANIFICARE;\n";
//scrivi intestazione tabella
$outcsv.='Anno'.';';
$outcsv.='Azienda - Ragione Sociale'.';';
$outcsv.='Attivita'.';';
$outcsv.='Comune'.';';
$outcsv.='Provincia'.';';
$outcsv.='Regione'.';';
$outcsv.='Tipo Ispezione'.';'."\n";

//scrivi la tabella
if(mysqli_num_rows($ispezioni_dapianificare)){
    $annocurr="";
    while ($row=$db->fetchassoc2($ispezioni_dapianificare)) {
//        if($annocurr!=$row["Anno"]){
            $annocurr=$row["Anno"];  //Anno
            $outcsv.=$annocurr.';';  //Anno
//        }else{
//            $outcsv.=';';  //Anno come sopra
//        }
        $outcsv.=$row["Azienda"].';';  //azienda
        $outcsv.=$row["Attivita"].';';  //attività
        $outcsv.=$row["Comune"].';';  //località comune
        $outcsv.=$row["Provincia"].';';  //provincia
        $outcsv.=$row["Regione"].';';  //regione

        //Aggiunto tipo ispezione 16-02-2017
        if($row["TipoIspez"]!=NULL){
            $outcsv.=$row["TipoIspez"].';';
        }else {
            $outcsv.=';';
        }
        
        $outcsv.="\n";
    }
}
// Download the file

$filename = "ispezioni_assegnate_e_dapianificare.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;

exit;
ob_end_clean();

?>
