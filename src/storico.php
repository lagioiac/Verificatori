<?php

ob_start();
$pageMenu = "incarichi ispettori";
require 'config.php';
require 'db/mysql.php';
require 'class/IspettoreClass.php';
require 'class/RuoloClass.php';
//MODIFICATO 03/02/2017
require 'class/IspezioneClass.php';

require 'class/StabilimentoClass.php';
require 'class/AttivitaIndustrialeClass.php';
require 'class/ComuneClass.php';
require 'class/UotClass.php';

include("include/check_user.php");

$pageName="STORICO";
include 'include/header.php';

$db= new DbConnect();
$db->open() or die($db->error());

$stabilimentoClass= new StabilimentoClass();
$attivitaIndustrialeClass= new AttivitaIndustrialeClass();
$comuneClass = new ComuneClass();
$uotClass = new UotClass();

$ispettoreClass= new IspettoreClass();
$ruoloClass = new RuoloClass();

$ispezioneClass=new IspezioneClass();
$anni=$ispezioneClass->getAnnoIspezioniConcluse($db);

$annispez = array();
$i=0;
while ($row = $db->fetchassoc2($anni)) { 
    $annispez[$i] = $row["anno"];
    $i++;
}
$an = $_REQUEST['an'];
if($an==0){
    //19-mar-2018
    $lung=count($annispez)-1;
    $annocurr=  $annispez[$lung];
//    $annocurr=  $annispez[0];
}else{
    $annocurr= $_POST["annoispez"];
}
$ispezioni=$ispezioneClass->getIspezioniConcluseAnno($db, $annocurr);
if(mysqli_num_rows($ispezioni)){$nIspez=mysqli_num_rows($ispezioni);}else{$nIspez=0;}

