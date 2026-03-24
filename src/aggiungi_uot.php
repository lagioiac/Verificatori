<?php
ob_start();
$pageMenu = "ispettori";
require 'config.php';
require 'db/mysql.php';
require 'class/UotClass.php';
require 'class/ProvinciaClass.php';
require 'class/RegioneClass.php';
include("include/check_user.php");

//AGGIUNTA LISTA DEGLI ISPETTORI E UDITORI
require 'class/CompetenzeClass.php';
include 'class/IspettoreClass.php';
require 'class/RuoloClass.php';
$ispettoreClass = new IspettoreClass();
$competenzeClass = new CompetenzeClass();
$ruoloClass = new RuoloClass();

$pageName="UOT";
include 'include/header.php';

$db= new DbConnect();

$uotClass = new UotClass();
$provinciaClass = new ProvinciaClass();
$regioneClass = new RegioneClass();
$nomiProvinceComp = array();
$idProvinceComp = array();
$provinceregione=null;
$db->open() or die($db->error());

$province = $provinciaClass->getProvince($db);

$uot = -1;
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
    
    $reg=$provinciaClass->getRegioneProvincia($db, $db->mysqli_real_escape($row["provinciaFkId"]));
    $reg = $db->fetchassoc();
    $provinceregione=$provinciaClass->getProvinceStessaRegione($db, $db->mysqli_real_escape($reg["regioneId"]));
    $i=0;
    while ($r = $db->fetchassoc2($provinceregione)){
        $nomiProvinceComp[$i] = $r["prov"];
        $idProvinceComp[$i] = $r["provinciaId"];
        $i++;
    }
    //la uot esiste già, quindi potrebbe avere degli ispettori/uditori
    $listaispettori=$ispettoreClass->getIspettoriByUot($db, $_GET["id"], 1);
    $nispet=count($listaispettori);
    $listauditori=$ispettoreClass->getIspettoriByUot($db, $_GET["id"], 2);
    $nudit=count($listauditori);
}


$regione = $regioneClass->getRegioni($db);

?>

<link rel="stylesheet" href="/js/jquery-tagsinput/jquery.tagsinput.css">      
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="/js/bootstrap-fileupload/bootstrap-fileupload.min.css" />                 
<script type='text/javascript' src='/js/jquery-tagsinput/jquery.tagsinput.js'></script>
<script type="text/javascript" src="/js/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="/js/bootstrap-fileupload/bootstrap-fileupload.js"></script>

