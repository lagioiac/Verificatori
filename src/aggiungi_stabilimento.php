<?php

ob_start();
$pageMenu = "ispettore";
require 'config.php';
require 'db/mysql.php';
require 'class/StabilimentoClass.php';
require 'class/ComuneClass.php';
require 'class/UotClass.php';
require 'class/AttivitaIndustrialeClass.php';
require 'class/RuoloClass.php';

$pageName="STABILIMENTO";
include 'include/header.php';

//AGGIUNTA LISTA DELLE ISPEZIONI STABILIMENTO
include 'class/IspettoreClass.php';
include 'class/IspezioneClass.php';
$ispezioneClass = new IspezioneClass();
$ispettoreClass = new IspettoreClass();

$db= new DbConnect();
$db->open() or die($db->error());

$stabilimentoClass= new StabilimentoClass();
$comuneClass = new ComuneClass();
$uotClass = new UotClass();
$attivitaIndustrialeClass= new AttivitaIndustrialeClass();

$comuni = $comuneClass->getListaComuni($db);
$attivitaelenco = $attivitaIndustrialeClass->getListaAttivitaindustriale($db);

$stabilimento = -1;
//AGGIUNTO IL CAMPO periodo
$statoClass = new RuoloClass();
if (isset($_GET["id"])) {
    $stabilimento = $_GET["id"];
    $stabilimentoClass->setStabilimentoId($_GET["id"]);
    $stabilimentoClass->getDettaglioStabilimento($db);
    $row = $db->fetchassoc();
    
    $stabilimentoClass->setStabilimentoDenominazione($row["stabilimentoDenominazione"]);
    $stabilimentoClass->setStabilimentoCodice($row["stabilimentoCodice"]);
    $stabilimentoClass->setSoglia105($row["soglia105"]);
    $stabilimentoClass->setStabilimentoLoc($row["stabilimentoLoc"]);
    $stabilimentoClass->setComuneIdFk($row["comuneIdFk"]);
    $stabilimentoClass->setAttivIndustrialeIdFk($row["attivIndustrialeIdFk"]);
    $stabilimentoClass->setPeriodo($row["periodo"]);
    
            //AGGIUNTA LA QUERY DELLE ISPEZIONI ASSEGNATE O CONCLUSE PER LO STABILIMENTO
    $listaispezioni=$ispezioneClass->getIspezioniPerStabilimento($db, $_GET["id"]);
    $colore = array();
    $tipostato = array();
    $idstato=array();
    $elencostati = $statoClass->getStati($db);
    $i=1;
    while ($r = $db->fetchassoc2($elencostati)){
        $idruolo[$i] = $r["statoId"];
        $colore[$i] = $r["iconastato"];
        $tipostato[$i] = $r["stato"];
        $i++;
    }
}


$elencoperiodi = $statoClass->getPeriodi($db);
//tipo di ispezione
$ruoloClass = new RuoloClass();
$idtipoispez=array();
$tipoispez=array();
$abbrevtipoispez=array();
$i=1;
$elencotipiospezione=$ruoloClass->getTipiispezione($db);
while($t=$db->fetchassoc2($elencotipiospezione)){
    $idtipoispez[$i]=$t["tipoispezioneId"];
    $tipoispez[$i]=$t["tipoispezione"];
    $abbrevtipoispez[$i]=$t["abbrevtipoispezione"];
    $i++;
}

?>

<link rel="stylesheet" href="/js/jquery-tagsinput/jquery.tagsinput.css">      
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="/js/bootstrap-fileupload/bootstrap-fileupload.min.css" />                 
<script type='text/javascript' src='/js/jquery-tagsinput/jquery.tagsinput.js'></script>
<script type="text/javascript" src="/js/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="/js/bootstrap-fileupload/bootstrap-fileupload.js"></script>

<script type="text/javascript">

