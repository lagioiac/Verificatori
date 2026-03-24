<?php
ob_start();
$pageMenu="ispettori";
require 'config.php';

require 'db/mysql.php';
require 'class/UotClass.php';
require 'class/RegioneClass.php';
require 'class/UotRegioneClass.php';
require 'class/RuoloClass.php';
include("include/check_user.php");

$pageName="UOT";
include 'include/header.php'; 

$uotDenominazione="";

$db= new DbConnect();
$uotClass= new UotClass();
$regioneClass = new RegioneClass();
$uot_regioneClass= new UotRegioneClass();

$db->open() or die($db->error());

if(count($_POST)>0){
    $uotDenominazione=$_POST["uotDenominazione"];
}

$uot=$uotClass->getLastRecord($db);

$regione = $regioneClass->getRegioni($db);
$uot_regione=null;

$ruoloClass = new RuoloClass();
$colore = array();
$tiporuolo = array();
$idruolo=array();
$elencoruoli = $ruoloClass->getRuoli($db);
$i=1;
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

<section id="page" class="uot">
	<div class="container ">
		<div class="header">
			<h1>Elenco UOT - Unita' operativa Territoriale di Certificazione, Verifica e Ricerca</h1>
                        <form method="POST" action="uot.php">
			<aside>
                            <a href="aggiungi_uot.php" class="button add">Aggiungi UOT</a>
                            <a href="csv_uot.php" class="button">Esporta Excel</a>
                            <a href="ispettori.php" class="back">Indietro</a>
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
                                        <th class="span5">Regione</th>
                                        <th class="span5">Denominazione</th>
                                        <th class="span5">Ispettori/Ruolo</th>
                                        <th width="150px"></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(mysqli_num_rows($regione)){
                                        while($row=$db->fetchassoc2($regione)){ ?>
                                        <tr>
                                            <td valign="top"><?=$row["nomeregione"]?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
                                        </tr>
                                        <?php   //cerca tutte le uot di una data regione
                                            $idRegione=(int)$row["regioneId"];
                                            $uot_regione=$uot_regioneClass->getUotRegione($db, $idRegione); 
                                            if(mysqli_num_rows($uot_regione)) {
                                                while($row2=$db->fetchassoc2($uot_regione)){?>
                                                <tr>
                                                    <td></td>
                                                    <td valign="top"><?=$row2["uotDenominazione"]?></td>
                                                    <?php //conta quanti ispettori ci sono suddividendoliper ruolo
                                                    $k1=0;
                                                    $k2=0;
                                                    $k3=0;
                                                    $nexp=$uotClass->contaRuoliByUot($db, $row2["uotId"], 1);
                                                    while($row3= $db->fetchassoc2($nexp)){ 
                                                        if($db->mysqli_real_escape($row3["cont"])>0){
                                                            $k1=$db->mysqli_real_escape($row3["cont"]);
                                                        }
                                                    }
                                                    $nexp=$uotClass->contaRuoliByUot($db, $row2["uotId"], 2);
                                                    while($row3= $db->fetchassoc2($nexp)){ 
                                                        if($db->mysqli_real_escape($row3["cont"])>0){
                                                            $k2=$db->mysqli_real_escape($row3["cont"]);
                                                        }
                                                    }
                                                    $nexp=$uotClass->contaRuoliByUot($db, $row2["uotId"], 3);
                                                    while($row3= $db->fetchassoc2($nexp)){ 
                                                        if($db->mysqli_real_escape($row3["cont"])>0){
                                                            $k3=$db->mysqli_real_escape($row3["cont"]);
                                                        }
                                                    }
                                                    ?>
                                                    <td><?php
                                                    if($k1>0){
                                                        ?><img src="<?=$colore[1]?>" alt="icon"><?= $tiporuolo[1].": ".$k1."<br>"?><?php
                                                    }
                                                    if($k2>0){
                                                        ?><img src="<?=$colore[2]?>" alt="icon"><?= $tiporuolo[2].": ".$k2."<br>"?><?php
                                                    }
                                                    if($k3>0){
                                                        ?><img src="<?=$colore[3]?>" alt="icon"><?= $tiporuolo[3].": ".$k3."<br>"?><?php
                                                    }
                                                    ?>
                                                    </td>
                                                    <td valign="top"><a href="aggiungi_uot.php?id=<?=$row2["uotId"]?>"><img src="img/icon-mod.png" alt="icon"></a></td>
                                                </tr>
                                            <?php }} ?>
                                    <?php } } ?>
                                </tbody>
                            </table>
                    </div>
		</div>
           </div>
	</div>
</section>

<?php include '../include/footer.php';?>
</body>
</html>