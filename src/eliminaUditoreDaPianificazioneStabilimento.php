<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/PropostaIspezioneClass.php';
require 'class/IspezioneClass.php';

$db = new DbConnect();
$ispezioneClass = new IspezioneClass();
$propostaispezioneClass = new PropostaIspezioneClass();

$db->open() or die($db->error());
//ATTENZIONE: ELIMINA L'UDITORE DALLA PIANIFICAZIONE 
//

//caso3: volgio togliere l'uditore assegnato da UOT o DIT senza sostituirlo

if (isset($_GET["pr"])) {
    if($_GET["pr"]>0){  // proposta di ispezione

        if ($_GET["ud"]>0){
            $idUditCurr=$_GET["ud"];   //id uditore
            
            $propostaispezioneClass->setPropispezioneId($_GET["pr"]);

            $propostaispezioneClass->updateFlagPresenzaUditore($db, 1);
            //se l'uditore è stato assegnato da DIT deve essere rimosso!!!!!??????
            

        }
    }
    
    ob_end_clean();
    header("Location: pianificazione.php?succes" . $risultato);
    exit();
}

?>
