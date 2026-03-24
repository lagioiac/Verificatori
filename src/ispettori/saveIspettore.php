<?php
ob_start();
require 'config.php';
if ($_SESSION['user']['mask'] < 50) {
    ob_end_clean();
    header("Location: ../logout.php");
}
require '../db/mysql.php';
require '../class/IspettoreClass.php';

ob_start();
$db = new DbConnect();
$ispettoreClass = new IspettoreClass();

$db->open() or die($db->error());

//se arrivo da modifica
if ($_POST["anagraficaId"] != "") {

} else {
	if ($_POST["ispettoreNome"] == "" || $_POST["ispettoreCognome"] == "") {
        ob_end_clean();
        header("Location: ../aggiungi_ispettore.php?msg");
        exit();
    }
	
	$risultato = $anagraficaClass->insertIspettore($db, $_POST);
	
	ob_end_clean();
    header("Location: ../aggiungi_ispettore?succes&id=" . $risultato);
}

?>