<?php

ob_start();
$pageMenu = "INDICATORI";
require 'config.php';
require 'db/mysql.php';
require 'class/IspettoreClass.php';
require 'class/UotClass.php';
require 'class/IspezioneClass.php';
require 'class/StabilimentoClass.php';
require 'class/AttivitaIndustrialeClass.php';
require 'class/ComuneClass.php';
require 'class/RegioneClass.php';
require 'class/RuoloClass.php';
require 'class/UotRegioneClass.php';

include("include/check_user.php");

include 'include/header.php';

$db= new DbConnect();
$db->open() or die($db->error());

$ispettoreClass= new IspettoreClass();
$uotClass = new UotClass();
$ispezioneClass=new IspezioneClass();
$stabilimentoClass= new StabilimentoClass();
$attivitaIndustrialeClass= new AttivitaIndustrialeClass();
$comuneClass = new ComuneClass();
$regioneClass=new RegioneClass();
$ruoloClass = new RuoloClass();
$uotRegione = new UotRegioneClass();

//Tutte le ispezioni
$anni=$ispezioneClass->getAnniTutteIspezioni($db);

$annispez = array();
$i=0;
while ($row = $db->fetchassoc2($anni)) { 
    $annispez[$i] = $row["anno"];
    $i++;
}

$an = $_REQUEST['an'];
//if($an==0){
    $j=count($annispez);
    if($j>0){
        $annocurr=  $annispez[$j-1];
    }
//}


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
            <a href="indicatori.php?an=0"><img src="img/kpi2.png" alt="icon"></a>
            <a href="dati.php">Dati</a>
            <a href="criteri.php">Criteri</a>
            <a href="logout.php">Logout</a>
        </div>
        </div>
        </div>
        <a href="javascript:;" class="mobilemenu">MENU</a>
        <nav>
            <ul>
                <li class="uno<?php echo $class_0;?>"><a href="ispettori.php"><img src="img/icon_1.png" alt="icon"><span class="none">Ispettori</span></a></li>
                <li class="due<?php echo $class_1;?>"><a href="stabilimenti.php"><img src="img/icon_2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
                <li class="tre<?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
                <li class="quattro<?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
            </ul> 
        </nav>
        </div>
    </header>
    <input type="hidden" name="ignora" id="uot" value="<?= $stabilimento ?>"></input>
    <section id="page" >
        <div class="container addnew">
            <div class="header">
                <h1>Indicatori</h1>     
            <form method="POST" action="indicatori.php?an=1">
                <aside>
