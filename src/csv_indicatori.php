<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspettoreClass.php';
require 'class/IspezioneClass.php';
require 'class/StabilimentoClass.php';
require 'class/UotRegioneClass.php';

$db = new DbConnect();
$db->open() or die($db->error());

$ispettoreClass= new IspettoreClass();
$ispezioneClass=new IspezioneClass();
$stabilimentoClass= new StabilimentoClass();
$uotRegione = new UotRegioneClass();

//get degli anni
$anni=$ispezioneClass->getAnniTutteIspezioni($db);
$annispez = array();
$i=0;
while ($row = $db->fetchassoc2($anni)) { 
    $annispez[$i] = $row["anno"];
    $i++;
}

$kXreg=array();
$kXter=array();

//Ispettori esperti
$outcsv.='ISPETTORI ESPERTI'.';';
$nIspett=$ispettoreClass->contaIspettori($db,1);
while($row1= $db->fetchassoc2($nIspett)){ 
    if($db->mysqli_real_escape($row1["cont"])>0){
        $outcsv.=$db->mysqli_real_escape($row1["cont"]).';';
        $outcsv.="\n";
    }
}
//Uditori
$outcsv.='UDITORI'.';';
$nIspett=$ispettoreClass->contaIspettori($db,2);
while($row1= $db->fetchassoc2($nIspett)){ 
    if($db->mysqli_real_escape($row1["cont"])>0){
        $outcsv.=$db->mysqli_real_escape($row1["cont"]).';';
    }
    $outcsv.="\n";
}
//salta una riga
$outcsv.="\n";
//Intestazione tabella
$outcsv.=";";
for($i=0;$i<count($annispez);$i++){
    $outcsv.=$annispez[$i].';';
}
$outcsv.="\n";

$outcsv.='ISPEZIONI ARCHIVIATE'.';';    //MODIFICA   del 21/04/2017
for($i=0;$i<count($annispez);$i++){
    $nIspez=$ispezioneClass->contaIspezioniAnno($db,1, $annispez[$i]); 
    while($row1= $db->fetchassoc2($nIspez)){ 
        if($db->mysqli_real_escape($row1["cont"])>0){
            $outcsv.=$db->mysqli_real_escape($row1["cont"]).';';
        }else{
            $outcsv.=";";
        }
    }
}
$outcsv.="\n";

$outcsv.='ISPEZIONI CONCLUSE'.';';    //MODIFICA   del 21/04/2017
for($i=0;$i<count($annispez);$i++){
    $nIspez=$ispezioneClass->contaIspezioniAnno($db,5, $annispez[$i]); 
    while($row1= $db->fetchassoc2($nIspez)){ 
        if($db->mysqli_real_escape($row1["cont"])>0){
            $outcsv.=$db->mysqli_real_escape($row1["cont"]).';';
        }else{
            $outcsv.=";";
        }
    }
}
$outcsv.="\n";

$outcsv.='ISPEZIONI IN CORSO'.';';
for($i=0;$i<count($annispez);$i++){
    $nIspez=$ispezioneClass->contaIspezioniAnno($db,2, $annispez[$i]); 
    while($row1= $db->fetchassoc2($nIspez)){ 
        if($db->mysqli_real_escape($row1["cont"])>0){
            $outcsv.=$db->mysqli_real_escape($row1["cont"]).';';
        }else{
            $outcsv.=";";
        }
    }
}
$outcsv.="\n";

$outcsv.='ISPEZIONI DA PIANIFICARE'.';';
for($i=0;$i<count($annispez);$i++){
    $nIspez=$ispezioneClass->contaIspezioniAnno($db,3, $annispez[$i]); 
    while($row1= $db->fetchassoc2($nIspez)){ 
        if($db->mysqli_real_escape($row1["cont"])>0){
            $outcsv.=$db->mysqli_real_escape($row1["cont"]).';';
        }else{
            $outcsv.=";";
        }
    }
}
$outcsv.="\n";

$outcsv.='ISPEZIONI SOSPESE'.';';
for($i=0;$i<count($annispez);$i++){
    $nIspez=$ispezioneClass->contaIspezioniAnno($db,4, $annispez[$i]); 
    while($row1= $db->fetchassoc2($nIspez)){ 
        if($db->mysqli_real_escape($row1["cont"])>0){
            $outcsv.=$db->mysqli_real_escape($row1["cont"]).';';
        }else{
            $outcsv.=";";
        }
    }
}
$outcsv.="\n";

