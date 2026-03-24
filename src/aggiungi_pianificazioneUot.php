<?php
ob_start();
$pageMenu = "ispettori";
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
require 'class/RegioneClass.php';
include("include/check_user.php");

$pageName="UOT";
include 'include/header.php';

$db= new DbConnect();

$uotClass = new UotClass();
$ispezioneClass=new IspezioneClass();
$ispettoreClass=new IspettoreClass();
$ispettoreDesClass=new IspettoreClass();
$competenzeClass = new CompetenzeClass();
$ruoloClass = new RuoloClass();
$propostaispezioneClass=new PropostaIspezioneClass();
$stabilimentoClass=new StabilimentoClass();
$ispezioneCurrClass=new IspezioneClass();
$attivitaIndustrialeClass= new AttivitaIndustrialeClass();

$provinciaClass = new ProvinciaClass();
$regioneClass = new RegioneClass();
$nomiProvinceComp = array();
$idProvinceComp = array();
$provinceregione=null;
$db->open() or die($db->error());

$province = $provinciaClass->getProvince($db);

$uot = -1;

// MODIFICA 02-02-2017
if (isset($_GET["an"])) {
    $annocurr=$_GET["an"];
}

if (isset($_GET["id"])) {
    $uot = $_GET["id"];
    $uotClass->setUotId($_GET["id"]);
    $uotClass->getDettaglioUot($db);
    $row = $db->fetchassoc();
    $uotClass->setUotDenominazione($row["uotDenominazione"]);
    $uotClass->setUotIndirizzo($row["uotIndirizzo"]);
    $uotClass->setUotCap($row["uotCap"]);
    $uotClass->setUotPec($row["uotPec"]);
    $uotClass->setUotTelefono($row["uotTelefono"]);
    $uotClass->setUotFax($row["uotFax"]);
    $uotClass->setUotProvinciaFkId($row["provinciaFkId"]);   
    //get di tutte le ispezioni da programmare nella UOT
    $elencoispezionidapianificare=$ispezioneClass->getIspezioniDaPianificareByUot($db, $db->mysqli_real_escape($uotClass->getUotId()),$annocurr);    
    //get di tutti gli ispettori della uot 
    $elencoispettoriuot=$ispettoreClass->getIspettoriByUot($db, $db->mysqli_real_escape($uotClass->getUotId()),0);
    //get di tutti gli stabilimenti che sono stati considerati
    
    $reg=$provinciaClass->getRegioneProvincia($db, $db->mysqli_real_escape($row["provinciaFkId"]));
    $reg = $db->fetchassoc();
    $regionecurr=$db->mysqli_real_escape($reg["regioneId"]);
    $provinceregione=$provinciaClass->getProvinceStessaRegione($db, $db->mysqli_real_escape($reg["regioneId"]));
    $i=0;
    while ($r = $db->fetchassoc2($provinceregione)){
        $nomiProvinceComp[$i] = $r["prov"];
        $idProvinceComp[$i] = $r["provinciaId"];
        $i++;
    }
}
if (isset($_GET["rg"])) {
    if($_GET["rg"]>0){
        //get delle uot della stessa regione, esclusa quella corrente
        $rg=$_GET["rg"];
        $regioneClass->setRegioneId($rg);
    }else{
        $rg=0;
    }
}

?>

<link rel="stylesheet" href="/js/jquery-tagsinput/jquery.tagsinput.css">      
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="/js/bootstrap-fileupload/bootstrap-fileupload.min.css" />                 
<script type='text/javascript' src='/js/jquery-tagsinput/jquery.tagsinput.js'></script>
<script type="text/javascript" src="/js/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="/js/bootstrap-fileupload/bootstrap-fileupload.js"></script>

<script>
    $(document).ready(function () {
        
    });
</script>
<scrpit>
    
</scrpit>

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
    <input type="hidden" name="ignora" id="uot" value="<?= $uot ?>">
    <section id="page" >
        <div class="container addnew">
        <div class="header">
            <h1>UOT</h1>
            <form method="POST" action="ispezioni.php">
            <aside>
