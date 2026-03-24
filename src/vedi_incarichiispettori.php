<?php

ob_start();
$pageMenu = "incarichi ispettori";
require 'config.php';
require 'db/mysql.php';
require 'class/IspettoreClass.php';
require 'class/RuoloClass.php';
//MODIFICATO 03/02/2017
require 'class/IspezioneClass.php';

include("include/check_user.php");

$pageName="UOT";
include 'include/header.php';

$db= new DbConnect();
$db->open() or die($db->error());

$ispettoreClass= new IspettoreClass();
$elencoispettori=$ispettoreClass->getElencoTotaleIspettori($db);
$ruoloClass = new RuoloClass();

$ispezioneClass=new IspezioneClass();
$anni=$ispezioneClass->getAnnoIspezioniAssegnate($db);

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

$colore = array();
$tiporuolo = array();
$idruolo=array();
$elencoruoli = $ruoloClass->getRuoli($db);
$i=0;
while ($r = $db->fetchassoc2($elencoruoli)){
    $idruolo[$i] = $r["ruoloId"];
    $colore[$i] = $r["iconaruolo"];
    $tiporuolo[$i] = $r["ruolo"];
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
                <h1>Panoramica incarichi a Ispettori/Uditori - anno:</h1>
                
            <form method="POST" action="vedi_incarichiispettori.php?an=1">
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
                    <a href="csv_incarichi_ispettori.php?anno=<?= $annocurr ?>" class="button">Esporta Excel</a>
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
                                    <th class="span2"> Regione</th>
                                    <th class="span1"> UOT</th>
                                    <th class="span2"> Ispettore</th>
                                    <th class="span2"> Competenza</th>                                                                       
                                    <th class="span6"> n° Ispez.Assegnate</th>
                                    <th class="span6"> n° Ispez.Concluse</th>
                                    <th class="span1"> n° Ispez.Sospese</th>
                                    <th class="span6"> Esperienza Ispez.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $regionecurr="";
                                    $uotcurr="";
                                    if(mysqli_num_rows($elencoispettori)>0){
                                        while($row=$db->fetchassoc2($elencoispettori)){
                                    ?>
                                <tr>
                                    <?php   //Regione
                                    if($row["Regione"]==$regionecurr){
                                        ?><td></td><?php
                                    }else{
                                        $regionecurr=$row["Regione"];
                                        ?><td valign="top"><?=$row["Regione"]?></td><?php
                                    }
                                    //UOT
                                    if($row["UOT"]==$uotcurr){
                                        ?><td></td><?php
                                    }else{
                                        $uotcurr=$row["UOT"];
                                        ?><td valign="top"><?=$row["UOT"]?></td><?php
                                    }
                                    //Ispettore
                                    ?><td valign="top">
                                    <a href="aggiungi_ispettore.php?id=<?=$row["ispett"]?>" target=”_blank”><?=$row["Cognome"]." ".$row["Nome"]?> </a>
                                    </td>
                                    <?php
                                    //Ruolo
                                    $r=$ruoloClass->getIconaByRuolo($db, $row["Ruolo"]);
                                    $r3=$db->fetchassoc2($r);
                                    
                                    //Competenza
                                    ?><td valign="top"><?=$row["Competenza"]?></td>
                                    <?php
                                    //n° ispezioni  ASSEGNATE
                                    //come ispettore
                                    $nIspezAnno=$ispettoreClass->contaIspezioniByIspettoreAnno($db, $row["ispett"], 2,$annocurr); 
                                    $k1=0;
                                    while($row31= $db->fetchassoc2($nIspezAnno)){ 
                                        if($db->mysqli_real_escape($row31["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row31["cont"]);
                                        }
                                    }
                                    //conta se ha fatto ispezioni come uditore
                                    $nIspezAnno=$ispettoreClass->contaIspezioniByUditoreAnno($db, $row["ispett"], 2,$annocurr); 
                                    $k2=0;
                                    while($row31= $db->fetchassoc2($nIspezAnno)){ 
                                        if($db->mysqli_real_escape($row31["cont"])>0){
                                            $k2=$db->mysqli_real_escape($row31["cont"]);
                                        }
                                    }
                                    if(($k1>0) && ($k2>0)){
                                        //ispettore e uditore
                                        ?>
                                        <td valign="top">
                                            <img src="<?=$colore[0]?>" alt="icon"><?=$tiporuolo[0].": ".$k1."<br>"?>
                                            <img src="<?=$colore[1]?>" alt="icon"><?=$tiporuolo[1].": ".$k2?>
                                        </td><?php
                                    }elseif(($k1>0) && ($k2==0)){
                                        ?>
                                        <td valign="top">
                                            <img src="<?=$colore[0]?>" alt="icon"><?=$tiporuolo[0].": ".$k1?>
                                        </td><?php
                                    }elseif(($k2>0) && ($k1==0)){
                                        ?>
                                        <td valign="top">
                                            <img src="<?=$colore[1]?>" alt="icon"><?=$tiporuolo[1].": ".$k2?>
                                        </td><?php
                                    }else{
                                        ?>
                                        <td></td>
                                        <?php
                                    }

                                    //ISPEZIONI CONCLUSE
                                    $nIspezAnno=$ispettoreClass->contaIspezioniByIspettoreAnno($db, $row["ispett"], 1,$annocurr); 
                                    $k1=0;
                                    while($row31= $db->fetchassoc2($nIspezAnno)){ 
                                        if($db->mysqli_real_escape($row31["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row31["cont"]);
                                        }
                                    }
                                    //conta se ha fatto ispezioni come uditore
                                    $nIspezAnno=$ispettoreClass->contaIspezioniByUditoreAnno($db, $row["ispett"], 1,$annocurr); 
                                    $k2=0;
                                    while($row31= $db->fetchassoc2($nIspezAnno)){ 
                                        if($db->mysqli_real_escape($row31["cont"])>0){
                                            $k2=$db->mysqli_real_escape($row31["cont"]);
                                        }
                                    }
                                    if(($k1>0) && ($k2>0)){
                                        //ispettore e uditore
                                        ?>
                                        <td valign="top">
                                            <img src="<?=$colore[0]?>" alt="icon"><?=$tiporuolo[0].": ".$k1."<br>"?>
                                            <img src="<?=$colore[1]?>" alt="icon"><?=$tiporuolo[1].": ".$k2?>
                                        </td><?php
                                    }elseif(($k1>0) && ($k2==0)){
                                        ?>
                                        <td valign="top">
                                            <img src="<?=$colore[0]?>" alt="icon"><?=$tiporuolo[0].": ".$k1?>
                                        </td><?php
                                    }elseif(($k2>0) && ($k1==0)){
                                        ?>
                                        <td valign="top">
                                            <img src="<?=$colore[1]?>" alt="icon"><?=$tiporuolo[1].": ".$k2?>
                                        </td><?php
                                    }else{
                                        ?>
                                        <td></td>
                                        <?php
                                    }
                                        
                                    //ISPEZIONI SOSPESE 
                                    $nIspezAnno=$ispettoreClass->contaIspezioniByIspettoreAnno($db, $row["ispett"], 4,$annocurr); 
                                    $k1=0;
                                    while($row31= $db->fetchassoc2($nIspezAnno)){ 
                                        if($db->mysqli_real_escape($row31["cont"])>0){
                                            $k1=$db->mysqli_real_escape($row31["cont"]);
                                        }
                                    }
                                    $nIspezAnno=$ispettoreClass->contaIspezioniByUditoreAnno($db, $row["ispett"], 4,$annocurr); 
                                    $k2=0;
                                    while($row32= $db->fetchassoc2($nIspezAnno)){ 
                                        if($db->mysqli_real_escape($row32["cont"])>0){
                                            $k2=$db->mysqli_real_escape($row32["cont"]);
                                        }
                                    }
                                    if(($k1>0) && ($k2>0)){
                                        //ispettore e uditore
                                        ?>
                                        <td valign="top">
                                            <img src="<?=$colore[0]?>" alt="icon"><?=$tiporuolo[0].": ".$k1."<br>"?>
                                            <img src="<?=$colore[1]?>" alt="icon"><?=$tiporuolo[1].": ".$k2?>
                                        </td><?php
                                    }elseif(($k1>0) && ($k2==0)){
                                        ?>
                                        <td valign="top">
                                            <img src="<?=$colore[0]?>" alt="icon"><?=$tiporuolo[0].": ".$k1?>
                                        </td><?php
                                    }elseif(($k2>0) && ($k1==0)){
                                        ?>
                                        <td valign="top">
                                            <img src="<?=$colore[1]?>" alt="icon"><?=$tiporuolo[1].": ".$k2?>
                                        </td><?php
                                    }else{
                                        ?>
                                        <td></td>
                                        <?php
                                    }
                                        
                                        //31-gen-2017
                                        $esp=$ispettoreClass->getElencoEsperienzeIspettore($db, $row["ispett"]);
                                        $listesp="";
                                        while($r4=$db->fetchassoc2($esp)){
                                            $listesp.=$db->mysqli_real_escape($r4["Esperienza"])."<br>";
                                        }?><td valign="top"><?=$listesp ?></td><?php
                                    ?>
                                </tr>
                                <?php } 
                                  }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</body>