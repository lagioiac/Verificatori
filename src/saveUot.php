<?php
ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/UotClass.php';
require 'class/UotRegioneClass.php';
require 'class/ProvinciaClass.php';

$db = new DbConnect();
$uotClass = new UotClass();
$uot_regioneClass= new UotRegioneClass();
$provinciaClass = new ProvinciaClass();

$idUot=null;
$idRegione=null;

$db->open() or die($db->error());

//se arrivo da modifica
if ($_POST["uotId"] != "") {
    $uotClass->setUotId($_POST["uotId"]);
    
    if (($_POST["uotDenominazione"] == "") || ($_POST["provincia"] == "")) {
        ob_end_clean();
        header("Location: aggiungi_uot.php?msg");
        exit();
    }    
    $uotClass->updateUot($db, $_POST);
    ob_end_clean();
    header("Location: aggiungi_uot.php?succes&id=" . $uotClass->getUotId());
    exit();
} else {
	if (($_POST["uotDenominazione"] == "") || ($_POST["provincia"] == "")) {
        ob_end_clean();
        header("Location: aggiungi_uot.php?msg");
        exit();
    }
        $den=$_POST["uotDenominazione"];
	$risultato = $uotClass->insertUot($db, $_POST);
        
	if($risultato){
            $uotClass->getIdUotByName($db, $den);
            $rw = $db->fetchassoc();
            
            $idProv=$db->mysqli_real_escape($_POST["provincia"]);
            $provinciaClass->getDettaglioProvincia($db,$idProv);
            $rw2 = $db->fetchassoc();
            
            $uot_regioneClass->insertUotRegione($db, $rw["uotId"],$rw2["regioneIdFk"]);
        }
	ob_end_clean();
    header("Location: aggiungi_uot.php?succes" . $risultato);
}

?>