<?php

ob_start();
$pageMenu = "pianificastabilimenti";
require 'config.php';
require 'db/mysql.php';
require 'class/UotClass.php';
require 'class/IspezioneClass.php';
require 'class/IspettoreClass.php';
require 'class/CompetenzeClass.php';
require 'class/RuoloClass.php';
require 'class/PropostaIspezioneClass.php';
require 'class/StabilimentoClass.php';
require 'class/AttivitaIndustrialeClass.php';

require 'class/ProvinciaClass.php';
require 'class/ComuneClass.php';
require 'class/RegioneClass.php';
require 'class/UotRegioneClass.php';
include("include/check_user.php");

$pageName="UOT";
include 'include/header.php';

$db= new DbConnect();

$uotClass = new UotClass();
$ispezioneClass=new IspezioneClass();
$ispettoreClass=new IspettoreClass();
$competenzeClass = new CompetenzeClass();
$ruoloClass = new RuoloClass();

$propostaispezioneClass=new PropostaIspezioneClass();
$propIspettoreClass=new IspettoreClass();
$propUditoreClass=new IspettoreClass();

$stabilimentoClass=new StabilimentoClass();
$ispezioneCurrClass=new IspezioneClass();
$attivitaIndustrialeClass= new AttivitaIndustrialeClass();
$uot_regioneClass=new UotRegioneClass();

$provinciaClass = new ProvinciaClass();
$comuneClass=new ComuneClass();
$regioneClass = new RegioneClass();
$nomiProvinceComp = array();
$idProvinceComp = array();
$provinceregione=null;

$uot_regione=array();
$ispettoriCandidati=array();
$iIspettCand=0;
$uditoriCandidati=array();
$iUdCand=0;

$uot_candidati=array();
$uotTmpClass=new UotClass();

$db->open() or die($db->error());

$province = $provinciaClass->getProvince($db);

// MODIFICA 02-02-2017
if (isset($_GET["an"])) {
    $annocurr=$_GET["an"];
}

$uot = -1;
if (isset($_GET["id"])) {
    //id corrispondente allo stabilimento
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
}
if (isset($_GET["rg"])) {
    if($_GET["rg"]>0){
        //get delle uot della stessa regione, esclusa quella corrente
        $rg=$_GET["rg"];
        $regioneClass->setRegioneId($rg);
        
    }
}
if (isset($_GET["uid"])) {
    $uotId=$_GET["uid"];
    $uotClass->setUotId($uotId);
    // get di tutte le UOT della regione eccetto quella corrente
    $uot_regione=$uot_regioneClass->getUotRegione($db, $rg); 
    
    //get di tutte le uot esterne alla regione
    $uot_candidati=$uot_regioneClass->getUotOutRegione($db, $rg);
}
$propispId=0;
if (isset($_GET["pr"])) {
    $propispId=$_GET["pr"];
    $propostaispezioneClass->setPropispezioneId($propispId);
    $propostaispezioneClass->getDettaglioPropostaIspezione($db);
    $row7 = $db->fetchassoc();
    $propostaispezioneClass->setPropIspettIdFk($row7["propIspettIdFk"]);
    $propostaispezioneClass->setPropUditIdFk($row7["propUditIdFk"]);
    $propostaispezioneClass->setPropIspettDaUotIdFk($row7["propIspettDaUotIdFk"]);
    $propostaispezioneClass->setPropUditDaUotIdFk($row7["propUditDaUotIdFk"]);
    //agiunto 12-04-2017
    $propostaispezioneClass->setFlgPresenzaUd($row7["flgPresenzaUd"]);
}
?>
<link rel="stylesheet" href="/js/jquery-tagsinput/jquery.tagsinput.css">      
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="/js/bootstrap-fileupload/bootstrap-fileupload.min.css" />                 
<script type='text/javascript' src='/js/jquery-tagsinput/jquery.tagsinput.js'></script>
<script type="text/javascript" src="/js/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="/js/bootstrap-fileupload/bootstrap-fileupload.js"></script>

