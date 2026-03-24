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

//Tutte le ispezioni
$anni=$ispezioneClass->getAnniTutteIspezioni($db);

$annispez = array();
$i=0;
while ($row = $db->fetchassoc2($anni)) { 
    $annispez[$i] = $row["anno"];
    $i++;
}
$an = $_REQUEST['an'];
if($an==0){
    $annocurr=  $annispez[0];
}else{
    $annocurr= $_POST["annoispez"];
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
                                    <th class="span6"> Argomento</th>
                                    <th class="span2"> Anno</th>
                                    <th class="span2"> Valore</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td valign="top">Ispettori Esperti</td>
                                    <td></td>
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
                                <tr>
                                    <td valign="top">Totale Ispezioni designate nel </td>
                                    <td valign="top"><?=$annocurr?></td>
                                    <?php
                                    $nIspez=$ispezioneClass->contaIspezioniAnno($db,0, $annocurr); 
                                    $k1=0;
                                    while($row1= $db->fetchassoc2($nIspez)){ 
                                        if($db->mysqli_real_escape($row1["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row1["cont"]);
                                        }?><td valign="top"><?=$k1 ?></td><?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <?php 
                                        $strtmp1="\t"."Ispezioni in corso nel"?>
                                    <td valign="top"><?=$strtmp1 ?></td>
                                    <td valign="top"><?=$annocurr?></td>
                                    <?php
                                    $nIspez=$ispezioneClass->contaIspezioniAnno($db,2, $annocurr); 
                                    $k1=0;
                                    while($row1= $db->fetchassoc2($nIspez)){ 
                                        if($db->mysqli_real_escape($row1["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row1["cont"]);
                                        }?><td valign="top"><?=$k1 ?></td><?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <td valign="top">Ispezioni concluse nel </td>
                                    <td valign="top"><?=$annocurr?></td>
                                    <?php
                                    $nIspez=$ispezioneClass->contaIspezioniAnno($db,1, $annocurr); 
                                    $k1=0;
                                    while($row1= $db->fetchassoc2($nIspez)){ 
                                        if($db->mysqli_real_escape($row1["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row1["cont"]);
                                        }?><td valign="top"><?=$k1 ?></td><?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <td valign="top">Ispezioni sospese nel </td>
                                    <td valign="top"><?=$annocurr?></td>
                                    <?php
                                    $nIspez=$ispezioneClass->contaIspezioniAnno($db,4, $annocurr); 
                                    $k1=0;
                                    while($row1= $db->fetchassoc2($nIspez)){ 
                                        if($db->mysqli_real_escape($row1["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row1["cont"]);
                                        }?><td valign="top"><?=$k1 ?></td><?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <td valign="top">Ispezioni con ispettori della stessa UOT </td>
                                    <td valign="top"><?=$annocurr?></td>
                                    <?php
                                    $nIspez=$ispezioneClass->contaIspezioniStessaUOTAnno($db, $annocurr); 
                                    $k1=0;
                                    while($row1= $db->fetchassoc2($nIspez)){ 
                                        if($db->mysqli_real_escape($row1["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row1["cont"]);
                                        }?><td valign="top"><?=$k1 ?></td><?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <td valign="top">Ispezioni con ispettori di altre UOT </td>
                                    <td valign="top"><?=$annocurr?></td>
                                    <?php
                                    $nIspez=$ispezioneClass->contaIspezioniDiversaUOTAnno($db, $annocurr); 
                                    $k1=0;
                                    while($row1= $db->fetchassoc2($nIspez)){ 
                                        if($db->mysqli_real_escape($row1["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row1["cont"]);
                                        }?><td valign="top"><?=$k1 ?></td><?php
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>