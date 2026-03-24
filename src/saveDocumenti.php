<?php

ob_start();
require 'config.php';

require 'db/mysql.php';

require 'class/IspezioneClass.php';

$db = new DbConnect();
$ispezioneClass = new IspezioneClass();

$db->open() or die($db->error());

if ($_POST["ispezioneId"] != "") {
    //controlla se è stato modificato lo stato dell'ispezione
    $ispezioneClass->setIspezioneId($_POST["ispezioneId"]);
    $ispezioneClass->getDettaglioIspezione($db);
    $row = $db->fetchassoc();
    if($row["statoIdFk"]==STATO_CONCLUSA){
        //DEVE ESSERE MESSA A ARCHIVIATA    29/06/2017
        $ispezioneClass->updateStatoIspezione($db, STATO_ARCHIVIATA);
    }
    
    if(isset($_POST['rc'])){  $k=1;   //checked
        }else {   $k=0;}
    $ispezioneClass->updateRCdoc($db, $k);
    
    if(isset($_POST['st'])){  $k=1;   //checked
        }else {   $k=0;}
    $ispezioneClass->updateSTdoc($db, $k);
    
    if(isset($_POST['eo'])){  $k=1;   //checked
        }else {   $k=0;}
    $ispezioneClass->updateEOdoc($db, $k);
    
    if(isset($_POST['al'])){  $k=1;   //checked
        }else {   $k=0;}
    $ispezioneClass->updateALdoc($db, $k);
    
    if(isset($_POST['mi'])){  $k=1;   //checked //22-01-2019 - aggiunta
        }else {   $k=0;}
    $ispezioneClass->updateMIdoc($db, $k);

    ob_end_clean();
    header("Location: aggiungi_ispezione.php?succes");
    exit();
    
}

?>