<script type="text/javascript">

		function cancellaIspettore(id,pr,uo){
			if(confirm("Sicuro di voler cancellare l'ispettore proposto? pr="+pr+"uo="+uo)){	
				$(location).attr('href',"deleteIspettoreProposta.php?&id="+id+"&ud=0&pr="+pr+"&uot="+uo);
			}
		}
                function cancellaUditore(ud,pr,uo){
			if(confirm("Sicuro di voler cancellare l'uditore proposto? pr="+pr+"ud="+ud+"uo="+uo)){	
				$(location).attr('href',"deleteIspettoreProposta.php?&id=0&ud="+ud+"&pr="+pr+"&uot="+uo);
			}
		}		
</script>

</head>

<body>
    <header>
	<div class="container">
    	<div class="row">
	<div class="span12">
        <div class="logo" style="border-bottom: 10px;"><a href="home.php">newRISPE</a><span>-UOT</span></div>   
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
                <li class="tre <?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
                <li class="quattro active<?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
            </ul> 
        </nav>
        </div>
    </header>
    <input type="hidden" name="ignora" id="uot" value="<?= $stabilimento ?>"></input>
    <section id="page" >
        <div class="container addnew">
            <div class="header">
                <h1>Pianifica ispezione per singolo stabilimento</h1>
                <form method="POST" action="ispezioni.php">
                    <aside>
                        <a href="pianificazione.php" class="back">Indietro</a>
                    </aside>
                </form>
            </div>
            <?php if (isset($_GET["msg"])) { ?>
            <div class="row">
                <div class="span12"><div class="alert alert-error">Compila tutti i campi obbligatori!</div></div>
            </div>
            <?php } ?>
            <?php if (isset($_GET["succes"])) { ?>
            <div class="row">
                    <div class="span12"><div class="alert alert-success">Salvataggio avvenuto!</div></div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="span12"><div class="title">Pianifica Ispezioni per Stabilimento</div></div>
            </div>
            <input type="hidden" name="indice" value="0" id="indice"></input>
            <div class="container scheda">
                <form method="POST" action="savePianificazioneStabilimento.php">
                    <input type="hidden" name="stabilimentoId" value="<?= $stabilimentoClass->getStabilimentoId() ?>">
                    <div class="row">
                        <div class="span3"><label>Stabilimento</label>
                            <span class="txtstatico"><?php echo $stabilimentoClass->getStabilimentoDenominazione();?></span>
                        </div>
                        <div class="span3"><label>Codice</label>
                            <span class="txtstatico"><?php echo $stabilimentoClass->getStabilimentoCodice();?></span>
                        </div>
                        <div class="span3"><label>Comune</label>
                            <span class="txtstatico"><?php 
                                $idCom=$stabilimentoClass->getComuneIdFk();
                                 $provinciacurr=$comuneClass->getProvByComune($db,$idCom );
                                 $r=$db->fetchassoc2($provinciacurr);
                                 echo $r["prov"];
                                ?></span>
                        </div>
                        <div class="span3"><label>Attività industriale</label>
                            <span class="txtstatico"><?php 
                                $idAtt= $stabilimentoClass->getAttivIndustrialeIdFk();
                                $attivcurr=$attivitaIndustrialeClass->getAttivitaById($db, $idAtt);
                                $r=$db->fetchassoc2($attivcurr);
                                echo $r["attivita"];
                                ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span3"><label>Ispettore proposto</label>
                            <span class="txtstatico"><?php 
                            $pr=$propostaispezioneClass->getPropIspettIdFk();
                            if($pr==""){    //get dell'ispettore proposto da uot solo se non è stato individuato altrimenti
                                $pr=$propostaispezioneClass->getPropIspettDaUotIdFk();
                                $ispUot=1;  //indica quello indicato da uot
                            }else{$ispUot=0;  //indica quello indicato da dit
                            }
                            if($pr!=""){
                                $propIspettoreClass->setIspettoreId($pr);
                                $propIspettore=$propIspettoreClass->getDettaglioIspettore($db);
                                $r=$db->fetchassoc2($propIspettore);
                                echo $r["ispettoreCognome"]." ".$r["ispettoreNome"]." ";
                                ?>
                            <?php } ?></span></div>
                        <div class="span3"><label>Uditore proposto</label>
                            <span class="txtstatico">
                            <!--   aggiunto controllo flag 12/04/2017           -->
                            <?php 
                            if($propostaispezioneClass->getFlgPresenzaUd()==0){
                                $pr=$propostaispezioneClass->getPropUditIdFk();
                                if($pr==""){    //get dell'uditore proposto da uot solo se non è stato individuato altrimenti
                                    $pr=$propostaispezioneClass->getPropUditDaUotIdFk();
                                    $ispUot=1;  //indica quello indicato da uot
                                }else{$ispUot=0;  //indica quello indicato da dit
                                }
                                if($pr!=""){
                                    $propUditoreClass->setIspettoreId($pr);
                                    $propUditore=$propUditoreClass->getDettaglioIspettore($db);
                                    $r=$db->fetchassoc2($propUditore);
                                    echo $r["ispettoreCognome"]." ".$r["ispettoreNome"]." ";
                                    ?>
                                    <!--   aggiunta cancellazione uditore 12/04/2017           -->
                                    <a href="eliminaUditoreDaPianificazioneStabilimento.php?pr=<?=$propispId?>&ud=<?=$propUditoreClass->getIspettoreId()?>"><img src="img/checkmark_red.png" alt="icon"></a>
                                <?php } 
                            }?>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <?php 
                        if(mysqli_num_rows($uot_regione)) { //  LISTA UOT DELLA STESSA REGIONE
                            while($row2=$db->fetchassoc2($uot_regione)){
                                if($row2["uotId"]!=$uotId){ //stessa regione ma UOT diverse
                                    //dalla uot get n° stabilimenti da ispezionare durante anno corrente
                                    $nstabisp=$uotClass->contaStabilimentiDaIspezByUot($db, $row2["uotId"],$annocurr);
                                    $k10=0;
                                    while($row30= $db->fetchassoc2($nstabisp)){ 
                                        if($db->mysqli_real_escape($row30["cont"])>0){
                                            $k10=$db->mysqli_real_escape($row30["cont"]);
                                        }
                                    }
                                    //dalla uot get numero ispettori esperti
                                    $nexp=$uotClass->contaRuoliByUot($db, $row2["uotId"], 1);
                                    $k11=0;
                                    while($row30= $db->fetchassoc2($nexp)){ 
                                        if($db->mysqli_real_escape($row30["cont"])>0){
                                            $k11=$db->mysqli_real_escape($row30["cont"]);
                                        }
                                    }
                                    ?><div class="span12"><label>UOT stessa regione: <?php echo $row2["uotDenominazione"]." Stabilimenti da ispezionare: ".$k10." - Ispettori esperti: ".$k11; ?></label>
                                    <!--  get tutti ispettori esperti che soddisfano i criteri: 
        1. numero ispezioni < 3 && disponibilità trasferta SI o NON so  -->
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="span3">Ispettore</th>
                                                <th class="span1">Ispez.ass.</th>
                                                <th class="span1">Ispez.prop.</th>
                                                <th class="span2">Competenza</th>
                                                <th class="span3">Esperienza Ispez.</th>
                                                <th class="span1">Disp.Trasf.</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            //get di tutti gli ispettori esperti della UOT
                                            $ispettoriCandidati[$iIspettCand]=$ispettoreClass->getIspettoriByUot($db,$row2["uotId"],1);
                                            if(mysqli_num_rows($ispettoriCandidati[$iIspettCand])){
                                                while($row3=$db->fetchassoc2($ispettoriCandidati[$iIspettCand])){ 
                                                    $ispdes=$row3["ispettoreId"];
                                                    $uddes="";
                                                    //ispezioni assegnate
                                                    $n=$ispezioneClass->contaIspezioniByIspettoreAnno($db, $db->mysqli_real_escape($row3["ispettoreId"]),$annocurr,2);
                                                    $r3= $db->fetchassoc2($n);
                                                    if($db->mysqli_real_escape($r3["cont"])>0){
                                                        $k=$db->mysqli_real_escape($r3["cont"]);
                                                    }else {$k=0;}
                                                    //ispezioni proposte
                                                    $n2=$ispezioneClass->contaIspezioniByIspettoreAnno($db, $db->mysqli_real_escape($row3["ispettoreId"]),$annocurr,3);
                                                    $r3= $db->fetchassoc2($n2);
                                                    if($db->mysqli_real_escape($r3["cont"])>0){
                                                        $k2=$db->mysqli_real_escape($r3["cont"]);
                                                    }else {$k2=0;}
                                                    ?>
                                                    <td valign="top"><?php
                                                    //31-01-2017
                                                    $ruolo=0;
                                                    if($row3["ruoloIdFk"]>0){
                                                        $ruolo=$row3["ruoloIdFk"];
                                                        $r=$ruoloClass->getRuoloById($db, $row3["ruoloIdFk"]);
                                                        $r3=$db->fetchassoc2($r);?>
                                                        <a href="aggiungi_ispettore.php?id=<?=$row3["ispettoreId"]?>" target=”_blank”><img src="<?=$r3["iconaruolo"]?>" alt="icon"><?=" ".$row3["ispettoreCognome"]." ".$row3["ispettoreNome"]?> </a>
                                                    </td>
                                                    <?php }?>
                                                    <td valign="top"><?php 
                                                        ?><?=$k ?>
                                                    </td>
                                                    <td valign="top"><?php
                                                        ?><?=$k2?>
                                                    </td>
                                                    <td valign="top">
                                                        <?php if($row3["compIdFk"]>0){  //Competenza ispettore
                                                            $comp=$competenzeClass->getCompetenzaById($db, $row3["compIdFk"]);
                                                            $r3=$db->fetchassoc2($comp);?>
                                                            <?=$r3["competenza"]?><?php
                                                        }else{?>
                                                            <?=""?>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td valign="top">
                                                        <?php //31-01-2017
                                                        if($ruolo==1){  //esperto
                                                            $esp=$ispettoreClass->getElencoEsperienzeIspettore($db, $row3["ispettoreId"]);
                                                            $listesp="";
                                                            while($r4=$db->fetchassoc2($esp)){
                                                                $listesp.=$db->mysqli_real_escape($r4["Esperienza"])."<br>";
                                                            }
                                                            ?><?=$listesp ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td valign="top"><?php
                                                        if($row3["flgDispTrasferta"]==1){
                                                            ?><?="Sì"?><?php
                                                        }elseif($row3["flgDispTrasferta"]==2){
                                                            ?><?="No"?><?php
                                                        }elseif($row3["flgDispTrasferta"]==0){
                                                            ?><?="Non so"?><?php
                                                        }
                                                    ?></td>
                                                    <td>
                                                        <a href="savePianificazioneStabilimento.php?pr=<?=$propispId?>&isp=<?=$ispdes?>&ud=<?=$uddes?>"><img src="img/checkmark_green.png" alt="icon"></a>
                                                    </td>
                                                  </tr>
                                                    <?php
                                                } 
                                            } 
                                            //Get UDITORI
                                            $uditoriCandidati[$iUdCand]=$ispettoreClass->getIspettoriByUot($db,$row2["uotId"],2);
                                            if(mysqli_num_rows($uditoriCandidati[$iUdCand])){
                                                while($row3=$db->fetchassoc2($uditoriCandidati[$iUdCand])){
                                                    $uddes=$row3["ispettoreId"];
                                                    $ispdes="";
                                                    ?><tr><?php 
                                                    $n=$ispezioneClass->contaIspezioniByUditoreAnno($db, $db->mysqli_real_escape($row3["ispettoreId"]),$annocurr,2);
                                                    $r3= $db->fetchassoc2($n);
                                                    if($db->mysqli_real_escape($r3["cont"])>0){
                                                        $k=$db->mysqli_real_escape($r3["cont"]);
                                                    }else {$k=0;}
                                                    $n2=$ispezioneClass->contaIspezioniByUditoreAnno($db, $db->mysqli_real_escape($row3["ispettoreId"]),$annocurr,3);
                                                    $r3= $db->fetchassoc2($n2);
                                                    if($db->mysqli_real_escape($r3["cont"])>0){
                                                        $k2=$db->mysqli_real_escape($r3["cont"]);
                                                    }else {$k2=0;}
                                                    ?>
                                                    <td valign="top"><?php
                                                        $ruolo=0;
                                                        if($row3["ruoloIdFk"]>0){
                                                            $ruolo=$row3["ruoloIdFk"];
                                                            $r=$ruoloClass->getRuoloById($db, $row3["ruoloIdFk"]);
                                                            $r3=$db->fetchassoc2($r);?>
                                                            <a href="aggiungi_ispettore.php?id=<?=$row3["ispettoreId"]?>" target=”_blank”><img src="<?=$r3["iconaruolo"]?>" alt="icon"><?=" ".$row3["ispettoreCognome"]." ".$row3["ispettoreNome"]?> </a>
                                                    </td>
                                                        <?php }?>
                                                    <td valign="top"><?=$k?></td>
                                                    <td valign="top"><?=$k2?></td>
                                                    <td valign="top">
                                                        <?php if($row3["compIdFk"]>0){
                                                            $comp=$competenzeClass->getCompetenzaById($db, $row3["compIdFk"]);
                                                            $r3=$db->fetchassoc2($comp);?>
                                                            <?=$r3["competenza"]?><?php
                                                        }else{?>
                                                            <?=""?>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td valign="top">
                                                        <?php 
                                                        if($ruolo==2){ //uditore    ESPERIENZA
                                                            $esp=$ispettoreClass->getElencoEsperienzeUditore($db, $row3["ispettoreId"]);
                                                            $listesp="";
                                                            while($r4=$db->fetchassoc2($esp)){
                                                                $listesp.=$db->mysqli_real_escape($r4["Esperienza"])."<br>";
                                                            }
                                                            ?><?=$listesp ?>
                                                            <?php
                                                        }else{ ?>
                                                            <?=""?>
                                                        <?php
                                                        }   ?>
                                                    </td>
                                                    <td valign="top"><?php
                                                        if($row3["flgDispTrasferta"]==1){
                                                            ?><?="Sì"?><?php
                                                        }
                                                    ?></td>
                                                    <td><a href="savePianificazioneStabilimento.php?pr=<?=$propispId?>&isp=<?=$ispdes?>&ud=<?=$uddes?>"><img src="img/checkmark_green.png" alt="icon"></a></td>
                                                <?php
                                                } 
                                            } ?></tr><?php
                                            ?>
                                        </tbody>
                                    </table>
                                    </div><?php
                                }
                            }
                        } ?>
                    </div>
                    <div class="row"> <?php
                        if(mysqli_num_rows($uot_candidati)) {   //  LISTA UOT DI ALTRE REGIONI
                            while($row20=$db->fetchassoc2($uot_candidati)){
                                //dalla uot get n° stabilimenti da ispezionare durante anno corrente
                                $nstabisp=$uotClass->contaStabilimentiDaIspezByUot($db, $row20["uotId"],$annocurr);
                                $k10=0;
                                while($row30= $db->fetchassoc2($nstabisp)){ 
                                    if($db->mysqli_real_escape($row30["cont"])>0){
                                        $k10=$db->mysqli_real_escape($row30["cont"]);
                                    }
                                }
                                //dalla uot get numero ispettori esperti
                                $nexp=$uotClass->contaRuoliByUot($db, $row20["uotId"], 1);
                                $k11=0;
                                while($row30= $db->fetchassoc2($nexp)){ 
                                    if($db->mysqli_real_escape($row30["cont"])>0){
                                        $k11=$db->mysqli_real_escape($row30["cont"]);
                                    }
                                }
                                ?>
                                <div class="span12"><label>Regione: <?php echo $row20["nomeregione"]." - ".$row20["uotDenominazione"]." Stabilimenti da ispezionare: ".$k10." - Ispettori esperti: ".$k11; ?></label>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="span3">Ispettore</th>
                                                <th class="span1">Ispez.ass.</th>
                                                <th class="span1">Ispez.prop.</th>
                                                <th class="span2">Competenza</th>
                                                <th class="span3">Esperienza Ispez.</th>
                                                <th class="span1">Disp.Trasf.</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            //get di tutti gli ispettori esperti della UOT
                                            $ispettoriCandidati[$iIspettCand]=$ispettoreClass->getIspettoriByUot($db,$row20["uotId"],1);
                                            if(mysqli_num_rows($ispettoriCandidati[$iIspettCand])){
                                                while($row3=$db->fetchassoc2($ispettoriCandidati[$iIspettCand])){
                                                    $ispdes=$row3["ispettoreId"];
                                                    $uddes="";  
                                                    //ISPEZIONI ASSEGNATE
                                                    $n=$ispezioneClass->contaIspezioniByIspettoreAnno($db, $db->mysqli_real_escape($row3["ispettoreId"]),$annocurr,2);
                                                    $r3= $db->fetchassoc2($n);
                                                    if($db->mysqli_real_escape($r3["cont"])>0){
                                                        $k=$db->mysqli_real_escape($r3["cont"]);
                                                    }else {$k=0;}
                                                    //ISPEZIONI PROPOSTE
                                                    $n2=$ispezioneClass->contaIspezioniByIspettoreAnno($db, $db->mysqli_real_escape($row3["ispettoreId"]),$annocurr,3);
                                                    $r3= $db->fetchassoc2($n2);
                                                    if($db->mysqli_real_escape($r3["cont"])>0){
                                                        $k2=$db->mysqli_real_escape($r3["cont"]);
                                                    }else {$k2=0;}
                                                    ?><tr>
                                                        <td valign="top">
                                                            <?php 
                                                            $ruolo=0;
                                                            if($row3["ruoloIdFk"]>0){
                                                                $ruolo=$row3["ruoloIdFk"];
                                                                $r=$ruoloClass->getRuoloById($db, $row3["ruoloIdFk"]);
                                                                $r3=$db->fetchassoc2($r);
                                                                ?>
                                                                <a href="aggiungi_ispettore.php?id=<?=$row3["ispettoreId"]?>" target=”_blank”><img src="<?=$r3["iconaruolo"]?>" alt="icon"><?=" ".$row3["ispettoreCognome"]." ".$row3["ispettoreNome"]?> </a>
                                                            <?php } ?>
                                                        </td>
                                                        <td valign="top"><?php 
                                                            ?><?=$k ?>
                                                        </td>
                                                        <td valign="top"><?php
                                                            ?><?=$k2?>
                                                        </td>
                                                        <td valign="top">
                                                            <?php if($row3["compIdFk"]>0){  //Competenza ispettore
                                                                $comp=$competenzeClass->getCompetenzaById($db, $row3["compIdFk"]);
                                                                $r3=$db->fetchassoc2($comp);?>
                                                                <?=$r3["competenza"]?><?php
                                                            }else{?>
                                                                <?=""?>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td valign="top">
                                                            <?php //31-01-2017
                                                            if($ruolo==1){  //esperto
                                                                $esp=$ispettoreClass->getElencoEsperienzeIspettore($db, $row3["ispettoreId"]);
                                                                $listesp="";
                                                                while($r4=$db->fetchassoc2($esp)){
                                                                    $listesp.=$db->mysqli_real_escape($r4["Esperienza"])."<br>";
                                                                }
                                                                ?><?=$listesp ?>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td valign="top"><?php
                                                            if($row3["flgDispTrasferta"]==1){
                                                                ?><?="Sì"?><?php
                                                            }elseif($row3["flgDispTrasferta"]==2){
                                                                ?><?="No"?><?php
                                                            }elseif($row3["flgDispTrasferta"]==0){
                                                                ?><?="Non so"?><?php
                                                            }
                                                        ?></td>
                                                        <td>
                                                            <a href="savePianificazioneStabilimento.php?pr=<?=$propispId?>&isp=<?=$ispdes?>&ud=<?=$uddes?>"><img src="img/checkmark_green.png" alt="icon"></a>
                                                        </td>
                                                    </tr><?php
                                                }
                                            }   
                                            //LISTA GLI UDITORI                              
                                            $uditoriCandidati[$iUdCand]=$ispettoreClass->getIspettoriByUot($db,$row20["uotId"],2);
                                            if(mysqli_num_rows($uditoriCandidati[$iUdCand])){
                                                while($row3=$db->fetchassoc2($uditoriCandidati[$iUdCand])){ 
                                                    $ispdes="";
                                                    $uddes=$row3["ispettoreId"];   
                                                    $n=$ispezioneClass->contaIspezioniByUditoreaNNO($db, $db->mysqli_real_escape($row3["ispettoreId"]),$annocurr,2);
                                                    $r3= $db->fetchassoc2($n);
                                                    if($db->mysqli_real_escape($r3["cont"])>0){
                                                        $k=$db->mysqli_real_escape($r3["cont"]);
                                                    }else {$k=0;}
                                                    $n2=$ispezioneClass->contaIspezioniByUditore($db, $db->mysqli_real_escape($row3["ispettoreId"]),3);
                                                    $r3= $db->fetchassoc2($n2);
                                                    if($db->mysqli_real_escape($r3["cont"])>0){
                                                        $k2=$db->mysqli_real_escape($r3["cont"]);
                                                    }else {$k2=0;}  ?>
                                                    <tr>
                                                        <td valign="top"><?php
                                                        $ruolo=0;
                                                        if($row3["ruoloIdFk"]>0){
                                                            $ruolo=$row3["ruoloIdFk"];
                                                            $r=$ruoloClass->getRuoloById($db, $row3["ruoloIdFk"]);
                                                            $r3=$db->fetchassoc2($r);?>
                                                            <a href="aggiungi_ispettore.php?id=<?=$row3["ispettoreId"]?>" target=”_blank”><img src="<?=$r3["iconaruolo"]?>" alt="icon"><?=" ".$row3["ispettoreCognome"]." ".$row3["ispettoreNome"]?> </a>
                                                            <?php } ?>
                                                        </td>
                                                        <td valign="top"><?=$k?></td>
                                                        <td valign="top"><?=$k2?></td>
                                                        <td valign="top">
                                                            <?php if($row3["compIdFk"]>0){
                                                                $comp=$competenzeClass->getCompetenzaById($db, $row3["compIdFk"]);
                                                                $r3=$db->fetchassoc2($comp);?>
                                                                <?=$r3["competenza"]?><?php
                                                            }else{?>
                                                                <?=""?>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td valign="top">
                                                            <?php //31-01-2017
                                                            if($ruolo==2){  //uditore
                                                                $esp=$ispettoreClass->getElencoEsperienzeUditore($db, $row3["ispettoreId"]);
                                                                $listesp="";
                                                                while($r4=$db->fetchassoc2($esp)){
                                                                    $listesp.=$db->mysqli_real_escape($r4["Esperienza"])."<br>";
                                                                }
                                                                ?><?=$listesp ?>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td valign="top"><?php
                                                            if($row3["flgDispTrasferta"]==1){
                                                                ?><?="Sì"?><?php
                                                            }elseif($row3["flgDispTrasferta"]==2){
                                                                ?><?="No"?><?php
                                                            }elseif($row3["flgDispTrasferta"]==0){
                                                                ?><?="Non so"?><?php
                                                            }
                                                        ?></td>
                                                        <td>
                                                            <a href="savePianificazioneStabilimento.php?pr=<?=$propispId?>&isp=<?=$ispdes?>&ud=<?=$uddes?>"><img src="img/checkmark_green.png" alt="icon"></a>
                                                        </td>
                                                    </tr>
                                        <?php   }
                                            }
                                            ?>
                                        </tbody>
                                    </table>  
                                </div><?php
                            }
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>