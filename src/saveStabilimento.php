<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/StabilimentoClass.php';

$db = new DbConnect();
$stabilimentoClass = new StabilimentoClass();

$db->open() or die($db->error());

//se arrivo da modifica
if ($_POST["stabilimentoId"] != "") {
    $stabilimentoClass->setStabilimentoId($_POST["stabilimentoId"]);
    
    if ($_POST["stabilimentoDenominazione"] == "" ){
        ob_end_clean();
        header("Location: aggiungi_stabilimento.php?msg");
        exit();
    }    
    
    $stabilimentoClass->updateStabilimento($db, $_POST);
    ob_end_clean();
    header("Location: aggiungi_stabilimento.php?succes&id=" . $stabilimentoClass->getStabilimentoId());
    exit();
}else {
    //20-05-2019    Aggiunto il controllo se il codice già esiste
    $ris=$stabilimentoClass->checkEsisteCodice($db, $_POST["stabilimentoCodice"] );
    if(mysqli_num_rows($ris)){
        //significa che il codice stabilimento già esiste, quindi si deve segnalare l'errore 
        ob_end_clean();
        header("Location: aggiungi_stabilimento.php?msg2");
        exit();
    }else{
        
        if ($_POST["stabilimentoDenominazione"] == "" ) { //|| $_POST["comunestab"] == "" || $_POST["attivita"] == ""
            ob_end_clean();
            header("Location: aggiungi_stabilimento.php?msg");
            exit();
        }
    }
	
	$risultato = $stabilimentoClass->insertStabilimento($db, $_POST);
	
	ob_end_clean();
    header("Location: aggiungi_stabilimento.php?succes" . $risultato);
}

?>
