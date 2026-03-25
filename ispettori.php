<?php
ob_start();
$pageMenu="ispettori";
require 'config.php';

require 'db/mysql.php';
require 'class/IspettoreClass.php';
require 'class/CompetenzeClass.php';
require 'class/RuoloClass.php';
include("include/check_user.php");

$pageName="ISPETTORI";
include 'include/header.php'; 

$ispettoreCognome="";


$db= new DbConnect();
$ispettoreClass= new IspettoreClass();
$competenzeClass = new CompetenzeClass();
$ruoloClass = new RuoloClass();

$db->open() or die($db->error());
//22-01-2019    modifica nei controlli cerca e nell'ordine alfabetico
$searchispettore=null;
$searchuot=null;
$searchregione=null;

if(!isset($_GET['ordinaPer'])) {
    $_GET['ordinaPer'] = 'uot.uotDenominazione';
}
$ordinaPer=$_GET['ordinaPer'];
if(isset($_POST["searchispettori"]) || isset($_POST["searchuot"]) || isset($_POST["searchregione"])){

    $ispettore=$ispettoreClass->getSearchIspettori($db, $_POST,$_GET['ordinaPer']);
    if(isset($_POST["searchispettori"]) != ""){
        $searchispettore=$_POST["searchispettori"];
    }else{
        $searchispettore=null;
    }
    if(isset($_POST["searchuot"]) != ""){
        $searchuot=$_POST["searchuot"];
    }else{
        $searchuot=null;
    }
    if(isset($_POST["searchregione"]) != ""){
        $searchregione=$_POST["searchregione"];
    }else{
        $searchregione=null;
    }
}else{
    $ispettore=$ispettoreClass->getListaIspettori($db,$_GET['ordinaPer']);
}

if(mysqli_num_rows($ispettore)){$nIspett=mysqli_num_rows($ispettore);}else{$nIspett=0;}


$competenze=$competenzeClass->getCompetenze($db);

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
/* da qui */
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
        <li class="uno active<?php echo $class_0;?>"><a href="ispettori.php"><img src="img/icon_1.png" alt="icon"><span class="none">Ispettori</span></a></li>
        <li class="due <?php echo $class_1;?>"><a href="stabilimenti.php"><img src="img/icon_2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
        <li class="tre <?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
        <li class="quattro <?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
    </ul> 
</nav>
 	</div>
</header>
/* a qui... da togliere in verificatori */

<section id="page" class="ispettore">
	<div class="container ">
		<div class="header">
			<h1>Elenco Ispettori: <?=$nIspett ?></h1>
                        <form method="POST" action="ispettori.php?ordinaPer=<?=$ordinaPer?>">
			<aside>
                            <div>
                            <input name="searchispettori" type="text" placeholder="Cerca ispettore" value="<?=$searchispettore?>" style="display: inline;">
                            <input name="searchuot" type="text" placeholder="Cerca UOT" value="<?=$searchuot?>" style="display: inline;">
                            <input name="searchregione" type="text" placeholder="Cerca Regione" value="<?=$searchregione?>" style="display: inline;">
                            <input type="submit" style="position: absolute; left: -9999px"/>
                            </div>
                            <div>
                            <a href="aggiungi_ispettore.php" class="button add">Aggiungi Ispettore</a>  
                            <a href="uot.php" class="button">UOT</a>
                            <a href="csv_ispettori.php" class="button">Esporta Excel</a>
                            <a href="csv_ispettori_note.php" class="button">Esporta Note</a>
                            <a href="ispettori_out.php" class="button">Non attivi</a>
                            <a href="home.php" class="back">Indietro</a>
                            </div>
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
							<th class="span5"><a href="ispettori.php?ordinaPer=ispettore.ispettoreCognome"><img src="img/icon_sort_asc.png" alt="icon"></a> Cognome</th>
							<th class="span5"> Nome</th>
                                                        <th class="span5"><a href="ispettori.php?ordinaPer=uot.uotDenominazione"><img src="img/icon_sort_asc.png" alt="icon"></a> UOT</th>
                                                        <th class="span5"> Competenza</th>
                                                        <th class="span5"> Ruolo</th>
							
						</tr>
					</thead>
					<tbody>
						<?php 
                                                if(mysqli_num_rows($ispettore)>0){
                                                    while($row=$db->fetchassoc2($ispettore)){
                                                ?>
						<tr>
                                                        <td valign="top">
                                                            <a href="aggiungi_ispettore.php?id=<?=$row["ispettoreId"]?>">
                                                            <?=$row["ispettoreCognome"]?></a>
                                                        </td>
                                                        <td valign="top"><?=$row["ispettoreNome"]?></td>
                                                        <td valign="top">
<!--                                                        UOT DIVENTA UN LINK ATTIVO 10-02-2017-->
                                                            <a href="aggiungi_uot.php?id=<?=$row["uotIspIdFk"]?>" target=”_blank”>
                                                            <?=$row["uotDenominazione"]?></a></td>
                                                        <td valign="top">
                                                            <?php if($row["compIdFk"]>0){
                                                                $comp=$competenzeClass->getCompetenzaById($db, $row["compIdFk"]);
                                                                $row2=$db->fetchassoc2($comp);?>
                                                                <?=$row2["competenza"]?><?php
                                                            }else{?>
                                                                <?=""?>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            if ($row["ruoloIdFk"]== $idruolo[0]) {
                                                                ?><img src="<?=$colore[0]?>" alt="icon"><?= $tiporuolo[0]?><?php ;
                                                            } elseif ($row["ruoloIdFk"]== $idruolo[1]){
                                                                ?><img src="<?=$colore[1]?>" alt="icon"><?= $tiporuolo[1]?><?php ;
                                                             }elseif ($row["ruoloIdFk"]== $idruolo[2]){
                                                                ?><img src="<?=$colore[2]?>" alt="icon"><?= $tiporuolo[2]?><?php ;
                                                             } ?>
                                                        </td>
						</tr>
						<?php } 
						}else{ ?>
                        <tr>
                            <td colspan="8">Nessun ispettore definito</td>
                        </tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<?php include '../include/footer.php';?>
    
</body>
</html>