<script>
    $(document).ready(function () {
        
        $('#provinceComp').tagsInput({
            width: 'auto',
            defaultText: 'Aggiungi',
            autocomplete_url: 'autocompleteProvince.php' // jquery ui autocomplete requires a json endpoint	 
        });
        
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
        <li class="uno active<?php echo $class_0;?>"><a href="ispettori.php"><img src="img/icon_1.png" alt="icon"><span class="none">Ispettori</span></a></li>
        <li class="due <?php echo $class_1;?>"><a href="stabilimenti.php"><img src="img/icon_2.png" alt="icon"><span class="none">Stabilimenti</span></a></li>
        <li class="tre <?php echo $class_2;?>"><a href="ispezioni.php"><img src="img/icon_3.png" alt="icon"><span>Ispezioni</span></a></li>
        <li class="quattro <?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
    </ul> 
</nav>
 	</div>
</header>
	<input type="hidden" name="ignora" id="uot" value="<?= $uot ?>">
	<section id="page" >
		<div class="container addnew">
			<div class="header">
                <h1>UOT</h1>
                <aside>
                    <a href="uot.php" class="back">Indietro</a>
                </aside>
				<?php if (isset($_GET["msg"])) { ?>
                <div class="row">
                    <div class="span12"><div class="alert alert-error">Compila tutti i campi obbligatori!</div></div>
                </div>
				<?php } ?>
				<?php if (isset($_GET["succes"])) { ?>
					<div class="row">
						<div class="span12"><div class="alert alert-success">Salvataggio avvenuto!</div></div>
					</div>
				<?php } ?>
				<div class="row">
                                        <div class="span12"><div class="title"><?php
                                        if(isset($_GET["id"])){?>Modifica UOT<?php }else{
                                        ?>Inserisci UOT<?php } ?></div></div>
				</div>
				<input type="hidden" name="indice" value="0" id="indice">
				<div class="container scheda">
					<form method="POST" action="saveUot.php">
						<input type="hidden" name="uotId" value="<?= $uotClass->getUotId() ?>">
						<div class="row">
                                                    <div class="span4">
                                                            <label>Denominazione*</label>    
                                                            <input style="width: 300px" name="uotDenominazione" type="text" maxlength="200" value="<?= $uotClass->getUotDenominazione() ?>">
                                                    </div>
                                                    <div class="span4">  
                                                            <label>Citta'*</label>   
                                                            <select style="width: 300px;" name="provincia" id="provincia" >
                                                                    <option value="">Seleziona</option>
                                                                    <?php while ($row = $db->fetchassoc2($province)) { ?>
                                                                            <option value="<?= $row["provinciaId"] ?>" 
                                                                            <?php 
                                                                            if ($uotClass->getUotProvinciaFkId() == $row["provinciaId"]) {
                                                                                    echo 'selected'; 
                                                                            } ?> ><?= $row["prov"] ?></option>
                                                                    <?php } ?>
                                                            </select>
                                                    </div>
                                                    <div class="span4">
                                                            <label>CAP</label>    
                                                            <input style="width: 300px" name="uotCap" type="text" maxlength="200" value="<?= $uotClass->getUotCap() ?>">
                                                    </div>
						</div>
						<div class="row">
                                                    <div class="span4">
                                                            <label>Indirizzo</label>    
                                                            <input style="width: 300px" name="uotIndirizzo" type="text" maxlength="200" value="<?= $uotClass->getUotIndirizzo() ?>">
                                                    </div>
                                                    <div class="span4">
                                                            <label>PEC</label>    
                                                            <input style="width: 300px" name="uotPec" type="text" maxlength="200" value="<?= $uotClass->getUotPec() ?>">
                                                    </div>
                                                    <div class="span4">
                                                            <label>Telefono</label>    
                                                            <input style="width: 300px" name="uotTel" type="text" maxlength="200" value="<?= $uotClass->getUotTelefono() ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="span4">
                                                            <label>Fax</label>    
                                                            <input style="width: 300px" name="uotFax" type="text" maxlength="200" value="<?= $uotClass->getUotFax() ?>">
                                                    </div>
                                                </div>
                                    <div class="row">
                                    <div class="span12">
<!--                                        <label>Province di competenza</label>    -->
<!--                                        <input id="provinceComp" class="tags" type="text" name="nomiProvinceComp" value="<?php print_r(implode(",", $nomiProvinceComp)); ?>">-->
                                    </div>
                                    </div>
                    <div class="row">
                        <div class="span12">
                            <button type="submit" class="mt0">Salva</button> 
                            <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>
                        </div>
                    </div>   
                    <?php if (isset($_GET["id"])) { ?>                   
                    <div class="row">
                        <div class="span8"><label>PERSONALE QUALIFICATO ALLE ISPEZIONI</label>
                            <table>
                                <thead>
                                    <tr>
                                        <th class="span4">Ispettore</th>
                                        <th class="span3">Competenza</th>
                                        <th class="span3">Ruolo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while($row2=$db->fetchassoc2($listaispettori)){
                                        ?><tr>
                                        <td valign="top">
                                            <a href="aggiungi_ispettore.php?id=<?=$row2["ispettoreId"]?>" target=”_blank”><?=$row2["ispettoreCognome"]." ".$row2["ispettoreNome"]?></a>
                                        </td>
                                        <td valign="top">
                                            <?php if($row2["compIdFk"]>0){  //Competenza ispettore
                                                $comp=$competenzeClass->getCompetenzaById($db, $row2["compIdFk"]);
                                                $r3=$db->fetchassoc2($comp);?>
                                                <?=$r3["competenza"]?><?php
                                            }else{?>
                                                <?=""?>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td valign="top"><?php
                                            $ruolo=0;
                                            if($row2["ruoloIdFk"]>0){
                                                $ruolo=$row2["ruoloIdFk"];
                                                $r=$ruoloClass->getRuoloById($db, $row2["ruoloIdFk"]);
                                                $r3=$db->fetchassoc2($r);?>
                                                <img src="<?=$r3["iconaruolo"]?>" alt="icon"><?=" ".$r3["ruolo"]?>
                                            <?php } ?>
                                        </td>
                                        </tr>
                                    <?php 
                                    }
                                    while($row3=$db->fetchassoc2($listauditori)){
                                        ?><tr>
                                        <td valign="top">
                                            <a href="aggiungi_ispettore.php?id=<?=$row3["ispettoreId"]?>" target=”_blank”><?=$row3["ispettoreCognome"]." ".$row3["ispettoreNome"]?></a>
                                        </td>
                                        <td valign="top">
                                            <?php if($row3["compIdFk"]>0){  //Competenza ispettore
                                                $comp=$competenzeClass->getCompetenzaById($db, $row3["compIdFk"]);
                                                $r3=$db->fetchassoc2($comp);?>
                                                <?=$r3["competenza"]?><?php
                                            }else{?>
                                                <?=""?>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td valign="top"><?php
                                            $ruolo=0;
                                            if($row3["ruoloIdFk"]>0){
                                                $ruolo=$row3["ruoloIdFk"];
                                                $r=$ruoloClass->getRuoloById($db, $row3["ruoloIdFk"]);
                                                $r3=$db->fetchassoc2($r);?>
                                                <img src="<?=$r3["iconaruolo"]?>" alt="icon"><?=" ".$r3["ruolo"]?>
                                            <?php } ?>
                                        </td>
                                        </tr>
                                    <?php 
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        ?>
                        </div>
                    </div>
                    <?php } ?>
                </form>
            </div>
            </div>
		</div>
	</section>
	
<?php include '../include/footer.php'; ?> 
</body>
</html>
