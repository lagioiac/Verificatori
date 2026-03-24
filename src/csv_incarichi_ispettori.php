<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspettoreClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$ispettoreClass= new IspettoreClass();
$elencoispettori=$ispettoreClass->getElencoTotaleIspettori($db);
if ($_REQUEST["anno"] != "") {
    $annocurr=$_REQUEST["anno"];
    
    //scrivi intestazione tabella
    $outcsv='Regione'.';';
    $outcsv.='UOT'.';';
    $outcsv.='Ispettore'.';';
    $outcsv.='Competenza'.';';
    $outcsv.='Disp. Trasferta'.';';
    $outcsv.='Esperto-Assegnate'.';';
    $outcsv.='Uditore-Assegnate'.';';
    $outcsv.='Esperto-Concluse'.';';
    $outcsv.='Uditore-Concluse'.';';
    $outcsv.='n. Ispez.Sospese'.';';
    $outcsv.='Esperienza Ispezioni'.';'."\n";
    
    //scrivi la tabella
    if(mysqli_num_rows($elencoispettori)){
        while ($row=$db->fetchassoc2($elencoispettori)) {
            $outcsv.=$row["Regione"].';';  //Regione
            $outcsv.=$row["UOT"].';';
            $outcsv.=$row["Cognome"].' '.$row["Nome"].';';  //ispettore
//            $outcsv.=$row["Ruolo"].';';   //Ruolo
            $outcsv.=$row["Competenza"].';';   //Competenza
            if($row["flgDispTrasferta"]==1){
                $outcsv.="Si".';';
            }elseif($row["flgDispTrasferta"]==2){
                $outcsv.="No".';';
            }if($row["flgDispTrasferta"]==0){
                $outcsv.="Non so".';';
            }

            $nIspezAnno=$ispettoreClass->contaIspezioniByIspettoreAnno($db, $row["ispett"], 2,$annocurr); 
            $k1=0;
            while($row31= $db->fetchassoc2($nIspezAnno)){ 
                if($db->mysqli_real_escape($row31["cont"])>0){
                    $k1=$db->mysqli_real_escape($row31["cont"]);
                }
                $outcsv.=$k1.';';   //Esperto-Assegnate
            }
            //controlla se ha avuto ispezioni come uditore
            $nIspezAnno=$ispettoreClass->contaIspezioniByUditoreAnno($db, $row["ispett"], 2,$annocurr); 
            $k1=0;
            while($row31= $db->fetchassoc2($nIspezAnno)){ 
                if($db->mysqli_real_escape($row31["cont"])>0){
                    $k1=$db->mysqli_real_escape($row31["cont"]);
                }
                $outcsv.=$k1.';';   //Uditore-Assegnate
            }
            //Ispettore  concluse
            $nIspezAnno=$ispettoreClass->contaIspezioniByIspettoreAnno($db, $row["ispett"], 1,$annocurr); 
            $k1=0;
            while($row31= $db->fetchassoc2($nIspezAnno)){ 
                if($db->mysqli_real_escape($row31["cont"])>0){
                    $k1=$db->mysqli_real_escape($row31["cont"]);
                }
                $outcsv.=$k1.';';
            }
            //Uditore  concluse
            $nIspezAnno=$ispettoreClass->contaIspezioniByUditoreAnno($db, $row["ispett"], 1,$annocurr); 
            $k1=0;
            while($row31= $db->fetchassoc2($nIspezAnno)){ 
                if($db->mysqli_real_escape($row31["cont"])>0){
                    $k1=$db->mysqli_real_escape($row31["cont"]);
                }
                $outcsv.=$k1.';';
            }
            //Ispettore  sospese
            $nIspezAnno=$ispettoreClass->contaIspezioniByIspettoreAnno($db, $row["ispett"], 4,$annocurr); 
            $k1=0;
            while($row31= $db->fetchassoc2($nIspezAnno)){ 
                if($db->mysqli_real_escape($row31["cont"])>0){
                    $k1=$db->mysqli_real_escape($row31["cont"]);
                }
                $outcsv.=$k1.';';
            }
            //Esperienza
            $esp=$ispettoreClass->getElencoEsperienzeIspettore($db, $row["ispett"]);
            $listesp="";
            while($r4=$db->fetchassoc2($esp)){
                $listesp.=$db->mysqli_real_escape($r4["Esperienza"]).";";
            }$outcsv.=$listesp.';';

            $outcsv.="\n";
        }
    }
}
// Download the file

$filename = "elenco_incarichi_ispettori_".$annocurr.".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();

?>
