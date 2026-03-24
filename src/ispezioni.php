<?php

ob_start();
$pageMenu="ispezioni";
require 'config.php';

require 'db/mysql.php';
require 'class/IspezioneClass.php';
require 'class/StabilimentoClass.php';
require 'class/AttivitaIndustrialeClass.php';
require 'class/ComuneClass.php';
require 'class/UotClass.php';
require 'class/IspettoreClass.php';
require 'class/RuoloClass.php';

include("include/check_user.php");

$pageName="STABILIMENTI";
include 'include/header.php'; 

//06/11/2018 modifica: la tabella delle ispezioni ha una colonna in meno
// il comando per modificare un'ispezione viene assegnato allo stato

$db= new DbConnect();
$db->open() or die($db->error());

$ispezioneClass=new IspezioneClass();
$stabilimentoClass= new StabilimentoClass();
$attivitaIndustrialeClass= new AttivitaIndustrialeClass();
$comuneClass = new ComuneClass();
$uotClass = new UotClass();
$ispettoreClass= new IspettoreClass();

//22-01-2019 modifica per mantenere quanto si vuole cercare 
$searchattiv=null;
$searchstato=null;
$searchuot=null;
$searchispet=null;
$searchreg=null;

if(isset($_POST["searchattiv"]) || isset($_POST["searchstato"]) || isset($_POST["searchuot"]) || isset($_POST["searchispet"]) || isset($_POST["searchreg"])){
    $ispezioni=$ispezioneClass->getSearchIspezioni($db, $_POST);
    if(isset($_POST["searchattiv"]) != ""){
        $searchattiv=$_POST["searchattiv"];
    }else{
        $searchattiv=null;
    }
    if(isset($_POST["searchstato"]) != ""){
        $searchstato=$_POST["searchstato"];
    }else{
        $searchstato=null;
    }
    if(isset($_POST["searchuot"]) != ""){
        $searchuot=$_POST["searchuot"];
    }else{
        $searchuot=null;
    }
    if(isset($_POST["searchispet"]) != ""){
        $searchispet=$_POST["searchispet"];
    }else{
        $searchispet=null;
    }
    if(isset($_POST["searchreg"]) != ""){
        $searchreg=$_POST["searchreg"];
    }else{
        $searchreg=null;
    }
    
}else{   
    $ispezioni=$ispezioneClass->getListaIspezioni($db);
}
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
$ruoloClass = new RuoloClass();
$tiporuolo = array();
$idruolo=array();
$coloreruolo = array();
$elencoruoli = $ruoloClass->getRuoli($db);
$i=1;
while ($r = $db->fetchassoc2($elencoruoli)){
    $idruolo[$i] = $r["ruoloId"];
    $tiporuolo[$i] = $r["ruolo"];
    $coloreruolo[$i] = $r["iconaruolo"];    //aggiunto il 26/04/2017
    $i++;
}
//aggiunto 05-02-2017
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
                <li class="due <?php echo $class_1;?>"><a href="stabilimenti.php"><img src="img/icon_2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
                <li class="tre active<?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
                <li class="quattro <?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
            </ul> 
        </nav>
 	</div>
    </header>
    <section id="page" class="ispezione">
        <div class="container ">
            <div class="header">
                <h1>Elenco Ispezioni: <?=$nIspez?></h1>
                <form method="POST" action="ispezioni.php">
                    <aside>
                        <input name="searchattiv" type="text" placeholder="Cerca attivita'" value="<?=$searchattiv?>" style="display: inline;">
                        <input name="searchstato" type="text" placeholder="Cerca stato" value="<?=$searchstato?>" style="display: inline;">
                        <input name="searchuot" type="text" placeholder="Cerca UOT" value="<?=$searchuot?>" style="display: inline;">
                        <input name="searchispet" type="text" placeholder="Cerca Ispettore" value="<?=$searchispet?>" style="display: inline;">
                        <input name="searchreg" type="text" placeholder="Cerca Regione" value="<?=$searchreg?>" style="display: inline;">
                        <input type="submit" style="position: absolute; left: -9999px"/>
                        <a href="aggiungi_ispezione.php" class="button add">Aggiungi Ispezione</a>
                        <a href="csv_ispezioni.php" class="button">Esporta Excel</a>
                        <a href="csv_ispezioni_note.php" class="button">Esporta Note</a>
                        <a href="storico.php?an=0" class="button">STORICO</a>
                        <a href="sospese.php?an=0" class="button">SOSPESE</a>
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
                                    <th class="span2"> Anno</th>
                                    <th class="span3"> Stabilimento</th>
                                    <th class="span3"> Attività Industriale</th>
                                    <th class="span2"> Provincia</th>
                                    <th class="span2"> Regione</th>
                                    <th class="span3"> UOT-Stab.</th>
                                    <th class="span3"> Ispettori</th>
                                    <th class="span2"> Uditori</th>
                                    <th class="span2"> Tipo Ispez.</th>
                                    <th class="span2"> Stato Ispezione</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysqli_num_rows($ispezioni)){
                                    while($row=$db->fetchassoc2($ispezioni)){ ?>
                                <tr>
                                    <td valign="top"><?=$row["anno"]?></td>
                                    <td valign="top">
                                        <?php //get nome stabilimento
                                        $stabilimentoClass->setStabilimentoId($row["stabIdFk"]);
                                        $stabilimentoClass->getDettaglioStabilimento($db);
                                        $row2 = $db->fetchassoc();
                                        //LO STABILIMENTO DIVENTA UN LINK ATTIVO 10-02-2017
                                        ?>
                                        <a href="aggiungi_stabilimento.php?id=<?=$row["stabIdFk"]?>" target=”_blank”><?=$row2["stabilimentoDenominazione"]?></a>
                                        
                                    </td>
                                    <td valign="top"><?php
                                        $att=$attivitaIndustrialeClass->getAttivitaById($db, $row2["attivIndustrialeIdFk"]);
                                        $row3=$db->fetchassoc2($att);?>
                                        <?=$row3["attivita"]?>
                                    </td>
                                    <td valign="top">
                                        <?php //get provincia 
                                        $provtmp=$comuneClass->getProvByComune($db, $db->mysqli_real_escape($row2["comuneIdFk"]));
                                        $row4=$db->fetchassoc2($provtmp);
                                        ?><?=$row4["prov"]?>
                                    </td>
                                    <td valign="top">
                                        <?php //get regione 
                                        $regtmp=$comuneClass->getRegioneByComune($db, $db->mysqli_real_escape($row2["comuneIdFk"]));
                                        $row5=$db->fetchassoc2($regtmp);
                                        ?><?=$row5["nomeregione"]?>
                                    </td>
                                    <td valign="top">
                                        <?php //get uot 
                                        $uottmp=$uotClass->getUotById($db, $db->mysqli_real_escape($row2["uotAffIdFk"]));
                                        $row6=$db->fetchassoc2($uottmp);
                                        //UOT DIVENTA UN LINK ATTIVO 10-02-2017
                                        ?>
                                        <a href="aggiungi_uot.php?id=<?=$row2["uotAffIdFk"]?>" target=”_blank”><?=$row6["uotDenominazione"]?></a>
                                    </td>
                                    <td valign="top"><?php
                                    $strtmp1=""; $k1="";
                                        if($row["ispettIdFk"]>0){   //get ispettore
                                            $isptmp=$ispettoreClass->getIspettoreById($db, $db->mysqli_real_escape($row["ispettIdFk"]));
                                            $row7=$db->fetchassoc2($isptmp);
//                                            $k1=$colore[$db->mysqli_real_escape($row7["ruoloIdFk"])]; modifica 26-04-2017
                                            $k1=$coloreruolo[$db->mysqli_real_escape($row7["ruoloIdFk"])];
                                            $strtmp1=$db->mysqli_real_escape($row7["ispettoreCognome"]);
                                        }
                                        //GLI ISPETTORI SONO LINK ATTIVI 10-02-2017
                                        if($strtmp1!=""){
                                            ?><a href="aggiungi_ispettore.php?id=<?=$row["ispettIdFk"]?>" target=”_blank”><img src="<?=$k1?>" alt="icon"><?=$strtmp1."<br>"?> </a>
                                        <?php
                                        } ?>
                                    </td>
                                    <td valign="top"><?php
                                    $strtmp2=""; $k2="";
                                        if($row["uditIdFk"]>0){   //get uditore
                                            $isptmp=$ispettoreClass->getIspettoreById($db, $db->mysqli_real_escape($row["uditIdFk"]));
                                            $row7=$db->fetchassoc2($isptmp);
                                            $k2=$coloreruolo[2];   //$db->mysqli_real_escape($row7["ruoloIdFk"])]; //26-04-2017
                                            $strtmp2=$db->mysqli_real_escape($row7["ispettoreCognome"]);
                                        }
                                        //GLI ISPETTORI SONO LINK ATTIVI 10-02-2017
                                        if($strtmp2!=""){
                                                ?><a href="aggiungi_ispettore.php?id=<?=$row["uditIdFk"]?>" target=”_blank”><img src="<?=$k2?>" alt="icon"><?=$strtmp2 ?> </a>
                                            <?php } ?>
                                    </td>
                                    <td valign="top"><?php
                                        $i=$row["tipoispez"];
                                        $strtmp=$abbrevtipoispez[$i];
//                                        switch ($row["tipoispez"]) {
//                                            case 1: //annuale
//                                                $strtmp="annuale";
//                                                break;
//                                            case 2: //biennale
//                                                $strtmp="biennale";
//                                                break;
//                                            case 3: //triennale
//                                                $strtmp="triennale";
//                                                break;
//                                            case 4: //straordinaria     //13-gen-2017 poi bisogna leggere quanto sta scritto nel db
//                                                $strtmp="straordinaria";
//                                                break;
//                                        }
                                    ?><?=$strtmp?>
                                    </td>
                                    <td valign="top"><a href="aggiungi_ispezione.php?id=<?=$row["ispezioneId"]?>"><?php
                                        if(($row["statoIdFk"]==1) OR ($row["statoIdFk"]==5)){ //archiviate o concluse 21/04/2017
                                            ?><img src="<?=$colore[1]?>" alt="icon"><?= $tipostato[1]?><?php
                                        }elseif($row["statoIdFk"]==2){ //assegnata
                                            ?><img src="<?=$colore[2]?>" alt="icon"><?= $tipostato[2]?><?php
                                        }elseif($row["statoIdFk"]==3){ //da pianificare
                                            ?><img src="<?=$colore[3]?>" alt="icon"><?= $tipostato[3]?><?php
                                        }elseif($row["statoIdFk"]==4){ //sospesa
                                            ?><img src="<?=$colore[4]?>" alt="icon"><?= $tipostato[4]?><?php
                                        }
                                        ?></a>
                                    </td>
                                </tr>
                                <?php } 
                                }else{ ?>
                                <tr>
                                    <td colspan="12">Nessuna ispezione definita</td>
                                </tr><?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</body>