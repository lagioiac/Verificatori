<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspezioneClass.php';
require 'class/StabilimentoClass.php';
require 'class/PropostaIspezioneClass.php';

$db = new DbConnect();
$ispezioneClass = new IspezioneClass();
$stabilimentoClass = new StabilimentoClass();
$propostaIspezioneClass=new PropostaIspezioneClass();

$db->open() or die($db->error());

//se arrivo da modifica
if ($_POST["ispezioneId"] != "") {
    
    if (($_POST["anno"] == "") || ($_POST["stabilimenti"] == "") || ($_POST["uotrif"] == "")) {
        ob_end_clean();
        header("Location: aggiungi_ispezione.php?msg");
        exit();
    }
    $ispezioneClass->setIspezioneId($_POST["ispezioneId"]);
    $ispezioneClass->getDettaglioIspezione($db);
    $ispezioneClass->updateIspezione($db, $_POST);
    
    $stabilimento=$stabilimentoClass->setStabilimentoId($db->mysqli_real_escape($_POST["stabilimenti"]));
    $stabilimento=$stabilimentoClass->updateUotAffStabilimento($db, $_POST["uotrif"]);
        
    //get proposta ispezione associata
        $idisp=$db->mysqli_real_escape($_POST["ispezioneId"]);
        $propostaispezione=$propostaIspezioneClass->getPropostaIspezioneByIspezione($db, $idisp);
        
        if(mysqli_num_rows($propostaispezione)>0){
            $p=$db->fetchassoc2($propostaispezione);
            $propostaIspezioneClass->setPropispezioneId($p["propispezioneId"]);
            $propostaIspezioneClass->updatePropostaIspezione($db, $idisp, $db->mysqli_real_escape($_POST["ispettprop"]), $db->mysqli_real_escape($_POST["uditprop"]),
                    $db->mysqli_real_escape($_POST["ispettpropdit"]), $db->mysqli_real_escape($_POST["uditpropdit"]));
        }else{
            //crea propostaispezione
            if(($_POST["ispettprop"]!="") && ($_POST["uditprop"]!="")){
                //ci sono entrambi
                $propostaIspezione=$propostaIspezioneClass->insertPropostaIspezione($db, $idisp, $db->mysqli_real_escape($_POST["ispettprop"]), $db->mysqli_real_escape($_POST["uditprop"]));
            }elseif(($_POST["ispettprop"]!="") && ($_POST["uditprop"]=="")){ //solo ispettore
                $propostaIspezione=$propostaIspezioneClass->insertPropostaIspezione($db, $idisp, $db->mysqli_real_escape($_POST["ispettprop"]), 0);
            }else{ //nulla, solo ispezione
                $propostaIspezione=$propostaIspezioneClass->insertPropostaIspezione($db, $idisp, 0, 0);
            }
        }

    ob_end_clean();
    header("Location: aggiungi_ispezione.php?succes&id=" . $ispezioneClass->getIspezioneId());
    exit();
    
}else{
    if (($_POST["anno"] == "") || ($_POST["stabilimenti"] == "") || ($_POST["uotrif"] == "")) {
        ob_end_clean();
        header("Location: aggiungi_ispezione.php?msg");
        exit();
    }
    $risultato = $ispezioneClass->insertIspezione($db, $_POST);
    
    if($risultato){
        $ispezione=$ispezioneClass->getLastRecord($db);
        //get id dello stabilimento
        $row=$db->fetchassoc2($ispezione);
        $idisp=$db->mysqli_real_escape($row["ispezioneId"]);
        
        $stabilimento=$stabilimentoClass->setStabilimentoId($db->mysqli_real_escape($row["stabIdFk"]));
        $stabilimento=$stabilimentoClass->updateUotAffStabilimento($db, $_POST["uotrif"]);
        
        //crea propostaispezione
        if(($_POST["ispettprop"]!="") && ($_POST["uditprop"]!="")){
            //ci sono entrambi
            $propostaIspezione=$propostaIspezioneClass->insertPropostaIspezione($db, $idisp, $db->mysqli_real_escape($_POST["ispettprop"]), $db->mysqli_real_escape($_POST["uditprop"]));
        }elseif(($_POST["ispettprop"]!="") && ($_POST["uditprop"]=="")){ //solo ispettore
            $propostaIspezione=$propostaIspezioneClass->insertPropostaIspezione($db, $idisp, $db->mysqli_real_escape($_POST["ispettprop"]), 0);
        }else{ //nulla, solo ispezione
            $propostaIspezione=$propostaIspezioneClass->insertPropostaIspezione($db, $idisp, 0, 0);
        }
        
    }
        
	ob_end_clean();
    header("Location: aggiungi_ispezione.php?succes" . $risultato);
}
?>
