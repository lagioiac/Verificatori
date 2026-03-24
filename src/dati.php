<?php
ob_start();
$pageMenu="dati";
require 'config.php';
require 'db/mysql.php';
include("include/check_user.php");

require 'class/RegioneClass.php';
require 'class/ProvinciaClass.php';
require 'class/ComuneClass.php';

$pageName="DATI";
include 'include/header.php';

$db= new DbConnect();
$db->open() or die($db->error());

$regioneClass = new RegioneClass();
$regioni=$regioneClass->getRegioni($db);

$provinciaClass = new ProvinciaClass();
$province=$provinciaClass->getProvince($db);

$comuneClass=new ComuneClass();
?>
<script>
</script>

</head>

<body>
    <header>
	<div class="container">
    	<div class="row">
	<div class="span12">
        <div class="logo" style="border-bottom: 10px;"><a href="home.php">newRISPE</a></div>  
        <div class="info">
            <a href="#">Dati</a>
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
        <li class="quattro <?php echo $class_3;?>"><a href="pianificazione.php"><img src="img/icon_4.png" alt="icon"><span>Pianificazione</span></a></li>
    </ul> 
</nav>
 	</div>
</header>

    <section id="page" >
        <div class="container addnew">
            <div class="header">
            <h1>Dati di riferimento</h1>
            <aside>
                <a href="home.php" class="back">Indietro</a>
            </aside>
            <?php if (isset($_GET["succes"])) { ?>
                <div class="row">
                        <div class="span12"><div class="alert alert-success">Salvataggio avvenuto!</div></div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="span12"><div class="title">Inserisci Dati</div></div> 
            </div>
            
            <div class="container scheda">
                <form method="POST" action="saveDati.php?op=1">
                    <div class="row">
                        <div class="span6">
                            <label>Corso di formazione*</label>    
                            <input style="width: 570px" name="corso" type="text" maxlength="200" value="">
                        </div>
                        <div class="span3">
                            <label>Mese</label>    
                            <input style="width: 270px" name="mese" type="text" maxlength="200" value="">
                        </div>
                        <div class="span3">
                            <label>Anno*</label>    
                            <input style="width: 270px" name="anno" type="text" maxlength="200" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="span12">
                            <button type="submit" class="mt0">Salva</button> 
                            <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>
                        </div>
                    </div>
                </form>
                <form method="POST" action="saveDati.php?op=2">
                    <div class="row">
                        <div class="span12">
                            <label>Competenza</label>    
                            <input style="width: 570px" name="competenza" type="text" maxlength="200" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="span12">
                            <button type="submit" class="mt0">Salva</button> 
                            <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>
                        </div>
                    </div>
                </form>
                <form method="POST" action="saveDati.php?op=3">
                    <div class="row">
                        <div class="span12">
                            <label>Regione</label>    
                            <input style="width: 570px" name="regione" type="text" maxlength="200" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="span12">
                            <button type="submit" class="mt0">Salva</button> 
                            <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>
                        </div>
                    </div>
                </form>
                <form method="POST" action="saveDati.php?op=4">
                    <div class="row">
                        <div class="span6">
                            <label>Provincia*</label>    
                            <input style="width: 570px" name="provincia" type="text" maxlength="200" value="">
                        </div>
                        <div class="span4">  
                            <label>Regione*</label>   
                            <select style="width: 270px;" name="regioni" >
                                    <option value="">Seleziona</option>
                                    <?php while ($row = $db->fetchassoc2($regioni)) { ?>
                                            <option value="<?= $row["regioneId"] ?>" 
                                            <?php 
                                            if ($regioneClass->getRegioneId() == $row["regioneId"]) {
                                                    echo 'selected'; 
                                            } ?> ><?= $row["nomeregione"] ?></option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span12">
                            <button type="submit" class="mt0">Salva</button> 
                            <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>
                        </div>
                    </div>
                </form>
                <form method="POST" action="saveDati.php?op=5">
                    <div class="row">
                        <div class="span6">
                            <label>Comune*</label>    
                            <input style="width: 570px" name="comune" type="text" maxlength="200" value="">
                        </div>
                        <div class="span4">  
                            <label>Provincia*</label>   
                            <select style="width: 270px;" name="province" >
                                    <option value="">Seleziona</option>
                                    <?php while ($row2 = $db->fetchassoc2($province)) { ?>
                                            <option value="<?= $row2["provinciaId"] ?>" 
                                            <?php 
                                            if ($provinciaClass->getProvinciaId() == $row2["provinciaId"]) {
                                                    echo 'selected'; 
                                            } ?> ><?= $row2["prov"] ?></option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span12">
                            <select style="width: 270px;" name="listacomuni" id="listacomuni" >
                                    <option value="">Comuni inseriti</option>
                                    <?php $comuni = $comuneClass->getListaComuni($db);
                                        while ($row = $db->fetchassoc2($comuni)) { ?>
                                            <option value="<?= $row["comuneId"] ?>" 
                                            <?php 
                                             ?> ><?= $row["comuneNome"] ?></option>
                                    <?php } ?>
                            </select>
                            <button type="submit" class="mt0">Salva</button> 
                            <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>
                        </div>
                    </div>
                </form>
                <form method="POST" action="saveDati.php?op=6">
                    <div class="row">
                        <div class="span12">
                            <label>Attivita' Industriale</label>    
                            <input style="width: 570px" name="attivita" type="text" maxlength="200" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="span12">
                            <button type="submit" class="mt0">Salva</button> 
                            <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>
                        </div>
                    </div>
                </form>
            </div>
    </section>

</body>