$outcsv.='TOTALE ISPEZIONI'.';';
for($i=0;$i<count($annispez);$i++){
    $nIspez=$ispezioneClass->contaIspezioniAnno($db,0, $annispez[$i]); 
    while($row1= $db->fetchassoc2($nIspez)){ 
        if($db->mysqli_real_escape($row1["cont"])>0){
            $outcsv.=$db->mysqli_real_escape($row1["cont"]).';';
        }else{
            $outcsv.=";";
        }
    }
}
$outcsv.="\n";


//salta una riga
$outcsv.="\n";
$outcsv.='ISPEZIONI CONCLUSE, ARCHIVIATE, IN CORSO'.';';
$outcsv.="\n";
//Intestazione tabella
$outcsv.=";";
for($i=0;$i<count($annispez);$i++){
    $outcsv.=$annispez[$i].';';
}
$outcsv.="\n";

//MODIFICATA IL 28-04-2017

$outcsv.='ISPETTORI STESSA UOT'.';';
for($i=0;$i<count($annispez);$i++){
    //quelle concluse o archiviate
    $nIspez=$ispezioneClass->contaIspezioniStessaUOTAnno($db, $annispez[$i],1); 
    $k1=0;
    while($row1= $db->fetchassoc2($nIspez)){ 
        if($db->mysqli_real_escape($row1["cont"])>0){
            $k1=$db->mysqli_real_escape($row1["cont"]);
        }
    }
    //quelle assegnate
    $nIspez=$ispezioneClass->contaIspezioniStessaUOTAnno($db, $annispez[$i],2); 
    $k2=0;
    while($row1= $db->fetchassoc2($nIspez)){ 
        if($db->mysqli_real_escape($row1["cont"])>0){
            $k2=$db->mysqli_real_escape($row1["cont"]);
        }
    }
    $k1=$k1 + $k2;
    $outcsv.=$k1.";";
}
$outcsv.="\n";

$j=0;
for($i=0;$i<count($annispez);$i++){
    $isp=$ispezioneClass->getIspezioniDiversaUOTAnno($db, $annispez[$i],0);
    $reg=0;
    $ter=0;
    while($row10= $db->fetchassoc2($isp)){
        //verifica se l'uot dell'ispettore e dello stab hanno la stessa regione
        //get id regione della uot stabilimento
        $stabilimentoClass->setStabilimentoId($row10["stabIdFk"]);
        $stabTmp=$stabilimentoClass->getDettaglioStabilimento($db);
        $row11=$db->fetchassoc2($stabTmp);
        $regstabtmp=$uotRegione->getRegioneIdByUot($db, $row11["uotAffIdFk"]);
        $row14=$db->fetchassoc2($regstabtmp);
        $idRegStab=$row14["regioneIdFk"];
        //get uot dell'ispettore
        $ispettoreClass->setIspettoreId($row10["ispettIdFk"]);
        $ispetTmp=$ispettoreClass->getDettaglioIspettore($db);
        $row12=$db->fetchassoc2($ispetTmp);
        $regtmp=$uotRegione->getRegioneIdByUot($db, $row12["uotIspIdFk"]);
        $row13=$db->fetchassoc2($regtmp);
        $idRegIspet=$row13["regioneIdFk"];
        if($idRegStab!=$idRegIspet){
            //extraregionale
            $kXreg[$j]=$reg+1;
            $reg++;
        }else{
            //regionale
            $kXter[$j]=$ter+1;
            $ter++;
        }
    }
    $j++;
}
$outcsv.='ISPETTORI REGIONALI'.';';
for ($j=0;$j<count($kXreg);$j++){
    $outcsv.=$kXreg[$j].';';
}
$outcsv.="\n";
$outcsv.='ISPETTORI EXTRAREGIONALI'.';';
for ($j=0;$j<count($kXter);$j++){
    $outcsv.=$kXter[$j].';';
}

$outcsv.="\n";

// Download the file

$filename = "elenco_indicatori.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $outcsv;
exit;
ob_end_clean();

?>
