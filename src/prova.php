<?php

include("config.php");
require 'db/mysql.php';
require 'class/StabilimentoClass.php';
require 'class/ComuneClass.php';

$stabilimentoClass=new StabilimentoClass();
$stabilimentoCurr=null;
$comuneClass=new ComuneClass();

ob_start();
$op = $_REQUEST['op'];

switch ($op) {
    case 1:
        $stabilimentoClass->setStabilimentoId($_GET["stabilimenti"]);
        $stabilimentoCurr=$stabilimentoClass->getDettaglioStabilimento($db);
        print_r($stabilimento["comuneIdFk"]);
        
        break;
}
?>
