<?php

require 'config.php';
require 'db/mysql.php';
require 'class/ProvinciaClass.php';
include("include/check_user.php");

$db= new DbConnect();
$db->open() or die($db->error());

$name=@$_GET['term'];

if (!empty($name)){
    $return_arr=array();
    $provinciaClass=new ProvinciaClass();
    $provtmp=$provinciaClass->getAutocompleteProvince($db,$name);
    if(mysqli_num_rows($provtmp)>0){
        $vet=array();
        while ($row=$db->fetchassoc()){
            $vet[]='{"label":"'.$row["nome"].'", "value":"'.$row["nome"].'"}';
        }
        $product = implode(",",$vet);
    }
    echo"[".$product."]";
}else {
    
    echo"non ho trovato nulla";
}
?>
