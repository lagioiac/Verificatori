<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspettoreClass.php';
require 'class/UotClass.php';
require 'class/RegioneClass.php';
require 'class/UotRegioneClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$uotClass=new UotClass();
$regioneClass = new RegioneClass();
$uot_regioneClass= new UotRegioneClass();

$uot=$uotClass->getLastRecord($db);
$regione = $regioneClass->getRegioni($db);
$uot_regione=null;

//scrivi intestazione tabella
$outcsv='Regione'.';';
$outcsv.='UOT'.';';
$outcsv.='Indirizzo'.';';
$outcsv.='CAP'.';';
$outcsv.='Provincia'.';';
$outcsv.='PEC'.';';
$outcsv.='Tel'.';';
$outcsv.='Fax'.';';
$outcsv.='n.Ispettori Esp.'.';';
$outcsv.='n.Uditori'.';';
$outcsv.='n.Nuovi'.';'."\n";

if(mysqli_num_rows($regione)){
    $regionecurr="";
    while($row=$db->fetchassoc2($regione)){
        //get tutte le uot della regione
        $idRegione=(int)$row["regioneId"];
        $uot_regione=$uot_regioneClass->getUotRegione($db, $idRegione);
        if(mysqli_num_rows($uot_regione)) {
            while($row2=$db->fetchassoc2($uot_regione)){
                
                $regionecurr=$row["nomeregione"];
                $outcsv.=$regionecurr.';';  //Regione
                
                $outcsv.=$row2["uotDenominazione"].';'; 
                $outcsv.=$row2["uotIndirizzo"].';';
                $outcsv.=$row2["uotCap"].';';
                
                $prov=$uotClass->getProvinciaUot($db, $row2["provinciaFkId"]);
                $row3=$db->fetchassoc2($prov);
                $outcsv.=$row3["prov"].';';
                
                $outcsv.=$row2["uotPec"].';';
                $outcsv.=$row2["uotTelefono"].';';
                $outcsv.=$row2["uotFax"].';';
                
                $k1=0;
                $k2=0;
                $k3=0;
                $nexp=$uotClass->contaRuoliByUot($db, $row2["uotId"], 1);
                while($row3= $db->fetchassoc2($nexp)){ 
                    if($db->mysqli_real_escape($row3["cont"])>0){
                        $k1=$db->mysqli_real_escape($row3["cont"]);
                    }
                }
                $nexp=$uotClass->contaRuoliByUot($db, $row2["uotId"], 2);
                while($row3= $db->fetchassoc2($nexp)){ 
                    if($db->mysqli_real_escape($row3["cont"])>0){
                        $k2=$db->mysqli_real_escape($row3["cont"]);
                    }
                }
                $nexp=$uotClass->contaRuoliByUot($db, $row2["uotId"], 3);
                while($row3= $db->fetchassoc2($nexp)){ 
                    if($db->mysqli_real_escape($row3["cont"])>0){
                        $k3=$db->mysqli_real_escape($row3["cont"]);
                    }
                }
                $outcsv.=$k1.';';
                $outcsv.=$k2.';';
                $outcsv.=$k3.';';
                
                $outcsv.="\n";
            }
        }
    }
}
// Download the file

$filename = "uot.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();
?>
