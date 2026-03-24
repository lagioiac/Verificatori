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
//ATTENZIONE: SALVA SOLO LA PIANIFICAZIONE NON L'ISPEZIONE
//
//!!!!!!!!!!!!!!!!!! da rivedere con calma sia l'algoritmo che il codice!!!!!!!!!!
//
//pr= id proposta ispezione, isp=id ispettore scelto, ud=uditore scelto
//algoritmo:
//caso1: proviene da una ispezione già assegnata ma si deve modificare l'ispettore
//aggiorna in propostaispezione: campo ispettore proposto da DIT
//analogo per eventuale uditore

//caso2: salva la proposta di ispezione con ispettore corrente
//se esiste ispettore proposto da DIT <> da quello corrente => aggiorna in propostaispezione campo ispettore proposto da DIT
//analogo per eventuale uditore

//caso3: volgio togliere l'uditore assegnato da UOT o DIT senza sostituirlo

if (isset($_GET["pr"])) {
    if($_GET["pr"]>0){  //salvataggio di una singola proposta
        if($_GET["isp"]>0) {    //salvataggio ispettore
            $idIspettCurr=$_GET["isp"];
            
            $propostaispezioneClass->setPropispezioneId($_GET["pr"]);
            $propostaispezioneClass->getDettaglioPropostaIspezione($db);
            $row = $db->fetchassoc();
            $propostaispezioneClass->setIspezioneId($db->mysqli_real_escape($row["ispezioneIdFk"]));
            // get Id Ispettore designato
            if($db->mysqli_real_escape($row["propIspettIdFk"])>0){  //era già stato designato un ispettore
                $idIspett=$db->mysqli_real_escape($row["propIspettIdFk"]);
                if($idIspett!=$idIspettCurr){   //se = non fa nulla, altrimenti inserisce quello nuovo
                    $propostaispezioneClass->setPropIspettIdFk($idIspettCurr);
                }
            }else {//non era stato designato nessun ispettore, controlla se assegnato da uot
                if($db->mysqli_real_escape($row["propIspettDaUotIdFk"])>0){ //assegnato da uot
                    $idIspett=$db->mysqli_real_escape($row["propIspettDaUotIdFk"]);
                    //controlla se è lo stesso, altrimenti lo salva
                    if($idIspett!=$idIspettCurr){
                        $propostaispezioneClass->setPropIspettIdFk($idIspettCurr);
                    }
                }else { //assegna ispettore
                    $propostaispezioneClass->setPropIspettIdFk($idIspettCurr);
                }
            }
        }
        if ($_GET["ud"]>0){
            $idUditCurr=$_GET["ud"];
            
            $propostaispezioneClass->setPropispezioneId($_GET["pr"]);
            $propostaispezioneClass->getDettaglioPropostaIspezione($db);
            $row = $db->fetchassoc();
            $propostaispezioneClass->setIspezioneId($db->mysqli_real_escape($row["ispezioneIdFk"]));
            // get Id uditore designato
            if($db->mysqli_real_escape($row["propUditIdFk"])>0){  //era già stato designato un uditore
                $idUdit=$db->mysqli_real_escape($row["propUditIdFk"]);
                if($idUdit!=$idUditCurr){   //se = non fa nulla, altrimenti inserisce quello nuovo
                    $propostaispezioneClass->setPropUditIdFk($idUditCurr);
                }
            }else {//non era stato designato nessun uditore, controlla se assegnato da uot
                if($db->mysqli_real_escape($row["propUditDaUotIdFk"])>0){ //assegnato da uot
                    $idUdit=$db->mysqli_real_escape($row["propUditDaUotIdFk"]);
                    //controlla se è lo stesso, altrimenti lo salva
                    if($idUdit!=$idUditCurr){
                        $propostaispezioneClass->setPropUditIdFk($idUditCurr);
                    }
                }else { //assegna uditore
                    $propostaispezioneClass->setPropUditIdFk($idUditCurr);
                }
            }
            //12-04-2017
            //se è stato assegnato un uditore il flag deve essere 0
            $propostaispezioneClass->updateFlagPresenzaUditore($db, 0);
        }
        
        //salva proposta ispezione
        $propostaispezioneClass->changePropostaIspezione($db);
    }
    
    ob_end_clean();
    header("Location: pianificazione.php?succes" . $risultato);
    exit();
}

?>
