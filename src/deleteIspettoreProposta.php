<?php

ob_start();
require 'config.php';
require 'db/mysql.php';
require 'class/PropostaIspezioneClass.php';

$db= new DbConnect();

$propostaispezioneClass=new PropostaIspezioneClass();

if(isset($_GET["pr"])) {
    $propispezioneId=$_GET["pr"];
    $propostaispezioneClass->setPropispezioneId($propispezioneId);
    
    if (isset($_GET["uo"])) {
        if($_GET["uo"]==0){
            if (isset($_GET["id"])) {
                if($_GET["id"]>0){
                    //elimina l'ispettore proposto
                    $propostaispezioneClass->deletePropostoIspettore($db);
                }
            }
        }else{
            if (isset($_GET["id"])) {
                if($_GET["id"]>0){
                    //elimina l'ispettore proposto
                    $propostaispezioneClass->deletePropostoIspettoreDaUot($db);
                }
            }
        }
    }
    
    if (isset($_GET["uo"])) {
        if($_GET["uo"]==0){
            if (isset($_GET["ud"])) {
                if($_GET["ud"]>0){
                    //elimina l'ispettore proposto
                    $propostaispezioneClass->deletePropostoUditore($db);
                }
            }
        }else{
            if (isset($_GET["ud"])) {
                if($_GET["ud"]>0){
                    //elimina l'ispettore proposto
                    $propostaispezioneClass->deletePropostoUditoreDaUot($db);
                }
            }
        }
    }
    
}

?>
