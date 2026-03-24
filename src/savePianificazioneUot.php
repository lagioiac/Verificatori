<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/PropostaIspezioneClass.php';
require 'class/IspezioneClass.php';
require 'class/IspettoreClass.php';

$db = new DbConnect();
$ispezioneClass = new IspezioneClass();
$propostaispezioneClass = new PropostaIspezioneClass();
$ispettoreClass = new IspettoreClass();

$db->open() or die($db->error());

if (isset($_GET["pr"])) {
    if($_GET["pr"]>0){  //salvataggio di una singola proposta
        if($_GET["isp"]>0) {    //salvataggio uditore e ispettore
            $today = date("d-m-y");
            $data=explode("-", $today);
            $today= $data[2]."-".$data[1]."-".$data[0];
            
            $propostaispezioneClass->setPropispezioneId($_GET["pr"]);
            $propostaispezioneClass->getDettaglioPropostaIspezione($db);
            $row = $db->fetchassoc();
            //get ispezione
            $ispezioneClass->setIspezioneId($db->mysqli_real_escape($row["ispezioneIdFk"]));
            // get Id Ispettore designato
            if($db->mysqli_real_escape($row["propIspettIdFk"])>0){
                $idIspett=$db->mysqli_real_escape($row["propIspettIdFk"]);
            }elseif($db->mysqli_real_escape($row["propIspettDaUotIdFk"])>0){
                $idIspett=$db->mysqli_real_escape($row["propIspettDaUotIdFk"]);
            }else { //errore
                ob_end_clean();
                header("Location: aggiungi_pianificazioneUot.php?msg");
                exit();
            }
            if ($_GET["ud"]>0){
            // get Id Uditore designato
                if($db->mysqli_real_escape($row["propUditIdFk"])>0){
                    $idUdit=$db->mysqli_real_escape($row["propUditIdFk"]);
                }elseif($db->mysqli_real_escape($row["propUditDaUotIdFk"])>0){
                    $idUdit=$db->mysqli_real_escape($row["propUditDaUotIdFk"]);
                }else{$idUdit=0;}
            }
            //assegna ispettore e uditore se c'è
            $ispezioneClass->setIspettIdFk($idIspett);
            $ispezioneClass->setUditIdFk($idUdit);
            
            $ispezioneClass->setData_assegnaz($today);
            
            $risultato=$ispezioneClass->assegnaIspezione($db);
            
            //28/03/2017 Elimina eventuali rifiuti da parte dello stesso ispettore per la stessa ispezione
            $ispettoreClass->setIspettoreId($idIspett);
            $ispettoreClass->deleteRifiutoIspettoreDiIspezione($db, $idIspett,$row["ispezioneIdFk"]);
            
            ob_end_clean();
            header("Location: pianificazione.php?succes" . $risultato);
            exit();
        }elseif(($_GET["isp"]==0) && ($_GET["ud"]==0)){
            ob_end_clean();
            header("Location: pianificazione.php");
            exit();
        }
    }elseif($_GET["pr"]==0){    //salvataggio di tutte le proposte
        
    }
    
    ob_end_clean();
    header("Location: pianificazione.php?succes" . $risultato);
    exit();
}

?>
