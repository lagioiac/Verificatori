<?php

ob_start();
$pageMenu="ispettori";
require 'config.php';

require 'db/mysql.php';
require 'class/StabilimentoClass.php';
require 'class/AttivitaIndustrialeClass.php';
require 'class/ComuneClass.php';
include("include/check_user.php");

$pageName="STABILIMENTI";
include 'include/header.php'; 

$db= new DbConnect();
$stabilimentoClass= new StabilimentoClass();
$attivitaIndustrialeClass= new AttivitaIndustrialeClass();
$comuneClass = new ComuneClass();

$db->open() or die($db->error());

$searchprovincia = null;
$searchregione = null;
$searchattiv=null;

//24-01-2019 Aggiunot ordinaPer
if(!isset($_GET['ordinaPer'])) {
    $_GET['ordinaPer'] = 'regione.nomeregione';
}
$ordinaPer=$_GET['ordinaPer'];
// $stabcurr=new StabilimentoClass();    //QUESTA PARTE COMMENTATA SERVE PER AGGIORNARE IL DB DEFINITIVO
//22-01-2019 Modifica fatta per mantenere quanto cercato
if (isset($_POST["searchprovincia"]) || isset($_POST["searchregione"]) || isset($_POST["searchattiv"])){
    if(isset($_POST["searchprovincia"]) != ""){
        //31-01-2019    corretto malfunzionamento: se si fa una ricerca per provincia l'ordine deve essere per provincia e non per regione
        $searchprovincia=$_POST["searchprovincia"];
//        if( $_GET['ordinaPer'] == 'regione.nomeregione'){
//             $_GET['ordinaPer'] = 'provincia.prov';
//        }
    }else{
        $searchprovincia=null;
    }
    if((($_POST["searchregione"]) == "") && (($_POST["searchprovincia"]) == "") && (($_POST["searchattiv"]) != "")){
        $_GET['ordinaPer'] = 'attivitaindustriale.attivita';
    } else {
        $_GET['ordinaPer'] = 'provincia.prov';
    }
    
    $stabilimento=$stabilimentoClass->getSearchStabilimenti($db, $_POST,$_GET['ordinaPer']);
    
    
    if(isset($_POST["searchregione"]) != ""){
        $searchregione=$_POST["searchregione"];
    }else{
        $searchregione=null;
    }
    if(isset($_POST["searchattiv"]) != ""){
        $searchattiv=$_POST["searchattiv"];
    }else{
        $searchattiv=null;
    }
}else{
    $stabilimento=$stabilimentoClass->getListaStabilimenti($db,$_GET['ordinaPer']);
    
    // while($row=$db->fetchassoc2($stabilimento)){
        // $stabcurr->setStabilimentoId($row["stabilimentoId"]);
        // $ok=$stabcurr->updatePeriodoStabilimento($db);
    // }
}
if(mysqli_num_rows($stabilimento)){$nStab=mysqli_num_rows($stabilimento);}else{$nStab=0;}
$attivitaindustriale=$attivitaIndustrialeClass->getListaAttivitaindustriale($db);

