<?php

ob_start();
$pageMenu="dati";
require 'config.php';
require 'db/mysql.php';
include("include/check_user.php");

$pageName="DATI";
include 'include/header.php';

$db= new DbConnect();
$db->open() or die($db->error());
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
            <a href="dati.php">Dati</a>
            <a href="#">Criteri</a>
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
            <h1>Criteri</h1>
            <aside>
<!--                <a href="aggiungi_criterio.php" class="button add">Aggiungi Criterio</a>-->
                <a href="home.php" class="back">Indietro</a>
            </aside>
            <?php if (isset($_GET["succes"])) { ?>
                <div class="row">
                        <div class="span12"><div class="alert alert-success">Salvataggio avvenuto!</div></div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="span12"><div class="title">Requisiti per Ispettori esperti (da Allegato H - d.lgs. 105)</div></div> 
            </div>
            <div class="container scheda">
                <form method="POST" action="saveCriteri.php?op=1">
                    <div class="row">
                        <div class="span4"><label>Avere effettuato un congruo numero di ispezioni</label></div>
                        <div class="span2"><input style="width: 50px" name="SGS_PIR" type="text" maxlength="50" value="5"></div>
                    </div>
                    <div class="row">
                        <div class="span4"><label>Essere in possesso di una comprovata esperienza nel settore dei SGS</label></div>
                        <div class="span2"><input style="width: 50px" name="SGS" type="text" maxlength="50" value="5" disabled></div>
                        <div class="span4"><label>e un periodo di addestramento come uditore</label></div>
                        <div class="span2"><input style="width: 50px" name="SGS" type="text" maxlength="50" value="2" disabled></div>
                    </div>
                    <div class="row">
                        <div class="span4"><label>Aver partecipato a corsi di formazione</label></div>
                        <div class="span2"><input style="width: 50px" name="SGS" type="text" maxlength="50" value="1" disabled></div>
                        <div class="span4"><label>e un periodo di addestramento come uditore</label></div>
                        <div class="span2"><input style="width: 50px" name="SGS" type="text" maxlength="50" value="3" disabled></div>
                    </div>
                    <div class="row">
                        <div class="span12">
                            <button type="submit" class="mt0">Salva</button> 
                            <a class="button button-large pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Annulla</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row"><div class="span12"></div></div>
            <div class="row">
                <div class="span12"><div class="title">Requisiti per assegnare le ispezioni (da DIT)</div></div> 
            </div>
            <div class="container scheda">
                <form method="POST" action="saveCriteri.php?op=2">
                    
                    <div class="row">
                        <div class="span12">
                        <label>UOT:</label> 
                    </div>
                        <div class="span12">
                        <input type="checkbox" name="ispezAnno" value="html" checked="checked" disabled/> Numero di ispettori esperti maggiore del Numero di Stabilimenti da ispezionare
                        </div>
                    </div>
                    <div class="row">
                        <div class="span12">
                        <label>ISPETTORE:</label> 
                        </div>
                        <div class="span12">
                        <input type="checkbox" name="ispezAnno" value="html" checked="checked" disabled/> Numero di ispezioni annuali inferiore a 
                        <input style="width: 50px" name="SGS_PIR" type="text" maxlength="50" value="3"></div>
                        <div class="span12">
                        <input type="checkbox" name="ispezAnno" value="html" checked="checked" disabled/> Ispettore disponibile alla trasferta 
                        </div>
                    </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