<!--                    <div class="span4">
                        <select style="width: 100px;" name="annoispez" >
                            <option value="<?= $annocurr ?>"></option>
                                <?php for ($i=0;$i<count($annispez);$i++) { ?>
                                        <option value="<?= $annispez[$i] ?>" 
                                        <?php 
                                        if ($annocurr == $annispez[$i]) {
                                                echo 'selected'; 
                                        } ?> ><?= $annispez[$i] ?></option>
                                <?php } ?>
                        </select>
                        <button type="submit" class="mt0">Aggiorna</button>
                    </div>-->
                    <div class="row">
                        <a href="csv_indicatori.php" class="button">Esporta Excel</a>
                        <a href="csv_indicatoridettagli.php" class="button">Esporta Dettagli</a>
                    </div>
                </aside>
            </form>
            </div>   
            <input type="hidden" name="indice" value="0" id="indice"></input>
            <div class="container scheda">
                <div class="row">
                    <div class="span8">
                        <table>
                            <thead>
                                <tr>
                                    <th class="span4"> Argomento</th>
                                    <th class="span6"> </th>
                                    <th class="span2"> Anno</th>
                                    <th class="span2"> Valore</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td valign="top">Ispettori Esperti</td>
                                    <td></td>
                                    <td valign="top"><?=$annocurr?></td>
                                    <?php
                                    $nIspett=$ispettoreClass->contaIspettori($db,1); 
                                    $k1=0;
                                    while($row1= $db->fetchassoc2($nIspett)){ 
                                        if($db->mysqli_real_escape($row1["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row1["cont"]);
                                        }?><td valign="top"><?=$k1 ?></td><?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <td valign="top">Ispettori Uditori</td>
                                    <td></td>
                                    <td valign="top"><?=$annocurr?></td>
                                    <?php
                                    $nIspett=$ispettoreClass->contaIspettori($db,2); 
                                    $k1=0;
                                    while($row1= $db->fetchassoc2($nIspett)){ 
                                        if($db->mysqli_real_escape($row1["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row1["cont"]);
                                        }?><td valign="top"><?=$k1 ?></td><?php
                                    }
                                    ?>
                                </tr>
                                <?php   //fai il loop di tutti gli anni sulle ispezioni
                                    for($i=0; $i<count($annispez);$i++){ ?>
                                        <tr>
                                            <td></td>
                                            <td valign="top">Ispezioni archiviate</td>
                                            
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <?php
                                            $nIspez=$ispezioneClass->contaIspezioniAnno($db,1, $annispez[$i]); 
                                            $k1=0;
                                            while($row1= $db->fetchassoc2($nIspez)){ 
                                                if($db->mysqli_real_escape($row1["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row1["cont"]);
                                                }?><td valign="top"><?=$k1 ?></td><?php
                                            }
                                            ?>
                                        </tr>
                                        <tr>
<!--                                            modifica del 21/04/2017-->
                                            <td></td>
                                            <td valign="top">Ispezioni concluse</td>
                                            
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <?php
                                            $nIspez=$ispezioneClass->contaIspezioniAnno($db,5, $annispez[$i]); 
                                            $k1=0;
                                            while($row1= $db->fetchassoc2($nIspez)){ 
                                                if($db->mysqli_real_escape($row1["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row1["cont"]);
                                                }?><td valign="top"><?=$k1 ?></td><?php
                                            }
                                            ?>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td valign="top">Ispezioni in corso</td>
                                            
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <?php
                                            $nIspez=$ispezioneClass->contaIspezioniAnno($db,2, $annispez[$i]); 
                                            $k1=0;
                                            while($row1= $db->fetchassoc2($nIspez)){ 
                                                if($db->mysqli_real_escape($row1["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row1["cont"]);
                                                }?><td valign="top"><?=$k1 ?></td><?php
                                            }
                                            ?>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td valign="top">Ispezioni da pianificare</td>
                                            
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <?php
                                            $nIspez=$ispezioneClass->contaIspezioniAnno($db,3, $annispez[$i]); 
                                            $k1=0;
                                            while($row1= $db->fetchassoc2($nIspez)){ 
                                                if($db->mysqli_real_escape($row1["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row1["cont"]);
                                                }?><td valign="top"><?=$k1 ?></td><?php
                                            }
                                            ?>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td valign="top">Ispezioni sospese</td>
                                            
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <?php
                                            $nIspez=$ispezioneClass->contaIspezioniAnno($db,4, $annispez[$i]); 
                                            $k1=0;
                                            while($row1= $db->fetchassoc2($nIspez)){ 
                                                if($db->mysqli_real_escape($row1["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row1["cont"]);
                                                }?><td valign="top"><?=$k1 ?></td><?php
                                            }
                                            ?>
                                        </tr>
                                        <tr>
                                            <td valign="top">Totale Ispezioni</td>
                                            <td></td>
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <?php
                                            $nIspez=$ispezioneClass->contaIspezioniAnno($db,0, $annispez[$i]); 
                                            $k1=0;
                                            while($row1= $db->fetchassoc2($nIspez)){ 
                                                if($db->mysqli_real_escape($row1["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row1["cont"]);
                                                }?><td valign="top"><?=$k1 ?></td><?php
                                            }
                                            ?>
                                        </tr>
                                    <?php } ?>
                                        
                               <?php   //fai il loop di tutti gli anni sulle ispezioni
                                    for($i=0; $i<count($annispez);$i++){ ?>
                                        <tr>
                                            <td valign="top">Ispezioni concluse </td>
                                            <td valign="top">Ispettori stessa UOT</td>
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <?php
                                            $nIspez=$ispezioneClass->contaIspezioniStessaUOTAnno($db, $annispez[$i],1); 
                                            $k1=0;
                                            while($row1= $db->fetchassoc2($nIspez)){ 
                                                if($db->mysqli_real_escape($row1["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row1["cont"]);
                                                }?><td valign="top"><?=$k1 ?></td><?php
                                            }
                                            ?>
                                        </tr>
                                            <?php      //get elenco ispezioni con ispettori diversa uot                                     
                                            $isp=$ispezioneClass->getIspezioniDiversaUOTAnno($db, $annispez[$i],1);
                                            $kXreg=0;
                                            $kXter=0;
                                            while($row10= $db->fetchassoc2($isp)){
                                                //verifica se l'uot dell'ispettore e dello stab hanno la stessa regione
                                                //get id regione della uot stabilimento
                                                $stabilimentoClass->setStabilimentoId($row10["stabIdFk"]);
                                                $stabTmp=$stabilimentoClass->getDettaglioStabilimento($db);
                                                $row11=$db->fetchassoc2($stabTmp);
                                                $regstabtmp=$uotRegione->getRegioneIdByUot($db, $row11["uotAffIdFk"]);
                                                $row14=$db->fetchassoc2($regstabtmp);
                                                $idRegStab=$row14["regioneIdFk"];
                                                //get uot dell'ispettore
                                                $ispettoreClass->setIspettoreId($row10["ispettIdFk"]);
                                                $ispetTmp=$ispettoreClass->getDettaglioIspettore($db);
                                                $row12=$db->fetchassoc2($ispetTmp);
                                                $regtmp=$uotRegione->getRegioneIdByUot($db, $row12["uotIspIdFk"]);
                                                $row13=$db->fetchassoc2($regtmp);
                                                $idRegIspet=$row13["regioneIdFk"];
                                                if($idRegStab!=$idRegIspet){
                                                    //extraregionale
                                                    $kXreg++;
                                                }else{
                                                    //regionale
                                                    $kXter++;
                                                }
                                            }
                                            ?>
                                        <tr>
                                            <td valign="top"></td>
                                            <td valign="top">Ispettori regionali </td>
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <td valign="top"><?=$kXter?></td>
                                        </tr>
                                        <tr>
                                            <td valign="top"></td>
                                            <td valign="top">Ispettori extraregionali </td>
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <td valign="top"><?=$kXreg?></td>
                                        </tr>
                                        <tr>
                                            <td valign="top">Ispezioni in corso </td>
                                            <td valign="top">Ispettori stessa UOT</td>
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <?php
                                            $nIspez=$ispezioneClass->contaIspezioniStessaUOTAnno($db, $annispez[$i],2); 
                                            $k1=0;
                                            while($row1= $db->fetchassoc2($nIspez)){ 
                                                if($db->mysqli_real_escape($row1["cont"])>0){
                                                    $k1=$db->mysqli_real_escape($row1["cont"]);
                                                }?><td valign="top"><?=$k1 ?></td><?php
                                            }
                                            ?>
                                        </tr>
                                            <?php      //get elenco ispezioni con ispettori diversa uot                                     
                                            $isp=$ispezioneClass->getIspezioniDiversaUOTAnno($db, $annispez[$i],2);
                                            $kXreg=0;
                                            $kXter=0;
                                            while($row10= $db->fetchassoc2($isp)){
                                                //verifica se l'uot dell'ispettore e dello stab hanno la stessa regione
                                                //get id regione della uot stabilimento
                                                $stabilimentoClass->setStabilimentoId($row10["stabIdFk"]);
                                                $stabTmp=$stabilimentoClass->getDettaglioStabilimento($db);
                                                $row11=$db->fetchassoc2($stabTmp);
                                                $regstabtmp=$uotRegione->getRegioneIdByUot($db, $row11["uotAffIdFk"]);
                                                $row14=$db->fetchassoc2($regstabtmp);
                                                $idRegStab=$row14["regioneIdFk"];
                                                //get uot dell'ispettore
                                                $ispettoreClass->setIspettoreId($row10["ispettIdFk"]);
                                                $ispetTmp=$ispettoreClass->getDettaglioIspettore($db);
                                                $row12=$db->fetchassoc2($ispetTmp);
                                                $regtmp=$uotRegione->getRegioneIdByUot($db, $row12["uotIspIdFk"]);
                                                $row13=$db->fetchassoc2($regtmp);
                                                $idRegIspet=$row13["regioneIdFk"];
                                                if($idRegStab!=$idRegIspet){
                                                    //extraregionale
                                                    $kXreg++;
                                                }else{
                                                    //regionale
                                                    $kXter++;
                                                }
                                            }
                                            ?>
                                        <tr>
                                            <td valign="top"></td>
                                            <td valign="top">Ispettori regionali </td>
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <td valign="top"><?=$kXter?></td>
                                        </tr>
                                        <tr>
                                            <td valign="top"></td>
                                            <td valign="top">Ispettori extraregionali </td>
                                            <td valign="top"><?=$annispez[$i]?></td>
                                            <td valign="top"><?=$kXreg?></td>
                                        </tr>
                                    <?php } ?> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>