$(function () {
    
}
</script>

</head>
<body>
    <header>
	<div class="container">
    	<div class="row">
	<div class="span12">
        <div class="logo" style="border-bottom: 10px;"><a href="home.php">newRISPE</a></div>  
        <div class="info">
            <a href="dati.php">Dati</a>
            <a href="criteri.php">Criteri</a>
            <a href="logout.php">Logout</a>
        </div>
            </div>
        </div>
        <a href="javascript:;" class="mobilemenu">MENU</a>
        <nav>
                <ul>
                <li class="uno <?php echo $class_0;?>"><a href="ispettori.php"><img src="img/icon_1.png" alt="icon"><span class="none">Ispettori</span></a></li>
                <li class="due active<?php echo $class_1;?>"><a href="stabilimenti.php"><img src="img/icon_2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
                <li class="tre <?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
                <li class="quattro <?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
            </ul> 
        </nav>
 	</div>
    </header>
    <input type="hidden" name="ignora" id="stabilimento" value="<?= $stabilimento ?>">
    <section id="page" >
        <div class="container addnew">
            <div class="header">
        <h1>Stabilimento</h1>
        <aside>
            <a href="stabilimenti.php" class="back">Indietro</a>
        </aside>
        <?php if (isset($_GET["msg"])) { ?>
        <div class="row">
            <div class="span12"><div class="alert alert-error">Compila tutti i campi obbligatori!</div></div>
        </div>
        <?php } ?>
        <?php if (isset($_GET["msg2"])) { ?>
        <div class="row">
            <div class="span12"><div class="alert alert-error">Il codice inserito esiste già!</div></div>
        </div>
        <?php } ?>
        <?php if (isset($_GET["succes"])) { ?>
                <div class="row">
                        <div class="span12"><div class="alert alert-success">Salvataggio avvenuto!</div></div>
                </div>
        <?php } ?>
        <div class="row">
                <div class="span12"><div class="title"><?php
                if(isset($_GET["id"])){?>Modifica Stabilimento<?php }else{
                ?>Inserisci Stabilimento<?php } ?></div></div> 
        </div>
        <input type="hidden" name="indice" value="0" id="indice">
        <div class="container scheda">
            <form method="POST" action="saveStabilimento.php">
                <input type="hidden" name="stabilimentoId" value="<?= $stabilimentoClass->getStabilimentoId() ?>">
                <div class="row">
                    <div class="span8">
                        <label>Denominazione*</label>    
                        <input style="width: 760px" name="stabilimentoDenominazione" type="text" maxlength="200" value="<?= $stabilimentoClass->getStabilimentoDenominazione() ?>">
                    </div>
                    <div class="span4">
                        <label>Codice</label>    
                        <input style="width: 360px" name="stabilimentoCodice" type="text" maxlength="200" value="<?= $stabilimentoClass->getStabilimentoCodice() ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="span3">
                        <label>Localita'</label>    
                        <input style="width: 260px" name="stabilimentoLoc" type="text" maxlength="200" value="<?= $stabilimentoClass->getStabilimentoLoc() ?>">
                    </div>
                    <div class="span3">  
                        <label>Comune*</label>   
                        <select style="width: 260px;" name="comunestab" id="comunestab" >
                                <option value="">Seleziona</option>
                                <?php while ($row = $db->fetchassoc2($comuni)) { ?>
                                        <option value="<?= $row["comuneId"] ?>" 
                                        <?php 
                                        if ($stabilimentoClass->getComuneIdFk() == $row["comuneId"]) {
                                                echo 'selected'; 
                                        } ?> ><?= $row["comuneNome"] ?></option>
                                <?php } ?>
                        </select>
                    </div>
                    <div class="span6">  
                        <label>Attivita' Industriale*</label>   
                        <select style="width: 560px;" name="attiv" id="attiv" >
                                <option value="">Seleziona</option>
                                <?php while ($row3 = $db->fetchassoc2($attivitaelenco)) { ?>
                                        <option value="<?= $row3["attivitaindustrialeId"] ?>" 
                                        <?php 
                                        if ($stabilimentoClass->getAttivIndustrialeIdFk() == $row3["attivitaindustrialeId"]) {
                                                echo 'selected'; 
                                        } ?> ><?= $row3["attivita"] ?></option>
                                <?php } ?>
                        </select>
                    </div>
                    
                    <div class="span2">
                        <label>Indica Periodo</label> 
                        <select type="submit" style="width: 260px;" name="periodorif" id="periodorif" >
                                <option value="">Seleziona</option>
                                <?php while ($row20 = $db->fetchassoc2($elencoperiodi)) { ?>
                                        <option value="<?= $row20["periodoId"] ?>" 
                                        <?php 
                                        if ($stabilimentoClass->getPeriodo() == $row20["periodoId"]) {
                                                echo 'selected'; 
                                        } ?> ><?= $row20["periodo"] ?></option>
                                <?php } ?>
                        </select>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="span3">
                        <label>Soglia (d.lgs.105)*</label>    
                        <label>Superiore &nbsp;<input type="radio" name="sogliaTipo" value="1" <?php 
                        $tipo=$stabilimentoClass->getSoglia105();
                        if($tipo=="1" || !isset($tipo) ){ echo 'checked="checked"'; }
                        ?> style="display: inline;"/> &nbsp; Inferiore &nbsp; <input type="radio" name="sogliaTipo" <?php 
                        if($tipo=="0" ){ echo 'checked="checked"'; }?> value="0" /></label>
                    </div>
                </div>
                <div class="row">
                <div class="span12">
                    <button type="submit" class="mt0">Salva</button> 
                    <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>
                </div>
                </div>
                <?php if (isset($_GET["id"])) { ?>
                    <div class="row">
                        <div class="span12"><label>ISPEZIONI CONCLUSE O ASSEGNATE</label>
                            <table>
                                <thead>
                                    <tr>
                                        <th class="span1">Anno</th>
                                        <th class="span2">Tipo Ispez.</th>
                                        <th class="span4">Ispettore</th>
                                        <th class="span2">UOT</th>
                                        <th class="span4">Uditore</th>
                                        <th class="span2">UOT</th>
                                        <th class="span2">Stato Ispez.</th>
                                    </tr>
                                <tbody>
                                    <?php
                                    while($row2=$db->fetchassoc2($listaispezioni)){
                                        ?>
                                    <tr>
                                        <td valign="top"><?=$row2["anno"]?></td>
                                        <td valign="top"><?= $tipoispez[$row2["tipoispez"]]?></td>
                                        <?php 
                                        if($row2["ispettIdFk"]>0){ ?>
                                            <td valign="top">
                                                <?php 

                                                    $strtmp1=""; $k1="";
                                                    $strtmp2=""; $k2="";
                                                    if($row2["ispettIdFk"]>0){   //get ispettore
                                                        $isptmp=$ispettoreClass->getIspettoreById($db, $db->mysqli_real_escape($row2["ispettIdFk"]));
                                                        $row7=$db->fetchassoc2($isptmp);
                                                        $k1=$colore[$db->mysqli_real_escape($row7["ruoloIdFk"])];
                                                        $strtmp1=$db->mysqli_real_escape($row7["ispettoreCognome"])." ".$db->mysqli_real_escape($row7["ispettoreNome"]);
                                                    } 
                                                    if($strtmp1!=""){
                                                ?><a href="aggiungi_ispettore.php?id=<?=$row2["ispettIdFk"]?>" target=”_blank”><img src="<?=$k1?>" alt="icon"><?=$strtmp1."<br>"?> </a>
                                                    <?php } ?>
                                            </td>
                                        <td valign="top">
                                            <?php //get uot 
                                            $uottmp=$uotClass->getUotById($db, $db->mysqli_real_escape($row7["uotIspIdFk"]));
                                            $row6=$db->fetchassoc2($uottmp);
                                            ?>
                                            <a href="aggiungi_uot.php?id=<?=$row7["uotIspIdFk"]?>" target=”_blank”><?=$row6["uotDenominazione"]?></a>
                                        </td>
                                        <?php }else{ ?>
                                            <td valign="top"></td>
                                            <td valign="top"></td>
                                        <?php }
                                        if($row2["uditIdFk"]>0){
                                        ?>
                                            <td valign="top">
                                            <?php 
                                                $strtmp1=""; $k1="";
                                                $strtmp2=""; $k2="";
                                                if($row2["uditIdFk"]>0){   //get uditore
                                                    $isptmp=$ispettoreClass->getIspettoreById($db, $db->mysqli_real_escape($row2["uditIdFk"]));
                                                    $row7=$db->fetchassoc2($isptmp);
                                                    $k1=$colore[2]; //[$db->mysqli_real_escape($row7["ruoloIdFk"])]; //modifica del 28/04/2017
                                                    $strtmp1=$db->mysqli_real_escape($row7["ispettoreCognome"])." ".$db->mysqli_real_escape($row7["ispettoreNome"]);
                                                } 
                                                if($strtmp1!=""){
                                            ?><a href="aggiungi_ispettore.php?id=<?=$row2["uditIdFk"]?>" target=”_blank”><img src="<?=$k1?>" alt="icon"><?=$strtmp1."<br>"?> </a>
                                            <?php }
                                            ?>
                                            </td>
                                            <td valign="top">
                                                <?php //get uot 
                                                    $uottmp=$uotClass->getUotById($db, $db->mysqli_real_escape($row7["uotIspIdFk"]));
                                                    $row6=$db->fetchassoc2($uottmp);
                                                    ?>
                                                    <a href="aggiungi_uot.php?id=<?=$row7["uotIspIdFk"]?>" target=”_blank”><?=$row6["uotDenominazione"]?></a>
                                            </td>
                                        <?php } else { ?>
                                            <td valign="top"></td>
                                            <td valign="top"></td>
                                        <?php } ?>
                                        <td valign="top"><a href="aggiungi_ispezione.php?id=<?=$row2["ispezioneId"]?>"><?php    //28/01/2019
                                            if($row2["statoIdFk"]==1){ //archiviata
                                                ?><img src="<?=$colore[1]?>" alt="icon"><?= $tipostato[1]?><?php
                                            }elseif($row2["statoIdFk"]==2){ //assegnata
                                                ?><img src="<?=$colore[2]?>" alt="icon"><?= $tipostato[2]?><?php
                                            }elseif($row2["statoIdFk"]==3){ //da pianificare
                                                ?><img src="<?=$colore[3]?>" alt="icon"><?= $tipostato[3]?><?php
                                            }elseif($row2["statoIdFk"]==4){ //sospesa
                                                ?><img src="<?=$colore[4]?>" alt="icon"><?= $tipostato[4]?><?php
                                            }elseif($row2["statoIdFk"]==5){ //conclusa  modifica del 21/04/2017
                                                ?><img src="<?=$colore[5]?>" alt="icon"><?= $tipostato[5]?><?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    } ?>
                                </tbody>
                                </thead>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </form>
        </div>
    </section>
</body>