$statoClass = new RuoloClass();
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
//aggiunto il 28/04/2017
$ruoloClass = new RuoloClass();
$tiporuolo = array();
$idruolo=array();
$coloreruolo = array();
$elencoruoli = $ruoloClass->getRuoli($db);
$i=1;
while ($r = $db->fetchassoc2($elencoruoli)){
    $idruolo[$i] = $r["ruoloId"];
    $tiporuolo[$i] = $r["ruolo"];
    $coloreruolo[$i] = $r["iconaruolo"];    
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
                <li class="uno<?php echo $class_0;?>"><a href="ispettori.php"><img src="img/icon_1.png" alt="icon"><span class="none">Ispettori</span></a></li>
                <li class="due<?php echo $class_1;?>"><a href="stabilimenti.php"><img src="img/icon_2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
                <li class="tre active<?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
                <li class="quattro<?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
            </ul> 
        </nav>
        </div>
    </header>
    <input type="hidden" name="ignora" id="uot" value="<?= $stabilimento ?>"></input>
    <section id="page" >
        <div class="container addnew">
            <div class="header">
                <h1>Elenco ispezioni concluse: <?=$nIspez ?> - anno:</h1>     
            <form method="POST" action="storico.php?an=1">
                <aside>
                    <div class="span4">
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
                    </div>
                    <a href="csv_ispezioni_chiuse.php?anno=<?= $annocurr ?>" class="button">Esporta Excel</a>
                </aside>
            </form>
            </div>   
            <input type="hidden" name="indice" value="0" id="indice"></input>
            <div class="container scheda">
                <div class="row">
                    <div class="span12">
                        <table>
                            <thead>
                                <tr>
                                    <th class="span6"> Stabilimento</th>
                                    <th class="span6"> Attività Industriale</th>
                                    <th class="span4"> Provincia</th>
                                    <th class="span4"> Regione</th>
                                    <th class="span6"> UOT-Stab.</th>
                                    <th class="span6"> Ispettori-UOT</th>
                                    <th class="span5"> Uditori-UOT</th>
                                    <th class="span2"> Doc.Inviati</th>
                                    <th width="500px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysqli_num_rows($ispezioni)){
                                    while($row=$db->fetchassoc2($ispezioni)){ ?>
                                <tr>
                                    <td valign="top">
                                        <a href="aggiungi_stabilimento.php?id=<?=$row["stabilimentoId"]?>" target=”_blank”><?=$row["Azienda"]."<br>"?></a>                                    
                                    </td>
                                    <td valign="top">
                                        <?=$row["Attivita"]?>
                                    </td>
                                    <td valign="top">
                                        <?=$row["Provincia"]?>
                                    </td>
                                    <td valign="top">
                                        <?=$row["Regione"]?>
                                    </td>  
                                    <td valign="top">
                                        <?php //uot stabilimento 
                                        $stabilimentoClass->setStabilimentoId($row["stabilimentoId"]);
                                        $sttmp=$stabilimentoClass->getDettaglioStabilimento($db);
                                        $row5=$db->fetchassoc2($sttmp);
                                        $uotClass->setUotId($row5["uotAffIdFk"]);
                                        $uottmp=$uotClass->getDettaglioUot($db);
                                        $row6=$db->fetchassoc2($uottmp);
                                        ?><a href="aggiungi_uot.php?id=<?=$row5["uotAffIdFk"]?>" target=”_blank”><?=$row6["uotDenominazione"]."<br>"?></a>
                                    </td>
                                    <td valign="top">
                                        <?php //ispettore 
                                        $isptmp=$ispettoreClass->getIspettoreById($db, $db->mysqli_real_escape($row["Ispettore"]));
                                        $row7=$db->fetchassoc2($isptmp);
                                        $k1=$colore[$db->mysqli_real_escape($row7["ruoloIdFk"])];
                                        $strtmp1=$db->mysqli_real_escape($row7["ispettoreCognome"]);
                                        $strtmp1.=" ";
                                        $strtmp1.=$db->mysqli_real_escape($row7["ispettoreNome"]);
                                        if($strtmp1!=""){
                                            ?><a href="aggiungi_ispettore.php?id=<?=$row["Ispettore"]?>" target=”_blank”><img src="<?=$k1?>" alt="icon"><?=$strtmp1."<br>"?> </a>
                                        <?php } //get uot ispettore
                                        $uottmp=$uotClass->getUotById($db, $db->mysqli_real_escape($row7["uotIspIdFk"]));
                                        $row6=$db->fetchassoc2($uottmp);
                                        ?>
                                        <a href="aggiungi_uot.php?id=<?=$row7["uotIspIdFk"]?>" target=”_blank”><?=$row6["uotDenominazione"]."<br>"?></a>
                                    </td>
                                    <td valign="top">
                                        <?php //controlla se c'è uditore
                                        if($row["Uditore"]>0){
                                            $udtmp=$ispettoreClass->getIspettoreById($db, $db->mysqli_real_escape($row["Uditore"]));
                                            $row8=$db->fetchassoc2($udtmp);
                                            $k1=$coloreruolo[2];    //$colore[$db->mysqli_real_escape($row8["ruoloIdFk"])];
                                            $strtmp1=$db->mysqli_real_escape($row8["ispettoreCognome"]);
                                            $strtmp1.=" ";
                                            $strtmp1.=$db->mysqli_real_escape($row8["ispettoreNome"]);
                                            if($strtmp1!=""){
                                                ?><a href="aggiungi_ispettore.php?id=<?=$row["Uditore"]?>" target=”_blank”><img src="<?=$k1?>" alt="icon"><?=$strtmp1?> </a>
                                            <?php }
                                            $uottmp=$uotClass->getUotById($db, $db->mysqli_real_escape($row8["uotIspIdFk"]));
                                            $row9=$db->fetchassoc2($uottmp);
                                            ?>
                                            <a href="aggiungi_uot.php?id=<?=$row8["uotIspIdFk"]?>" target=”_blank”><?=$row9["uotDenominazione"]?></a>
                                            <?php
                                        }
                                       ?>
                                    </td>
                                    <td valign="top"><?php
                                        $strtmp="";
                                        if ($row["rcdoc"]) {
                                            $strtmp="Rapp.Concl."."<br>";
                                        }
                                        if ($row["eodoc"]) {
                                            $strtmp.="Esp.Op."."<br>";
                                        }
                                        if ($row["stdoc"]) {
                                            $strtmp.="Sist.Tec."."<br>";
                                        }
                                        if ($row["aldoc"]) {
                                            $strtmp.="Altro"."<br>";
                                        }
                                        if ($row["midoc"]) {    //22-01-2019 - aggiunta
                                            $strtmp.="Met.Invecc."."<br>";
                                        }
                                        if($strtmp==""){
                                            $strtmp.="";    //Scrivere qualcosa di particolare?
                                        }
                                    ?><?=$strtmp?>
                                    <td><a href="aggiungi_ispezione.php?id=<?=$row["ispezioneId"]?>" target=”_blank”><img src="img/icon-mod.png" alt="icon"></a></td>
                                </tr>
                                <?php } 
                                  }else{ ?>
                                <tr>
                                    <td colspan="12">Nessuna ispezione conclusa</td>
                                </tr><?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</body>