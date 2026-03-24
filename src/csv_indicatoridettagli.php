<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspettoreClass.php';
require 'class/IspezioneClass.php';
require 'class/RegioneClass.php';
require 'class/StabilimentoClass.php';
require 'class/UotRegioneClass.php';
require 'class/UotClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$ispettoreClass= new IspettoreClass();
$ispezioneClass=new IspezioneClass();
$uotClass = new UotClass();
$stabilimentoClass= new StabilimentoClass();
$uotRegione = new UotRegioneClass();
$regioneClass=new RegioneClass();

//get degli anni
$anni=$ispezioneClass->getAnniTutteIspezioni($db);
$annispez = array();
$i=0;
while ($row = $db->fetchassoc2($anni)) { 
    $annispez[$i] = $row["anno"];
    $i++;
}

for($i=0;$i<count($annispez);$i++){
    //ANNO
    $outcsv.=$annispez[$i].';';
    $outcsv.="\n";
    
    //ISPEZIONI CON ISPETTORI STESSA UOT
    $outcsv.='ISPEZIONI CON ISPETTORI STESSA UOT'.';';
    $outcsv.="\n";
    $outcsv.=";"."\n";
    for($j=1; $j<3; $j++){
        if($j==1){
            $outcsv.="ISPEZIONI CONCLUSE";
        }elseif($j==2){
            $outcsv.="ISPEZIONI IN CORSO";
        }
        $outcsv.="\n";
        $outcsv.="Stabilimento; UOT-Stabilimento; Regione-Stabilimento; Ispettore; UOT-Ispettore; Regione-Ispettore";
        $outcsv.="\n";
        $isp=$ispezioneClass->getIspezioniStessaUOTAnno($db, $annispez[$i],$j);
        while($row20= $db->fetchassoc2($isp)){
            $stabilimentoClass->setStabilimentoId($row20["stabIdFk"]);
            $stabTmp=$stabilimentoClass->getDettaglioStabilimento($db);
            $row21=$db->fetchassoc2($stabTmp);
            $outcsv.=$row21["stabilimentoDenominazione"].";";
            //UOT dello stabilimento
            $uotClass->setUotId($row21["uotAffIdFk"]);
            $uottmp=$uotClass->getDettaglioUot($db);
            $row25=$db->fetchassoc2($uottmp);
            $outcsv.=$row25["uotDenominazione"].";";
            //Regione stabilimento
            $regstabtmp=$uotRegione->getRegioneIdByUot($db, $row21["uotAffIdFk"]);
            $row24=$db->fetchassoc2($regstabtmp);
            $idRegStab=$row24["regioneIdFk"];
            $regioneClass->setRegioneId($row24["regioneIdFk"]);
            $regtmp=$regioneClass->getDettaglioRegione($db);
            $row26=$db->fetchassoc2($regtmp);
            $outcsv.=$row26["nomeregione"].";";
            //get ispettore
            $ispettoreClass->setIspettoreId($row20["ispettIdFk"]);
            $ispetTmp=$ispettoreClass->getDettaglioIspettore($db);
            $row22=$db->fetchassoc2($ispetTmp);
            $outcsv.=$row22["ispettoreCognome"]." ".$row22["ispettoreNome"].";";
            //get uot dell'ispettore
            $uotClass->setUotId($row22["uotIspIdFk"]);
            $uottmp=$uotClass->getDettaglioUot($db);
            $row25=$db->fetchassoc2($uottmp);
            $outcsv.=$row25["uotDenominazione"].";";
            //regione Ispettore
            $regtmp=$uotRegione->getRegioneIdByUot($db, $row22["uotIspIdFk"]);
            $row23=$db->fetchassoc2($regtmp);
            $idRegIspet=$row23["regioneIdFk"];
            $regioneClass->setRegioneId($row23["regioneIdFk"]);
            $regtmp=$regioneClass->getDettaglioRegione($db);
            $row26=$db->fetchassoc2($regtmp);
            $outcsv.=$row26["nomeregione"].";";
            $outcsv.="\n";
        }
        $outcsv.=";"."\n";
    }
    //
    $outcsv.='ISPEZIONI CON ISPETTORI DIVERSA UOT'.';';
    $outcsv.="\n";
    $outcsv.=";"."\n";
    for($j=1; $j<3; $j++){
        if($j==1){
            $outcsv.="ISPEZIONI CONCLUSE";
        }elseif($j==2){
            $outcsv.="ISPEZIONI IN CORSO";
        }
        $outcsv.="\n";
        $outcsv.="Stabilimento; UOT-Stabilimento; Regione-Stabilimento; Ispettore; UOT-Ispettore; Regione-Ispettore";
        $outcsv.="\n";
        $isp=$ispezioneClass->getIspezioniDiversaUOTAnno($db, $annispez[$i],$j);
        while($row10= $db->fetchassoc2($isp)){
            //verifica se l'uot dell'ispettore e dello stab hanno la stessa regione
            //get id regione della uot stabilimento
            $stabilimentoClass->setStabilimentoId($row10["stabIdFk"]);
            $stabTmp=$stabilimentoClass->getDettaglioStabilimento($db);
            $row11=$db->fetchassoc2($stabTmp);
            $outcsv.=$row11["stabilimentoDenominazione"].";";
            //UOT dello stabilimento
            $uotClass->setUotId($row11["uotAffIdFk"]);
            $uottmp=$uotClass->getDettaglioUot($db);
            $row15=$db->fetchassoc2($uottmp);
            $outcsv.=$row15["uotDenominazione"].";";
            //Regione stabilimento
            $regstabtmp=$uotRegione->getRegioneIdByUot($db, $row11["uotAffIdFk"]);
            $row14=$db->fetchassoc2($regstabtmp);
            $idRegStab=$row14["regioneIdFk"];
            $regioneClass->setRegioneId($row14["regioneIdFk"]);
            $regtmp=$regioneClass->getDettaglioRegione($db);
            $row16=$db->fetchassoc2($regtmp);
            $outcsv.=$row16["nomeregione"].";";
            //get ispettore
            $ispettoreClass->setIspettoreId($row10["ispettIdFk"]);
            $ispetTmp=$ispettoreClass->getDettaglioIspettore($db);
            $row12=$db->fetchassoc2($ispetTmp);
            $outcsv.=$row12["ispettoreCognome"]." ".$row12["ispettoreNome"].";";
            //get uot dell'ispettore
            $uotClass->setUotId($row12["uotIspIdFk"]);
            $uottmp=$uotClass->getDettaglioUot($db);
            $row15=$db->fetchassoc2($uottmp);
            $outcsv.=$row15["uotDenominazione"].";";
            //regione Ispettore
            $regtmp=$uotRegione->getRegioneIdByUot($db, $row12["uotIspIdFk"]);
            $row13=$db->fetchassoc2($regtmp);
            $idRegIspet=$row13["regioneIdFk"];
            $regioneClass->setRegioneId($row13["regioneIdFk"]);
            $regtmp=$regioneClass->getDettaglioRegione($db);
            $row16=$db->fetchassoc2($regtmp);
            $outcsv.=$row16["nomeregione"].";";
            $outcsv.="\n";
        }
        $outcsv.=";"."\n";
    }
    $outcsv.=";"."\n";
}


// Download the file

$filename = "elenco_ispezionidettagli.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;


exit;
ob_end_clean();

?>
