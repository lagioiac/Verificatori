<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspettoreClass.php';
require 'class/PropostaIspezioneClass.php';
require 'class/UotClass.php';
require 'class/IspezioneClass.php';
require 'class/ProvinciaClass.php';
require 'class/RegioneClass.php';
require 'class/StabilimentoClass.php';
require 'class/AttivitaIndustrialeClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$propostaispezioneClass=new PropostaIspezioneClass();
$uotClass = new UotClass();
$ispezioneClass=new IspezioneClass();
$ispezioneCurrClass=new IspezioneClass();
$ispettoreClass=new IspettoreClass();
$ispettoreDesClass=new IspettoreClass();
$provinciaClass = new ProvinciaClass();
$regioneClass = new RegioneClass();
$stabilimentoClass = new StabilimentoClass();
$attivitaIndustrialeClass = new AttivitaIndustrialeClass();

$nomiProvinceComp = array();
$idProvinceComp = array();
$provinceregione=null;
$province = $provinciaClass->getProvince($db);

$uot = -1;

if (isset($_GET["an"])) {
    $annocurr=$_GET["an"];
}
if (isset($_GET["id"])) {
    $uot = $_GET["id"];
    $uotClass->setUotId($_GET["id"]);
    $uotClass->getDettaglioUot($db);
    $row = $db->fetchassoc();
    $uotClass->setUotDenominazione($row["uotDenominazione"]);
    $uotClass->setUotIndirizzo($row["uotIndirizzo"]);
    $uotClass->setUotCap($row["uotCap"]);
    $uotClass->setUotPec($row["uotPec"]);
    $uotClass->setUotTelefono($row["uotTelefono"]);
    $uotClass->setUotFax($row["uotFax"]);
    $uotClass->setUotProvinciaFkId($row["provinciaFkId"]);   
    //get di tutte le ispezioni da programmare nella UOT
    $elencoispezionidapianificare=$ispezioneClass->getIspezioniDaPianificareByUot($db, $db->mysqli_real_escape($uotClass->getUotId()),$annocurr);    
    //get di tutti gli ispettori della uot 
    $elencoispettoriuot=$ispettoreClass->getIspettoriByUot($db, $db->mysqli_real_escape($uotClass->getUotId()),0);
    //get di tutti gli stabilimenti che sono stati considerati
//    
//    $reg=$provinciaClass->getRegioneProvincia($db, $db->mysqli_real_escape($row["provinciaFkId"]));
//    $reg = $db->fetchassoc();
//    $regionecurr=$db->mysqli_real_escape($reg["regioneId"]);
//    $provinceregione=$provinciaClass->getProvinceStessaRegione($db, $db->mysqli_real_escape($reg["regioneId"]));
//    $i=0;
//    while ($r = $db->fetchassoc2($provinceregione)){
//        $nomiProvinceComp[$i] = $r["prov"];
//        $idProvinceComp[$i] = $r["provinciaId"];
//        $i++;
//    }
}


//scrivi intestazione tabella
$outcsv ='PROPOSTE DI ISPEZIONI'.';';
$outcsv.='Anno'.';'.$annocurr.';'."\n";

$outcsv.='Azienda - Ragione Sociale'.';';
$outcsv.='Attivita'.';';
//$outcsv.='Comune'.';';
//$outcsv.='Provincia'.';';
//$outcsv.='Regione'.';';
$outcsv.='Ispettore Proposto'.';';
$outcsv.='Uditore Proposto'.';'."\n";

$propostaispezione=$propostaispezioneClass->getProposteIspezioniByUot($db, $db->mysqli_real_escape($uotClass->getUotId()),$annocurr);
$n=mysqli_num_rows($propostaispezione);
if(mysqli_num_rows($propostaispezione)>0){
    while($row4=$db->fetchassoc2($propostaispezione)){
        //get stabilimento
        $ispezioneCurrClass->setIspezioneId($row4["ispezioneIdFk"]);
        $ispcurr=$ispezioneCurrClass->getDettaglioIspezione($db);
        $row5=$db->fetchassoc2($ispcurr);
        $stabilimentoClass->setStabilimentoId($row5["stabIdFk"]);
        $st=$stabilimentoClass->getDettaglioStabilimento($db);
        $row6=$db->fetchassoc2($st);
        
        $outcsv.=$row6["stabilimentoDenominazione"].';';
        
        if($row6["attivIndustrialeIdFk"]>0){
            $att=$attivitaIndustrialeClass->getAttivitaById($db, $row6["attivIndustrialeIdFk"]);
            $row7=$db->fetchassoc2($att);
            $outcsv.=$row7["attivita"].';';
        }else{
            $outcsv.="".';';
        }
        $propispezione=$propostaispezioneClass->getPropostaIspezioneByIspezione($db, $row4["ispezioneIdFk"]);
        $row8=$db->fetchassoc2($propispezione);
        if($row8["propIspettIdFk"]>0){  //quello designato da DIT
            $ispett=$ispettoreClass->getBigliettoVisita($db, $db->mysqli_real_escape($row8["propIspettIdFk"]));
            $row9=$db->fetchassoc2($ispett);
            $outcsv.=$row9["Cognome"].' '.$row9["Nome"];
            $outcsv.=' c/o INAIL '.$row9["UOT"].' '.$row9["Indirizzo"];
            $outcsv.=' - '.$row9["cap"].' '.$row9["Provincia"];
            $outcsv.=' '.$row9["PEC"].' '.$row9["email"];
            $outcsv.=' Tel.'.$row9["Tel"].'/Fax '.$row9["Fax"];
            $outcsv.=';';  //
        }elseif($row8["propIspettDaUotIdFk"]>0){
            $ispett=$ispettoreClass->getBigliettoVisita($db, $db->mysqli_real_escape($row8["propIspettDaUotIdFk"]));
            $row9=$db->fetchassoc2($ispett);
            $outcsv.=$row9["Cognome"].' '.$row9["Nome"];
            $outcsv.=' c/o INAIL '.$row9["UOT"].' '.$row9["Indirizzo"];
            $outcsv.=' - '.$row9["cap"].' '.$row9["Provincia"];
            $outcsv.=' '.$row9["PEC"].' '.$row9["email"];
            $outcsv.=' Tel.'.$row9["Tel"].'/Fax '.$row9["Fax"];
            $outcsv.=';';  //
        }
            
        if($row8["propUditIdFk"]>0){
            $ispettoreDesClass->setIspettoreId($row8["propUditIdFk"]);
            $uditDes=$ispettoreDesClass->getDettaglioIspettore($db);
            $row9=$db->fetchassoc2($uditDes);
            $uddes=$row9["ispettoreId"];
            $outcsv.=$row9["ispettoreCognome"]." ".$row9["ispettoreNome"].";";
        }elseif($row8["propUditDaUotIdFk"]>0){
            $ispettoreDesClass->setIspettoreId($row8["propUditDaUotIdFk"]);
            $uditDes=$ispettoreDesClass->getDettaglioIspettore($db);
            $row9=$db->fetchassoc2($uditDes);
            $uddes=$row9["ispettoreId"];
            $outcsv.=$row9["ispettoreCognome"]." ".$row9["ispettoreNome"].";";
        }
        $outcsv.="\n";
    }
}
                                    

// Download the file

$filename = "pianificazione_ispezioni.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();

?>
