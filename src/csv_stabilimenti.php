<?php

ob_start();
require 'config.php';

require 'db/mysql.php';

require 'class/StabilimentoClass.php';
require 'class/IspezioneClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$stabilimentoClass=new StabilimentoClass;
$ispezioneClass=new IspezioneClass;

$stabilimento=$stabilimentoClass->getElencoStabilimentiConDettagli($db);    //lista in ordine alfabetico: regione, provincia, stabilimento

//scrivi intestazione tabella
$outcsv='Regione'.';';
$outcsv.='Provincia'.';';
$outcsv.='Comune'.';';
$outcsv.='Localita'.';';
$outcsv.='Stabilimento'.';';
$outcsv.='Codice'.';';
//$outcsv.='Soglia'.';';
$outcsv.='Attività Industriale'.';';   
$outcsv.='Periodo Ispez.'.';'; 
$outcsv.='Ultima Ispez.'.';'."\n"; //anno

if(mysqli_num_rows($stabilimento)){
    $regionecurr="";
    $provinciacurr="";
    $comunecurr="";
    while($row=$db->fetchassoc2($stabilimento)){
        $regionecurr=$row["NomeRegione"];
        $outcsv.=$regionecurr.';';  //Regione
        
        $provinciacurr=$row["Prov"];
        $outcsv.=$provinciacurr.';';  //Provincia
        
        $comunecurr=$row["Comune"];
        $outcsv.=$comunecurr.';';  //Comune
        
        $outcsv.=$row["Localita"].";";
        $outcsv.=$row["Stabilimento"].";";
        $outcsv.=$row["Codice"].";";
        $outcsv.=$row["Attivita"].";";
        
        if($row["Periodo"]){    //AGGIUNTO PERIODO 16-02-2017
            $outcsv.=$row["Periodo"].";";
        }else{
            $outcsv.=';'; 
        }
        
        $annoisp=$ispezioneClass->getIspezioniPerStabilimento($db, $row["stabilimentoId"]);
        $row2=$db->fetchassoc2($annoisp);
        $outcsv.=$row2["anno"].";";
        
        $outcsv.="\n";
    }
}
// Download the file

$filename = "stabilimenti.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();
?>
