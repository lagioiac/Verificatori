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
    
    $ispezioneClass->setNoteIspezione($row["noteIspezione"]);
    
    $ispezioneClass->updateNoteIspezione($db, $_POST["noteIspezione"]);
    
    
    ob_end_clean();
    header("Location: aggiungi_ispezione.php?succes");
    exit();

    
}

?>
