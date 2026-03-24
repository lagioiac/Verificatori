<?php
ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/IspettoreClass.php';

ob_start();
$db = new DbConnect();
$ispettoreClass = new IspettoreClass();
$ruolocurr=0;

$db->open() or die($db->error());

//se arrivo da modifica
if ($_POST["ispettoreId"] != "") {
    $ispettoreClass->setIspettoreId($_POST["ispettoreId"]);
    
    if ($_POST["ispettoreNome"] == "" || $_POST["ispettoreCognome"] == "" || $_POST["uottmp"] == "" || $_POST["competenza"] == ""
            || $_POST["corsoformazione"] == ""){
        ob_end_clean();
        header("Location: aggiungi_ispettore.php?msg");
        exit();
    }    
    
    $ispettoreClass->updateIspettore($db, $_POST);
    ob_end_clean();
    header("Location: aggiungi_ispettore.php?succes&id=" . $ispettoreClass->getIspettoreId());
    exit();
    
} else {    //nuovo
	if ($_POST["ispettoreNome"] == "" || $_POST["ispettoreCognome"] == "" || $_POST["uottmp"] == "" || $_POST["competenza"] == ""
                || $_POST["corsoformazione"] == "") {
        ob_end_clean();
        header("Location: aggiungi_ispettore.php?msg");
        exit();
    }
	
	$risultato = $ispettoreClass->insertIspettore($db, $_POST);
	
	ob_end_clean();
    header("Location: aggiungi_ispettore.php?succes" . $risultato);
}


?>