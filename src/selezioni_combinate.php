<?php

ob_start();
require 'config.php';

require 'db/mysql.php';
require 'class/StabilimentoClass.php';
require 'class/ComuneClass.php';


$db = new DbConnect();
$stabilimentoClass = new StabilimentoClass();

$db->open() or die($db->error());


if(isset($_POST['id_stab'])){
    $ok=$_POST['id_stab'];
    return $ok;
    //get del comune di appartenenza
//    $stabilimento = $_GET["id"];
//    $stabilimentoClass->setStabilimentoId($_GET["id"]);
//    $stabilimentoClass->getDettaglioStabilimento($db);
//    $row = $db->fetchassoc();
//    $comuneByStab=$stabilimentoClass->getComuneByStabilimento($db, $db->mysqli_real_escape($row["comuneIdFk"]));
//    $row2 = $db->fetchassoc2($comuneByStab);
//    return $db->mysqli_real_escape($row2["comuneNome"]);
}

?>