<!--                <a href="aggiungi_pianificazioneUot.php?id=<?=$row["uotId"]?>&rg=<?=$regionecurr?>" class="button add">Altre UOT</a>-->
                <a href="csv_pianificazione.php?id=<?=$uot?>&an=<?=$annocurr?>" class="button">Esporta Excel</a>
                <a href="pianificazione.php" class="back">Indietro</a>
            </aside>
            </form>
        </div>
        <?php if (isset($_GET["msg"])) { ?>
            <div class="row">
                <div class="span12"><div class="alert alert-error">Assegnare Ispettore!</div></div>
            </div>
            <?php } ?>
        <?php if (isset($_GET["succes"])) { ?>
                <div class="row">
                        <div class="span12"><div class="alert alert-success">Salvataggio avvenuto!</div></div>
                </div>
        <?php } ?>
        <div class="row">
        <?php  if($rg==0){ ?>
                <div class="span12"><div class="title">Pianifica Ispezioni per Anno: <?= $annocurr ?>
                    </div></div>
        <?php }else{ ?>
            <div class="span12"><div class="title">Pianifica Ispezioni per Regione: <?= $regionecurr; ?>
                    </div></div>
        <?php } ?>
        </div>
        <input type="hidden" name="indice" value="0" id="indice">
        <div class="container scheda">
            <form method="POST" action="savePianificazioneUot.php">
                <input type="hidden" name="uotId" value="<?= $uotClass->getUotId() ?>">
                <div class="row">
                    <div class="span12">
                        <label>Ispettori: <?= $uotClass->getUotDenominazione() ?></label>    
                        <table>
                            <thead>
                                <tr>
                                    <th class="span2">Ispettore</th>
                                    <th class="span2">Ispez.assegnate</th>
                                    <th class="span2">Competenza</th>
                                    <th class="span2">Ruolo</th>
                                    <th class="span2">Esperienza Ispez.</th>
                                    <th class="span2">Design.da UOT</th>
                                    <th width="50px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(mysqli_num_rows($elencoispettoriuot)){ 
                                    while($row2=$db->fetchassoc2($elencoispettoriuot)){?>
                                    <tr>
                                        <td valign="top"><?=$row2["ispettoreCognome"]." ".$row2["ispettoreNome"]?></td>
                                        <td valign="top">
                                            <?php
                                        if($row2["ruoloIdFk"]==1){
                                            //conta il numero di ispezioni assegnate per ispettore corrente
                                            //RINOMINA E MODIFICA 02-02-2017
                                            //$nIspezAnno=$ispettoreClass->contaIspezioniByIspettore($db, $row2["ispettoreId"], 2);                                        
                                            $nIspezAnno=$ispettoreClass->contaIspezioniByIspettoreAnno($db, $row2["ispettoreId"], 2,  $annocurr);                                        
                                            $k1=0;
                                            while($row30= $db->fetchassoc2($nIspezAnno)){ 
                                                if($db->mysqli_real_escape($row30["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row30["cont"]);
                                                }
                                        }}elseif($row2["ruoloIdFk"]==2){
                                            //conta il numero di ispezioni assegnate per uditore corrente
                                            //RINOMINA E MODIFICA 02-02-2017
//                                            $nIspezAnno=$ispettoreClass->contaIspezioniByUditore($db, $row2["ispettoreId"], 2);   
                                            $nIspezAnno=$ispettoreClass->contaIspezioniByUditoreAnno($db, $row2["ispettoreId"], 2, $annocurr);     
                                            $k1=0;
                                            while($row30= $db->fetchassoc2($nIspezAnno)){ 
                                                if($db->mysqli_real_escape($row30["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row30["cont"]);
                                                }
                                            }
                                        }
                                        ?><?=$k1 ?>
                                        </td>
                                        <td valign="top">
                                            <?php if($row2["compIdFk"]>0){
                                                $comp=$competenzeClass->getCompetenzaById($db, $row2["compIdFk"]);
                                                $row3=$db->fetchassoc2($comp);?>
                                                <?=$row3["competenza"]?><?php
                                            }else{?>
                                                <?=""?>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td valign="top">
                                            <?php //31-01-2017
                                            $ruolo=0;
                                            if($row2["ruoloIdFk"]>0){
                                                $ruolo=$row2["ruoloIdFk"];
                                                $r=$ruoloClass->getRuoloById($db, $row2["ruoloIdFk"]);
                                                $row3=$db->fetchassoc2($r);
                                                ?>
                                                <img src="<?=$row3["iconaruolo"]?>" alt="icon"><?= $row3["ruolo"]?><?php
                                            }else{?>
                                                <?=""?>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td valign="top">
                                            <?php //31-01-2017
                                            if($ruolo==1){  //esperto
                                                $esp=$ispettoreClass->getElencoEsperienzeIspettore($db, $row2["ispettoreId"]);
                                                $listesp="";
                                                while($r4=$db->fetchassoc2($esp)){
                                                    $listesp.=$db->mysqli_real_escape($r4["Esperienza"])."<br>";
                                                }
                                                ?><?=$listesp ?></td>
                                                <?php
                                            }elseif($ruolo==2){ //uditore
                                                $esp=$ispettoreClass->getElencoEsperienzeUditore($db, $row2["ispettoreId"]);
                                                $listesp="";
                                                while($r4=$db->fetchassoc2($esp)){
                                                    $listesp.=$db->mysqli_real_escape($r4["Esperienza"])."<br>";
                                                }
                                                ?><?=$listesp ?></td>
                                                <?php
                                            }else{?>
                                                <?=""?>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <?php 
                                        if($row2["ruoloIdFk"]==1){
                                            //RINOMINA E MODIFICA 02-02-2017
//                                            $propostiispettori=$propostaispezioneClass->getPropostaIspezioneByIspettore($db, $row2["ispettoreId"]);
                                            $propostiispettori=$propostaispezioneClass->getPropostaIspezioneByIspettoreAnno($db, $row2["ispettoreId"], $annocurr);
                                        }elseif($row2["ruoloIdFk"]==2){
                                            //RINOMINA E MODIFICA 02-02-2017
//                                            $propostiispettori=$propostaispezioneClass->getPropostaIspezioneByUditore($db, $row2["ispettoreId"]);
                                            $propostiispettori=$propostaispezioneClass->getPropostaIspezioneByUditoreAnno($db, $row2["ispettoreId"], $annocurr);
                                        }
                                        $npropisptmp=0;
                                        if(mysqli_num_rows($propostiispettori)>0){
                                            $npropisptmp=mysqli_num_rows($propostiispettori);
                                            ?>
                                            <td valign="top"><?=$npropisptmp?></td>  
                                        <?php }else{
                                        ?><td valign="top"></td><?php } ?>
                                        <td><a href="aggiungi_ispettore.php?id=<?=$row2["ispettoreId"]?>"><img src="img/icon-mod.png" alt="icon"></a></td>
                                    </tr>
                                <?php }
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                <div class="span12">
                    <label>Stabilimenti</label>    
                    <table>
                        <thead>
                            <tr>
                                <th class="span2"> Stabilimento</th>
                                <th class="span3"> Attiv.industriale</th>
                                <th class="span2"> Ultima ispez.</th>
                                <th class="span2"> Ispettore proposto</th>
                                <th class="span2"> Uditore proposto</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                //get delle proposte NON ancora assegnate, cioè con lo stato = 3 SOLO ISPETTORI UOT
                            //MODIFICA 02-02-2017   
//                                $propostaispezione=$propostaispezioneClass->getProposteIspezioniByUot($db, $db->mysqli_real_escape($uotClass->getUotId()));
                                $propostaispezione=$propostaispezioneClass->getProposteIspezioniByUot($db, $db->mysqli_real_escape($uotClass->getUotId()),$annocurr);
                                $n=mysqli_num_rows($propostaispezione);
                                if(mysqli_num_rows($propostaispezione)>0){
                                    while($row4=$db->fetchassoc2($propostaispezione)){
                                    ?><tr><?php
                                        //get stabilimento
                                        $ispezioneCurrClass->setIspezioneId($row4["ispezioneIdFk"]);
                                        $ispcurr=$ispezioneCurrClass->getDettaglioIspezione($db);
                                        $row5=$db->fetchassoc2($ispcurr);

                                        $stabilimentoClass->setStabilimentoId($row5["stabIdFk"]);
                                        $st=$stabilimentoClass->getDettaglioStabilimento($db);
                                        $row6=$db->fetchassoc2($st);
                                        $stabtmp.=$db->mysqli_real_escape($row6["stabilimentoDenominazione"])."<br>";
                                        ?><td valign="top"><?=$row6["stabilimentoDenominazione"]?></td>
                                        <td valign="top">
                                            <?php if($row6["attivIndustrialeIdFk"]>0){
                                            $att=$attivitaIndustrialeClass->getAttivitaById($db, $row6["attivIndustrialeIdFk"]);
                                            $row7=$db->fetchassoc2($att);?>
                                            <?=$row7["attivita"]?><?php
                                        }else{?>
                                            <?=""?>
                                        <?php
                                        }
                                        ?>
                                        </td>
                                        <td valign="top">
                                           
                                        </td>
                                        <td valign="top"><?php
                                            $propispezione=$propostaispezioneClass->getPropostaIspezioneByIspezione($db, $row4["ispezioneIdFk"]);
                                            $row8=$db->fetchassoc2($propispezione);
                                            if($row8["propIspettIdFk"]>0){  //quello designato da DIT
                                                $ispettoreDesClass->setIspettoreId($row8["propIspettIdFk"]);
                                                $ispettDes=$ispettoreDesClass->getDettaglioIspettore($db);
                                                $row9=$db->fetchassoc2($ispettDes);
                                                $ispdes=$row9["ispettoreId"];?>
                                                <?=$row9["ispettoreCognome"]?><?php
                                            }elseif($row8["propIspettDaUotIdFk"]>0){
                                                $ispettoreDesClass->setIspettoreId($row8["propIspettDaUotIdFk"]);
                                                $ispettDes=$ispettoreDesClass->getDettaglioIspettore($db);
                                                $row9=$db->fetchassoc2($ispettDes);
                                                $ispdes=$row9["ispettoreId"];?>
                                                <?=$row9["ispettoreCognome"]?><?php
                                            }else $ispdes=0;
                                        ?>
                                        </td>
                                        <td valign="top"><?php
                                            //$propispezione=$propostaispezioneClass->getPropostaIspezioneByIspezione($db, $row4["ispezioneIdFk"]);
                                            //$row8=$db->fetchassoc2($propispezione);
                                        // 12/04/2017    Inserito il controllo sul flag flgPresenzaUd
                                            if($row8["flgPresenzaUd"]==1){
                                                //non scrivere nulla
                                                $uddes=0;
                                            }else{
                                                if($row8["propUditIdFk"]>0){
                                                    $ispettoreDesClass->setIspettoreId($row8["propUditIdFk"]);
                                                    $uditDes=$ispettoreDesClass->getDettaglioIspettore($db);
                                                    $row9=$db->fetchassoc2($uditDes);
                                                    $uddes=$row9["ispettoreId"];?>
                                                    <?=$row9["ispettoreCognome"]?><?php
                                                }elseif($row8["propUditDaUotIdFk"]>0){
                                                    $ispettoreDesClass->setIspettoreId($row8["propUditDaUotIdFk"]);
                                                    $uditDes=$ispettoreDesClass->getDettaglioIspettore($db);
                                                    $row9=$db->fetchassoc2($uditDes);
                                                    $uddes=$row9["ispettoreId"];?>
                                                    <?=$row9["ispettoreCognome"]?><?php
                                                }else $uddes=0;
                                            }
                                        ?>
                                        </td>
<!--      MODIFICA 02-02-2017                                  -->
                                        <td><a href="aggiungi_pianificazioneStabilimento.php?id=<?=$row6["stabilimentoId"]?>&rg=<?=$regionecurr?>&uid=<?=$uotClass->getUotId()?>&pr=<?=$row4["propispezioneId"]?>&an=<?=$annocurr?>"><img src="img/icon-mod.png" alt="icon"></a></td>
                                        <td><a href="savePianificazioneUot.php?pr=<?=$row4["propispezioneId"]?>&isp=<?=$ispdes?>&ud=<?=$uddes?>&an=<?=$annocurr?>"><img src="img/checkmark_green.png" alt="icon"></a></td>
                                <?php } ?>
                                    </tr><?php 
                                    }
                               
                                    
                                    ?>
                                
                            
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="row">
                    <div class="span12">
<!--                        <button type="submit" class="mt0">Assegna</button> -->
<!--                        <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>-->
                    </div>
                </div>   

            </form>
        </div>
        </div>
        </div>
    </section>
	
<?php include '../include/footer.php'; ?> 
</body>
</html>
