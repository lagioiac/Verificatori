<?php

ob_start();
$pageMenu="ispezioni";
require 'config.php';

require 'db/mysql.php';
require 'class/IspezioneClass.php';
require 'class/StabilimentoClass.php';
require 'class/UotClass.php';
require 'class/IspettoreClass.php';
require 'class/PropostaIspezioneClass.php';
require 'class/RuoloClass.php';
require 'class/ComuneClass.php';    //aggiunto per avere una lista composta da codice, stabilimento e comune di appartenenza

include("include/check_user.php");

$pageName="Ispezione";
include 'include/header.php'; 

$db= new DbConnect();
$db->open() or die($db->error());

$ispezioneClass=new IspezioneClass();
$stabilimentoClass= new StabilimentoClass();
$uotClass= new UotClass();
$ispettoreClass=new IspettoreClass();
$propostaIspezioneClass=new PropostaIspezioneClass();

//i:24/11/2016
$comuneClass = new ComuneClass();
//f

$ispezione=-1;

if (isset($_GET["id"])) {
    $ispezione = $_GET["id"];
    $ispezioneClass->setIspezioneId($_GET["id"]);
    $ispezioneClass->getDettaglioIspezione($db);
    $row = $db->fetchassoc();
    
    $ispezioneClass->setAnno($row["anno"]);
    $ispezioneClass->setStabIdFk($row["stabIdFk"]);
    $ispezioneClass->setStatoIdFk($row["statoIdFk"]);
    $ispezioneClass->setIspettIdFk($row["ispettIdFk"]);
    $ispezioneClass->setUditIdFk($row["uditIdFk"]);
    $ispezioneClass->setTipoispez($row["tipoispez"]);   //modificato il 15-02-2017
    $ispezioneClass->setNoteIspezione($row["noteIspezione"]);
    $ispezioneClass->setRcdoc($row["rcdoc"]);
    $ispezioneClass->setStdoc($row["stdoc"]);
    $ispezioneClass->setEodoc($row["eodoc"]);
    $ispezioneClass->setAldoc($row["aldoc"]);
    $ispezioneClass->setMidoc($row["midoc"]);   //22-01-2019 - aggiunto
    
    //get proposta ispezione
    $propostaIspezioneClass->getPropostaIspezioneByIspezione($db, $ispezioneClass->getIspezioneId());
    $row10=$db->fetchassoc();
    $propostaIspezioneClass->setPropispezioneId($row10["propispezioneId"]);
    $propostaIspezioneClass->setIspezioneId($row10["ispezioneIdFk"]);
    $propostaIspezioneClass->setPropIspettDaUotIdFk($row10["propIspettDaUotIdFk"]);
    $propostaIspezioneClass->setPropUditDaUotIdFk($row10["propUditDaUotIdFk"]);
    
    $propostaIspezioneClass->setPropIspettIdFk($row10["propIspettIdFk"]);
    $propostaIspezioneClass->setPropUditIdFk($row10["propUditIdFk"]);
    
    $stabilimentoClass->setStabilimentoId($row["stabIdFk"]);
    $stabilimentoClass->getDettaglioStabilimento($db);
    $rowx = $db->fetchassoc();
    $stabilimentoClass->setUotAffIdFk($rowx["uotAffIdFk"]);
}

//$stabilimenti=$stabilimentoClass->getListaStabilimenti($db);
$stabilimenti=$stabilimentoClass->getElencoStabilimenti($db);
$uotelenco=$uotClass->getUot($db);

$statoClass = new RuoloClass();
$colore = array();
$tipostato = array();
$idstato=array();
$elencostati = $statoClass->getStati($db);
$i=1;
while ($r = $db->fetchassoc2($elencostati)){
    $idstato[$i] = $r["statoId"];
    $colore[$i] = $r["iconastato"];
    $tipostato[$i] = $r["stato"];
    $i++;
}
//16-02-2017
$elencotipoispez = $statoClass->getTipiispezione($db);

?>
<link rel="stylesheet" href="/js/jquery-tagsinput/jquery.tagsinput.css">      
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="/js/bootstrap-fileupload/bootstrap-fileupload.min.css" />                 
<script type='text/javascript' src='/js/jquery-tagsinput/jquery.tagsinput.js'></script>
<script type="text/javascript" src="/js/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="/js/bootstrap-fileupload/bootstrap-fileupload.js"></script>

