<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspezioneClass.php';
require 'class/IspettoreClass.php';
require 'class/UotClass.php';
require 'class/StabilimentoClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$ispezioneClass = new IspezioneClass();
$ispettoreClass= new IspettoreClass();
$stabilimentoClass= new StabilimentoClass();
$uotClass = new UotClass();

if ($_REQUEST["anno"] != "") {
    $annocurr=$_REQUEST["anno"];

    $ispezioni_concluse=$ispezioneClass->getIspezioniConcluseAnno($db, $annocurr);

    //scrivi intestazione tabella
    $outcsv='Anno'.';';
    $outcsv.='Azienda - Ragione Sociale'.';';
    $outcsv.='Attivita'.';';
    $outcsv.='UOT Stab.'.';';
    $outcsv.='Comune'.';';
    $outcsv.='Provincia'.';';
    $outcsv.='Regione'.';';
    $outcsv.='Ispettore'.';';
    $outcsv.='UOT'.';';
    $outcsv.='Uditore'.';';
    $outcsv.='UOT'.';';
    $outcsv.='Rapp. Conclusivo'.';';
    $outcsv.='Esperienze Op.'.';';
    $outcsv.='Sistemi Tec.'.';';
    $outcsv.='Atri Doc.'.';';
    $outcsv.='Met.Invec.'.';';  //12-07-2019 AGGIUNTO FALG METODO INVECCHIAMENTO
    $outcsv.='Note'.';'."\n";

    //scrivi la tabella

    if(mysqli_num_rows($ispezioni_concluse)){
        while ($row=$db->fetchassoc2($ispezioni_concluse)) {
            $outcsv.=$annocurr.';';  //Anno come sopra

            $outcsv.=$row["Azienda"].';';  //azienda
            $outcsv.=$row["Attivita"].';';  //attività
            
            $stabilimentoClass->setStabilimentoId($row["stabilimentoId"]);
            $sttmp=$stabilimentoClass->getDettaglioStabilimento($db);
            $row5=$db->fetchassoc2($sttmp);
            $uotClass->setUotId($row5["uotAffIdFk"]);
            $uottmp=$uotClass->getDettaglioUot($db);
            $row6=$db->fetchassoc2($uottmp);
            $outcsv.=$row6["uotDenominazione"].';';  //UOT stabilimento
                                        
            $outcsv.=$row["Comune"].';';  //località comune
            $outcsv.=$row["Provincia"].';';  //provincia
            $outcsv.=$row["Regione"].';';  //regione
            //get Ispettore
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

            //Indica i documenti inviati
            if($row["rcdoc"]!=0){
                $outcsv.="Si".';';
            }else {
                $outcsv.="No".';';
            }
            if($row["eodoc"]!=0){
                $outcsv.="Si".';';
            }else {
                $outcsv.="No".';';
            }
            if($row["stdoc"]!=0){
                $outcsv.="Si".';';
            }else {
                $outcsv.="No".';';
            }
            if($row["aldoc"]!=0){
                $outcsv.="Si".';';
            }else {
                $outcsv.="No".';';
            }
            //12-07-2019    AGGIUNTA INFO SU METODO INDICI INVECCHIAMENTO
            if($row["midoc"]!=0){
                $outcsv.="Si".';';
            }else {
                $outcsv.="No".';';
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

$filename = "ispezioni_concluse_".$annocurr.".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();


?>
