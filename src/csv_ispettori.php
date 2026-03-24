<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspettoreClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$ispettoreClass= new IspettoreClass();
$elencoispettori=$ispettoreClass->getElencoTotaleIspettori($db);

//scrivi intestazione tabella
$outcsv='Regione'.';';
$outcsv.='UOT'.';';
$outcsv.='Titolo'.';';
$outcsv.='Ispettore'.';';
$outcsv.='Ruolo'.';';
$outcsv.='email'.';';
$outcsv.='disp.trasf.'.';';
$outcsv.='SGS-PIR'.';';
$outcsv.='SGS'.';';
$outcsv.='Udit.'.';';
$outcsv.='Corso'.';';
$outcsv.='Ispez.Concl.'.';';
$outcsv.='Ispez.Ass.'.';';
$outcsv.='Note'.';'."\n";

//scrivi la tabella
if(mysqli_num_rows($elencoispettori)){
    $regionecurr="";
    $uotcurr="";
    while ($row=$db->fetchassoc2($elencoispettori)) {
        $regionecurr=$row["Regione"];
        $outcsv.=$regionecurr.';';  //Regione
        
        $uotcurr=$row["UOT"];
        $outcsv.=$uotcurr.';';  //UOT
        
        $outcsv.=$row["Competenza"].';';    //Titolo
        $outcsv.=$row["Cognome"].' '.$row["Nome"].';';  //ispettore
        $outcsv.=$row["Ruolo"].';';  //Ruolo
        $outcsv.=$row["email"].';';  //email
        if($row["flgDispTrasferta"]==0){    //disponibilità trasferta
            $outcsv.="Non so".';'; 
        }elseif($row["flgDispTrasferta"]==1){
            $outcsv.="Si".';';
        }elseif($row["flgDispTrasferta"]==2){
            $outcsv.="No".';';
        } 
        $outcsv.=$row["SGSPIR"].';';  //SGSPIR
        $outcsv.=$row["SGS"].';';  //SGS
        $outcsv.=$row["UDIT"].';';  //UDIT
        $outcsv.=$row["corso"]." - ".$row["annocorso"].';';  //corso di formazione
        
        if($row["Ruolo"]=="esperto"){
            //aggiungi il contatore delle ispezioni concluse
            $nIspezAnno=$ispettoreClass->contaIspezioniByIspettore($db, $row["ispett"], 1);
            $r= $db->fetchassoc2($nIspezAnno);
            $outcsv.=$db->mysqli_real_escape($r["cont"]).';';
            // e quelle assegnate
            $nIspezAnno=$ispettoreClass->contaIspezioniByIspettore($db, $row["ispett"], 2);
            $r= $db->fetchassoc2($nIspezAnno);
            $outcsv.=$db->mysqli_real_escape($r["cont"]).';';
             
        }elseif(($row["Ruolo"]=="uditore") || ($row["Ruolo"]=="nuovo")){
            //aggiungi il contatore delle ispezioni concluse
            $nIspezAnno=$ispettoreClass->contaIspezioniByUditore($db, $row["ispett"], 1);
            $r= $db->fetchassoc2($nIspezAnno);
            $outcsv.=$db->mysqli_real_escape($r["cont"]).';';
            // e quelle assegnate
            $nIspezAnno=$ispettoreClass->contaIspezioniByUditore($db, $row["ispett"], 2);
            $r= $db->fetchassoc2($nIspezAnno);
            $outcsv.=$db->mysqli_real_escape($r["cont"]).';';
        }
        
        $outcsv.=$row["note"].';';  //note
                
        $outcsv.="\n";
    }
}

// Download the file

$filename = "elenco_ispettori.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();

?>