<script type="text/javascript">

$(document).ready(function(){

        $("select#stabilimenti").change(function () {
            var stab =$('select#stabilimenti option:selected').attr('value');
            $.post("selezioni_combinate.php", {id_stab:stab}, function(data){
                    $("#comune").html('data');	
            });
        });

        $("#aggiungi_immagine").click(function () {
            $("#altre_immagini").append('<div class="fileupload fileupload-new" data-provides="fileupload"><span class="button btn-file btn-large"><span class="fileupload-new">Seleziona file</span><span class="fileupload-exists">Cambia</span><input type="file" name="file[]" id="file"></span><span class="fileupload-preview"></span><a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a></div>');
            return false;
        });

    });
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
                <li class="due <?php echo $class_1;?>"><a href="stabilimenti.php"><img src="img/icon_2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
                <li class="tre active<?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
                <li class="quattro <?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
            </ul> 
        </nav>
 	</div>
    </header>
    <input type="hidden" name="ignora" id="ispezione" value="<?= $ispezione ?>">
    <section id="page" >
        <div class="container addnew">
            <div class="header">
        <h1>Ispezione</h1>
        <aside>
            <a href="ispezioni.php" class="back">Indietro</a>
        </aside>
        <?php if (isset($_GET["msg"])) { ?>
        <div class="row">
            <div class="span12"><div class="alert alert-error">Compila tutti i campi obbligatori!</div></div>
        </div>
        <?php } ?>
        <?php if (isset($_GET["msg2"])) { ?>
        <div class="row">
            <div class="span12"><div class="alert alert-error">Modificato lo stato ispezione!</div></div>
        </div>
        <?php } ?>
        <?php if (isset($_GET["msg3"])) { ?>
        <div class="row">
<!--            <div class="span12"><div class="alert alert-error">Manca il Rapporto Conclusivo!</div></div>-->
            <div class="span12"><div class="alert alert-error">L'ispezione è conclusa ma mancano i documenti!</div></div>
        </div>
        <?php } ?>
        <?php if (isset($_GET["msg4"])) { ?>
        <div class="row">
            <div class="span12"><div class="alert alert-error">forse!</div></div>
        </div>
        <?php } ?>
        <?php if (isset($_GET["succes"])) { ?>
                <div class="row">
                        <div class="span12"><div class="alert alert-success">Salvataggio avvenuto!</div></div>
                </div>
        <?php } ?>
        <div class="row">
                <div class="span12"><div class="title"><?php
                if(isset($_GET["id"])){?>Modifica Ispezione<?php }else{
                ?>Inserisci Ispezione (dati provenienti da CTR)<?php } ?></div></div>
        </div>
        <input type="hidden" name="indice" value="0" id="indice">
        <div class="container scheda">
            <form method="POST" action="saveIspezione.php" id="form_ispez">
                <input type="hidden" name="ispezioneId" value="<?= $ispezioneClass->getIspezioneId() ?>">
                <div class="row">
                    <div class="span2">
                        <label>Anno*</label>    
                        <input style="width: 170px" name="anno" type="text" maxlength="200" value="<?= $ispezioneClass->getAnno() ?>">
                    </div>
                    <div class="span5">
                        <label>Stabilimento*</label>    
                        <select type="submit" style="width: 470px;" name="stabilimenti" id="stabilimenti" >
                                <option value="">Seleziona</option>
                                <?php while ($row = $db->fetchassoc2($stabilimenti)) { ?>
                                        <option value="<?= $row["stabilimentoId"] ?>" 
                                        <?php 
                                        //i: 24/11/2016 get comune dello stabilimento
                                        $comuneClass->setComuneId($row["comuneIdFk"]);
                                        $comuneClass->getComuneStabById($db);
                                        $row_comune = $db->fetchassoc();
                                        //f
                                        if ($ispezioneClass->getStabIdFk() == $row["stabilimentoId"]) {
                                                echo 'selected'; 
                                        } ?> ><?= $row["stabilimentoCodice"]." - ".$row["stabilimentoDenominazione"]." - (".$row_comune["comuneNome"].")" ?></option>
                                <?php } ?>
                        </select>
                    </div>
                    <div class="span5">
                        <label>UOT di riferimento*</label>    
                        <select type="submit" style="width: 470px;" name="uotrif" id="uotrif" >
                                <option value="">Seleziona</option>
                                <?php while ($row2 = $db->fetchassoc2($uotelenco)) { ?>
                                        <option value="<?= $row2["uotId"] ?>" 
                                        <?php 
                                        if ($stabilimentoClass->getUotAffIdFk() == $row2["uotId"]) {
                                                echo 'selected'; 
                                        } ?> ><?= $row2["uotDenominazione"] ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="span2">
<!--   Modifica del 15-2-2017                     -->
                        <label>Tipo di Ispezione</label> 
                        <select type="submit" style="width: 470px;" name="tipoispezrif" id="tipoispezrif" >
                                <option value="">Seleziona</option>
                                <?php while ($row20 = $db->fetchassoc2($elencotipoispez)) { ?>
                                        <option value="<?= $row20["tipoispezioneId"] ?>" 
                                        <?php 
                                        if ($ispezioneClass->getTipoispez() == $row20["tipoispezioneId"]) {
                                                echo 'selected'; 
                                        } ?> ><?= $row20["tipoispezione"] ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                <?php
                if (isset($_GET["id"])) {   //in fase di modifica
                    //leggi lo stato ispezione:
                    $statoispez=$ispezioneClass->getStatoIdFk();
                    
                    ?><div class="span12">
                        <label>ISPETTORE E/O UDITORE PROPOSTI DAL RESPONSABILE UOT</label>
                      </div>
                      <div class="span4">
                        <label>Ispettore</label>
                        <?php $elencoispettuot=$ispettoreClass->getIspettoriByUot($db, $stabilimentoClass->getUotAffIdFk(), 1); ?>
                        <select type="submit" style="width:370px;" name="ispettprop" id="ispettprop" >
                            <option value="">Seleziona</option>
                            <?php while ($row3 = $db->fetchassoc2($elencoispettuot)) { ?>
                                <option value="<?= $row3["ispettoreId"] ?>" 
                                <?php 
                                if ($propostaIspezioneClass->getPropIspettDaUotIdFk() == $row3["ispettoreId"]) {
                                        echo 'selected'; 
                                } ?> ><?= $row3["ispettoreCognome"]." ".$row3["ispettoreNome"] ?></option>
                            <?php } ?>
                        </select>
                      </div>
                      <div class="span4">
                        <label>Uditore</label>
                        <?php $elencouduot=$ispettoreClass->getIspettoriByUot($db, $stabilimentoClass->getUotAffIdFk(), 2);?>
                        
                        <select type="submit" style="width:370px;" name="uditprop" id="uditprop" >
                            <option value="">Seleziona</option>
                            <?php while ($row4 = $db->fetchassoc2($elencouduot)) { ?>
                                <option value="<?= $row4["ispettoreId"] ?>" 
                                <?php 
                                if ($propostaIspezioneClass->getPropUditDaUotIdFk() == $row4["ispettoreId"]) {
                                        echo 'selected'; 
                                } ?> ><?= $row4["ispettoreCognome"]." ".$row4["ispettoreNome"] ?></option>
                            <?php } ?>
                        </select>
                      </div>
                    <div class="span4">
                        <?php if(($statoispez==0) || ($statoispez==3)){
                            ?>
                        <label>Conferma scelte UOT</label>
                        <button type="submit" class="mt0">Salva</button> 
                        <?php } ?>
                    </div>
                    <?php
                }else{
                    ?><div class="span4">
                        <label>Salva ispezione</label>
                        <button type="submit" class="mt0">Salva</button> 
                    </div><?php
                }
                ?>
                </div>   
                
                <div class="row">
                <?php
                if (isset($_GET["id"])) {   //in fase di modifica
                    ?><div class="span12">
                        <label>ISPETTORE E/O UDITORE PROPOSTI DAL DIT</label>
                      </div>
                      <div class="span4">
                        <label>Ispettore</label>
                        <?php $elencoispettuot=$ispettoreClass->getIspettoriByUot($db, $stabilimentoClass->getUotAffIdFk(), 1); ?>
                        <select type="submit" style="width:370px;" name="ispettpropdit" id="ispettpropdit" >
                            <option value="">Seleziona</option>
                            <?php while ($row3 = $db->fetchassoc2($elencoispettuot)) { ?>
                                <option value="<?= $row3["ispettoreId"] ?>" 
                                <?php 
                                if ($propostaIspezioneClass->getPropIspettIdFk() == $row3["ispettoreId"]) {
                                        echo 'selected'; 
                                } ?> ><?= $row3["ispettoreCognome"]." ".$row3["ispettoreNome"] ?></option>
                            <?php } ?>
                        </select>
                      </div>
                      <div class="span4">
                        <label>Uditore</label>
                        <?php $elencouduot=$ispettoreClass->getIspettoriByUot($db, $stabilimentoClass->getUotAffIdFk(), 2);?>
                        
                        <select type="submit" style="width:370px;" name="uditpropdit" id="uditpropdit" >
                            <option value="">Seleziona</option>
                            <?php while ($row4 = $db->fetchassoc2($elencouduot)) { ?>
                                <option value="<?= $row4["ispettoreId"] ?>" 
                                <?php 
                                if ($propostaIspezioneClass->getPropUditIdFk() == $row4["ispettoreId"]) {
                                        echo 'selected'; 
                                } ?> ><?= $row4["ispettoreCognome"]." ".$row4["ispettoreNome"] ?></option>
                            <?php } ?>
                        </select>
                      </div>
                    <div class="span4">
                        <?php if(($statoispez==0) || ($statoispez==3)){
                            ?>
                        <label>Conferma scelte DIT</label>
                        <button type="submit" class="mt0">Salva</button> 
                        <?php } ?>
                    </div>
                    <?php
                }
                ?>
                </div>
                
            </form>
            <form method="POST" action="saveStatoIspezione.php" id="form_ispez2">
                <input type="hidden" name="ispezioneId" value="<?= $ispezioneClass->getIspezioneId() ?>">
                <div class="row">
                    <div class="span12">
                        <label>STATO ISPEZIONE</label>
                    </div>
                    <div class="span9">
                        <label>Stato corrente</label>                        
                    <?php
                        $jj=$ispezioneClass->getStatoIdFk();
                        if($jj==0){$jj=3;}
                        if($jj==5){$jj=1;}  //Aggiunto il 09/05/2017 conclusa come archiviata
                        ?>    <input type="hidden" name="statomanuale" value="<?= $jj ?>"></input>
                        <div class="span2">
                        <img src="<?=$colore[2]?>" alt="icon"><?= $tipostato[2]?>
                        <input type="radio" name="flgStatoIspez" value="2" style="display: inline;" <?php if($jj==2){echo 'checked="checked"';}?>/>
                        </div>
                        <div class="span2">
                        <img src="<?=$colore[3]?>" alt="icon"><?= $tipostato[3]?>
                        <input type="radio" name="flgStatoIspez" value="3" style="display: inline;" <?php if($jj==3){echo 'checked="checked"';}?>/>
                        </div>
                        <div class="span2">
                        <img src="<?=$colore[1]?>" alt="icon"><?= $tipostato[1]?>    
                        <input type="radio" name="flgStatoIspez" value="1" style="display: inline;" <?php if($jj==1){echo 'checked="checked"';}?>/>
                        </div>
                        <div class="span2">
                        <img src="<?=$colore[4]?>" alt="icon"><?= $tipostato[4]?>    
                        <input type="radio" name="flgStatoIspez" value="4" style="display: inline;" <?php if($jj==4){echo 'checked="checked"';}?>/>
                        </div>
                        <div class="span4">
                            <input type="checkbox" name="noispez" /> Ispettore rinuncia ispezione (Stato: da pianificare)
                        </div>
                        <div class="span4">
                            <input type="checkbox" name="nouditore" /> Uditore non ha svolto l'ispezione (Stato: conclusa)
                        </div>
                    </div>
                    <div class="span3">
                        <label>Conferma modifica stato ispezione</label>
                    <button type="submit" class="mt0">Salva</button> 
                    </div>
                </div>
                <?php if($ispezioneClass->getIspettIdFk()!=""){ 
                    $ispettoreTmp=$ispettoreClass->getIspettoreById($db, $ispezioneClass->getIspettIdFk());
                    $row5 = $db->fetchassoc2($ispettoreTmp);
                    if($ispezioneClass->getUditIdFk()>0){
                        $uditoreTmp=$ispettoreClass->getIspettoreById($db, $ispezioneClass->getUditIdFk());
                        $row6 = $db->fetchassoc2($uditoreTmp);
                    }
                    ?> 
                    <div class="row">
                        <div class="span12">
                            <label>ISPEZIONE ASSEGNATA A</label>
                        </div>
                        <div class="span4">
                            <?php if ($jj==3) {?><label>Ispettore proposto</label><?php }else{ ?><label>Ispettore incaricato</label><?php } ?>
                            <input style="width: 370px" name="ispincaricato" type="text" maxlength="370" value="<?= $row5["ispettoreCognome"]." ".$row5["ispettoreNome"] ?>">
                        </div><?php if($ispezioneClass->getUditIdFk()>0){ ?>
                            <div class="span4">
                                <?php if ($jj==3) {?><label>Uditore proposto</label><?php }else{ ?><label>Uditore incaricato</label><?php } ?>
                                <input style="width: 370px" name="udincaricato" type="text" maxlength="370" value="<?= $row6["ispettoreCognome"]." ".$row6["ispettoreNome"] ?>">
                            </div>
                            <?php } else { ?><div class="span4"></div>
                                    <?php } ?>

                    </div>
                <?php } ?>
            </form>
            <form method="POST" action="saveNoteIspezione.php" id="form_ispez3">
                <input type="hidden" name="ispezioneId" value="<?= $ispezioneClass->getIspezioneId() ?>"></input>
                <div class="row">
                    <div class="span9">
                        <label>Note</label>   
                        <textarea style="width: 770px" name="noteIspezione" type="textarea" rows="4"><?= $ispezioneClass->getNoteIspezione() ?></textarea>
                    </div>
                    <div class="span3">
                        <label>Conferma note</label>
                        <button type="submit" class="mt0">Salva</button> 
                    </div>
                </div>
            </form>
            
            </form>
            <form method="POST" action="saveDocumenti.php" id="form_ispez3">
                <input type="hidden" name="ispezioneId" value="<?= $ispezioneClass->getIspezioneId() ?>"></input>
                <?php if($ispezioneClass->getIspettIdFk()!=""){ ?>
                <div class="row">
                    <div class="span12">
                        <label>DOCUMENTI</label>   
                    </div>
                    <div class="span9">
                        <label>Documenti ricevuti</label>
                        <div class="span3">
                            <?php if($ispezioneClass->getRcdoc()>0){$vrc=1;}else{$vrc=0;}?>
                            <input type="checkbox" name="rc" value="<?php $vrc?>"<?php if($vrc==1) {echo 'checked="checked"';} ?>/> Rapporto Conclusivo
                        </div>
                        <div class="span3">
                            <?php if($ispezioneClass->getStdoc()>0){$vst=1;}else{$vst=0;}?>
                            <input type="checkbox" name="st" value="<?php $vst?>"<?php if($vst==1) {echo 'checked="checked"';} ?>/> Sistemi Tecnici
                        </div>
                        <div class="span2">
                            <?php if($ispezioneClass->getMidoc()>0){$val=1;}else{$val=0;}?>
                            <input type="checkbox" name="mi" value="<?php $val?>"<?php if($val==1) {echo 'checked="checked"';} ?>/> Metodo Invecchiamento
                        </div>
                        <div class="span3">
                            <?php if($ispezioneClass->getEodoc()>0){$veo=1;}else{$veo=0;}?>
                            <input type="checkbox" name="eo" value="<?php $veo?>"<?php if($veo==1) {echo 'checked="checked"';} ?>/> Esperienza Operativa
                        </div>
                        <div class="span2">
                            <?php if($ispezioneClass->getAldoc()>0){$val=1;}else{$val=0;}?>
                            <input type="checkbox" name="al" value="<?php $val?>"<?php if($val==1) {echo 'checked="checked"';} ?>/> Altri
                        </div>
                    </div>
                    <div class="span3">
                        <label>Conferma documenti ricevuti</label>
                        <button type="submit" class="mt0">Salva</button> 
                    </div>
                    
                </div> <?php } ?>
            </form>
        </div>
    </section>
</body>