?>
<script type="text/javascript">
    document.getElementById("myButton").onclick = function () {
        location.href = "index.php?clean";
    };
		function cancella(id){
			if(confirm("Sicuro di voler cancellare?")){	
				$(location).attr('href',"deleteFormazione.php?&id="+id+"");
			}
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
    <section id="page" class="stabilimento">
        <div class="container ">
            <div class="header">
                <h1>Elenco Stabilimenti: <?=$nStab?></h1>
                <form method="POST" action="stabilimenti.php">
                    <aside>
                        <input name="searchattiv" type="text" placeholder="Cerca attivita'" value="<?=$searchattiv?>" style="display: inline;">
                        <input name="searchprovincia" type="search" placeholder="Cerca provincia" value="<?=$searchprovincia?>" style="display: inline;">
                        <input name="searchregione" type="text" placeholder="Cerca regione" value="<?=$searchregione?>" style="display: inline;">
                        <input type="submit" style="position: absolute; left: -9999px"/>
                        <a href="aggiungi_stabilimento.php" class="button add">Aggiungi Stabilimento</a>
                        <a href="csv_stabilimenti.php" class="button">Esporta Excel</a>
 <!--                       <a href="ispettori/#" class="button">Importa Excel</a>   -->
                        <a href="home.php" class="back">Indietro</a>
                    </aside>
                </form>
            </div>
            <?php if(isset($_GET["succes"])){ ?>
            <div class="row addnew">
                <div class="span12"><div class="alert alert-success">Modifica avvenuta con successo!</div></div>
		<?php } ?>
                <div class="row">
                    <div class="span12">
                        <table>
                            <thead>
                                <tr>
                                    <th class="span6"><a href="stabilimenti.php?ordinaPer=stabilimento.stabilimentoDenominazione"><img src="img/icon_sort_asc.png" alt="icon"></a> Denominazione</th>
                                    <th class="span5"><a href="stabilimenti.php?ordinaPer=stabilimento.stabilimentoCodice"><img src="img/icon_sort_asc.png" alt="icon"></a> Codice</th>
                                    <th class="span5"><a href="stabilimenti.php?ordinaPer=provincia.prov"><img src="img/icon_sort_asc.png" alt="icon"></a> Provincia</th>
                                    <th class="span5"><a href="stabilimenti.php?ordinaPer=regione.nomeregione"><img src="img/icon_sort_asc.png" alt="icon"></a> Regione</th>
                                    <th class="span5"> Attiv.Industriale</th>
                                    <th class="span5"> Soglia</th>
                                    <th class="span5"> PianoIspez.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysqli_num_rows($stabilimento)){
                                    while($row=$db->fetchassoc2($stabilimento)){ ?>
                                <tr>
                                    <td valign="top">
                                        <a href="aggiungi_stabilimento.php?id=<?=$row["stabilimentoId"]?>">
                                        <?=$row["stabilimentoDenominazione"]?></a>
                                    </td>
                                    <td valign="top"><?=$row["stabilimentoCodice"]?></td>
                                    <td valign="top">
                                        <?php //get provincia 
                                        $provtmp=$comuneClass->getProvByComune($db, $row["comuneIdFk"]);
                                        $row3=$db->fetchassoc2($provtmp);
                                        ?><?=$row3["prov"]?>
                                    </td>
                                    <td valign="top">
                                        <?php //get regione 
                                        $regtmp=$comuneClass->getRegioneByComune($db, $row["comuneIdFk"]);
                                        $row4=$db->fetchassoc2($regtmp);
                                        ?><?=$row4["nomeregione"]?>
                                    </td>
                                    <td valign="top">
                                        <?php if($row["attivIndustrialeIdFk"]>0){
                                            $att=$attivitaIndustrialeClass->getAttivitaById($db, $row["attivIndustrialeIdFk"]);
                                            $row2=$db->fetchassoc2($att);?>
                                            <?=$row2["attivita"]?><?php
                                        }else{?>
                                            <?=""?>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td valign="top">
                                        <?php if($row["soglia105"]==0){?>
                                            <?="inferiore"?><?php
                                        }else{?>
                                            <?="superiore"?>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td valign="top"><?php
                                        switch ($row["periodo"]) {  //aggiunto il periodo allo stabilimento
                                            case 1: //annuale
                                                $strtmp="annuale";
                                                break;
                                            case 2: //biennale
                                                $strtmp="biennale";
                                                break;
                                            case 3: //triennale
                                                $strtmp="triennale";
                                                break;
                                            case 4: //straordinaria     //13-gen-2017 poi bisogna leggere quanto sta scritto nel db
                                                $strtmp="straordinaria";
                                                break;
                                        }
                                    ?><?=$strtmp?>
                                    </td>
                                    
                                </tr>
                                <?php } 
                                }else{ ?>
                                <tr>
                                    <td colspan="8">Nessuno stabilimento definito</td>
                                </tr><?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>