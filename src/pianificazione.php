<?php

ob_start();
$pageMenu="pianificazione";
require 'config.php';

require 'db/mysql.php';
require 'class/IspezioneClass.php';
require 'class/StabilimentoClass.php';
require 'class/UotClass.php';
require 'class/RegioneClass.php';
require 'class/IspettoreClass.php';
include("include/check_user.php");

$pageName="PIANIFICAZIONE";
include 'include/header.php'; 

$db= new DbConnect();
$db->open() or die($db->error());

$ispezioneClass=new IspezioneClass();
$stabilimentoClass= new StabilimentoClass();
$uotClass=new UotClass();
$regioneClass=new RegioneClass();
$ispettoreClass= new IspettoreClass();

$ispezioni=-1;

//$stabilimenti=$stabilimentoClass->getListaStabilimenti($db);
$uotelenco=$uotClass->getUot($db);
$regioneelenco=$regioneClass->getRegioni($db);

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
                <li class="tre <?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
                <li class="quattro active<?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
            </ul> 
        </nav>
 	</div>
    </header>
    <input type="hidden" name="ignora" id="propostaispezione" value="<?= $propostaispezione ?>">
    <section id="page" >
        <div class="container addnew">
            <div class="header">
                <h1>Pianificazione di Ispezioni</h1>
                <form method="POST" action="ispezioni.php">
                    <aside>
                        <a href="vedi_incarichiispettori.php?an=0" class="button">Incarichi Ispettori</a> 
                        <a href="home.php" class="back">Indietro</a>
                    </aside>
                </form>
            </div>
            <?php if (isset($_GET["msg"])) { ?>
            <div class="row">
                <div class="span12"><div class="alert alert-error">Scegliere la UOT o la Regione!</div></div>
            </div>
            <?php } ?>
            <?php if (isset($_GET["succes"])) { ?>
                    <div class="row">
                            <div class="span12"><div class="alert alert-success">Salvataggio avvenuto!</div></div>
                    </div>
            <?php } ?>
            <div class="row">
                    <div class="span12"><div class="title">Pianificazione delle ispezioni per UOT</div></div> 
            </div>
            <input type="hidden" name="indice" value="0" id="indice">
            <div class="container scheda">
                <form method="POST" action="aggiungi_pianificazioneUOT.php" id="form_ispez">
                    <input type="hidden" name="propispezioneId" value="<?= $ispezioneClass->getIspezioneId() ?>">
                    <div class="row">
                        <div class="span12">
                            <table>
                                <thead>
                                        <tr>
                                                <th class="span4"> UOT</th>
                                                <th class="span4"> Anno</th>
                                                <th class="span4"> Da Pianificare</th>
                                                <th class="span4"> Ispettori</th>
                                                <th class="span4"> Uditori</th>
                                                <th width="100px"></th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($uotelenco)>0){
                                        while($row=$db->fetchassoc2($uotelenco)){ 
                                            //QUI SI DEVE AGGIUNGERE IL GET DEGLI ANNI, COME LA QUERY CONTA ISPEZIONI MA CON SELECT ANNO
                                            //POI SU OGNI ANNO SI  INNESTA IL LOOP E LA SCRITTURA DEL RECORD
                                            $an=$ispezioneClass->getAnniIspezioniByUot($db, $row["uotId"], 3);
                                            if(mysqli_num_rows($an)){
                                                while($r7=$db->fetchassoc2($an)){
                                                    $k3=0;
                                                    $n=$ispezioneClass->contaIspezioniByUot($db, $row["uotId"],$r7["Anno"] , 3); 
                                                    $row3= $db->fetchassoc2($n);
                                                    if($db->mysqli_real_escape($row3["cont"])>0){
                                                        $k3=$db->mysqli_real_escape($row3["cont"]);
                                                    }
                                                    if ($k3>0){?>
                                                        <tr>    
                                                        <td valign="top"><?=$row["uotDenominazione"]?></td>
                                                        <td valign="top"><?=$r7["Anno"]?></td>                                                    
                                                        <td valign="top"><?=$k3?></td>
                                                    <?php 
                                                        $ispettoriexp=$ispettoreClass->getIspettoriByUot($db, $db->mysqli_real_escape($row["uotId"]), 1);
                                                        if(mysqli_num_rows($ispettoriexp)>0){ ?>
                                                            <td valign="top"><?=mysqli_num_rows($ispettoriexp)?></td>
                                                        <?php }else {?> <td valign="top"><?=0?><?php } ?></td>
                                                        <?php
                                                        $ispettoriud=$ispettoreClass->getIspettoriByUot($db, $db->mysqli_real_escape($row["uotId"]), 2);
                                                        if(mysqli_num_rows($ispettoriud)>0){ ?>
                                                            <td valign="top"><?=mysqli_num_rows($ispettoriud)?></td>
                                                        <?php }else {?> <td valign="top"><?=0?><?php } ?></td>
                                                        <!--           MODIFICA 02-02-2017                               -->
                                                        <td><a href="aggiungi_pianificazioneUot.php?id=<?=$row["uotId"]?>&rg=0&an=<?=$r7["Anno"]?>"><img src="img/icon-mod.png" alt="icon"></a></td>

                                                        </tr> 
                                                    <?php }
                                                }
                                            }
                                        }
                                    }else{ ?>
                                    <tr>
                                        <td colspan="12">Nessuna ispezione definita</td>
                                    </tr><?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>