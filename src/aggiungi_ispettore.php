<?php
ob_start();
$pageMenu = "ispettore";
require 'config.php';
require 'db/mysql.php';
require 'class/IspettoreClass.php';
require 'class/UotClass.php';
require 'class/CompetenzeClass.php';
require 'class/CorsoFormazioneClass.php';
require 'class/RuoloClass.php';
include("include/check_user.php");

//AGGIUNTA LISTA DELLE ISPEZIONI STABILIMENTO
include 'class/IspezioneClass.php';
include 'class/StabilimentoClass.php';
require 'class/AttivitaIndustrialeClass.php';
$ispezioneClass = new IspezioneClass();
$stabilimentoClass=new StabilimentoClass();
$attivitaIndustrialeClass= new AttivitaIndustrialeClass();

$pageName="ISPETTORE";
include 'include/header.php';

$db= new DbConnect();

$ispettoreClass = new IspettoreClass();
$uotClass = new UotClass();
$competenzaClass = new CompetenzeClass();
$corsoformazioneClass = new CorsoFormazioneClass();
$ruoloClass = new RuoloClass();

$db->open() or die($db->error());

$ispettore = -1;

$uot = $uotClass->getUot($db);
$competenza = $competenzaClass->getCompetenze($db);
$corsoformazione = $corsoformazioneClass->getCorsoFormazione($db);
$elencoruoli = $ruoloClass->getRuoli($db);

if (isset($_GET["id"])) {
    $ispettore = $_GET["id"];
    $ispettoreClass->setIspettoreId($_GET["id"]);
    $ispettoreClass->getDettaglioIspettore($db);
    $row = $db->fetchassoc();
    
    $ispettoreClass->setIspettoreCognome($row["ispettoreCognome"]);
    $ispettoreClass->setIspettoreNome($row["ispettoreNome"]);
    $ispettoreClass->setIspettoreTel($row["ispettoreTel"]);
    $ispettoreClass->setEmail($row["email"]);
    $ispettoreClass->setUotIspIdFk($row["uotIspIdFk"]);
    $ispettoreClass->setCompIdFk($row["compIdFk"]);
    $ispettoreClass->setNroIspSGSAu($row["nroIspSGSAu"]);
    $ispettoreClass->setAnniEspSGSAu($row["anniEspSGSAu"]);
    $ispettoreClass->setNroIspUditAu($row["nroIspUditAu"]);
    $ispettoreClass->setCorsoIdAu($row["corsoIdAu"]);
    
    $ispettoreClass->setRuoloIdFk($row["ruoloIdFk"]);
    $ispettoreClass->setFlgDispTrasferta($row["flgDispTrasferta"]);
    $ispettoreClass->setNoteIspettore($row["noteIspettore"]);
    //11/03/2019
    $ispettoreClass->setAttivo($row["attivo"]);
    
    //AGGIUNTA LA TABELLA DELLE ISPEZIONI ASSEGNATE
    $listaispezioni=$ispezioneClass->getIspezioniByIspettoreUditore($db, $_GET["id"]);
    $statoClass = new RuoloClass();
    $colorestato = array();
    $tipostato = array();
    $idstato=array();
    $elencostati = $statoClass->getStati($db);
    $i=1;
    while ($r = $db->fetchassoc2($elencostati)){
        $idruolo[$i] = $r["statoId"];
        $colorestato[$i] = $r["iconastato"];
        $tipostato[$i] = $r["stato"];
        $i++;
    }
    //AGGIUNTA LA TABELLA ISPEZIONI RIFIUTATE
    $listaispezionirifiutate=$ispezioneClass->getIspezioniRifiutateByIspettoreUditore($db, $_GET["id"]);
    
}

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

<link rel="stylesheet" href="/js/jquery-tagsinput/jquery.tagsinput.css">      
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="/js/bootstrap-fileupload/bootstrap-fileupload.min.css" />                 
<script type='text/javascript' src='/js/jquery-tagsinput/jquery.tagsinput.js'></script>
<script type="text/javascript" src="/js/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="/js/bootstrap-fileupload/bootstrap-fileupload.js"></script>

<script type="text/javascript">

$(function () {
    
}
</script>

</head>
<body>
<header>
	<div class="container">
    	<div class="row">
	<div class="span12">
        <div class="logo" style="border-bottom: 10px;"><a href="home.php">newRISPE</a><span>-Ispettore</span></div>    
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


	<input type="hidden" name="ignora" id="ispettore" value="<?= $ispettore ?>">
	<section id="page" >
		<div class="container addnew">
			<div class="header">
                <h1>Ispettore</h1>
                <aside>
                    <a href="ispettori.php" class="back">Indietro</a>
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
                        if(isset($_GET["id"])){?>Modifica Ispettore<?php }else{
                        ?>Inserisci Ispettore<?php } ?></div></div>
                </div>
                <input type="hidden" name="indice" value="0" id="indice">
                <div class="container scheda">
                    <form method="POST" action="saveIspettore.php">
                        <input type="hidden" name="ispettoreId" value="<?= $ispettoreClass->getIspettoreId() ?>">
                        <div class="row">
                            <div class="span4">
                                <label>Cognome*</label>    
                                <input style="width: 300px" name="ispettoreCognome" type="text" maxlength="200" value="<?= $ispettoreClass->getIspettoreCognome() ?>">
                            </div>
                            <div class="span4">
                                <label>Nome*</label>    
                                <input style="width: 300px" name="ispettoreNome" type="text" maxlength="200" value="<?= $ispettoreClass->getIspettoreNome() ?>">
                            </div>
                            <div class="span4">
                                <label>UOT*</label>
                                <select style="width: 300px;" name="uottmp"  >
                                        <option value="">Seleziona</option>
                                        <?php while ($row = $db->fetchassoc2($uot)) { ?>
                                                <option value="<?= $row["uotId"] ?>" 
                                                <?php 
                                                if ($ispettoreClass->getUotIspIdFk() == $row["uotId"]) {
                                                        echo 'selected'; 
                                                } ?> ><?= $row["uotDenominazione"] ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span4">
                                <label>eMail</label>    
                                <input style="width: 300px" name="email" type="text" maxlength="200" value="<?= $ispettoreClass->getEmail() ?>">
                            </div>
                            <div class="span4">
                                <label>Telefono</label>    
                                <input style="width: 300px" name="ispettoreTel" type="text" maxlength="200" value="<?= $ispettoreClass->getIspettoreTel() ?>">
                            </div>
                            <div class="span4">
                                <label>Competenza*</label>
                                <select style="width: 300px;" name="competenza" >
                                        <option value="">Seleziona</option>
                                        <?php while ($row = $db->fetchassoc2($competenza)) { ?>
                                                <option value="<?= $row["competenzaId"] ?>" 
                                                <?php 
                                                if ($ispettoreClass->getCompIdFk() == $row["competenzaId"]) {
                                                        echo 'selected'; 
                                                } ?> ><?= $row["competenza"] ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span12">
                                    <label>INFORMATIVA SGS-PIR (Acquisita)</label>
                            </div>
                        </div>
                        <div class="row">
                                <div class="span2">
                                    <label>Ispez. SGS-PIR*</label>    
                                    <input style="width: 100px" name="nroIspSGSAu" type="text" maxlength="10" value="<?= $ispettoreClass->getNroIspSGSAu() ?>">
                                </div>
                                <div class="span2">
                                    <label>Anni Esp. SGS*</label>    
                                    <input style="width: 100px" name="anni_SGS" type="text" maxlength="10" value="<?= $ispettoreClass->getAnniEspSGSAu() ?>">
                                </div>
                                <div class="span2">
                                    <label>Ispez. Uditore*</label>    
                                    <input style="width: 100px" name="ispez_ud" type="text" maxlength="10" value="<?= $ispettoreClass->getNroIspUditAu() ?>">
                                </div>
                                <div class="span6">
                                <label>Corso di formazione*</label>
                                <select style="width: 500px;" name="corsoformazione" >
                                        <option value="">Seleziona</option>
                                        <?php while ($row = $db->fetchassoc2($corsoformazione)) { ?>
                                                <option value="<?= $row["corsoId"] ?>" 
                                                <?php 
                                                if ($ispettoreClass->getCorsoIdAu() == $row["corsoId"]) {
                                                        echo 'selected'; 
                                                } ?> ><?= $row["titolo"]." - ".$row["mese"]." - ".$row["anno"] ?></option>
                                        <?php } ?>
                                </select>
                                </div>
                                <div class="span4">
                                    <label>Disponibile alla trasferta</label>    
                                    <label>Sì &nbsp;<input type="radio" name="flgDispTrasferta" value="1" <?php 
                                    $tipo=$ispettoreClass->getFlgDispTrasferta();
                                    if($tipo=="1"){ echo 'checked="checked"'; }
                                    ?> style="display: inline;"/> &nbsp; No &nbsp; <input type="radio" name="flgDispTrasferta" <?php 
                                    if($tipo=="2" ){ echo 'checked="checked"'; }?> value="2" />
                                    &nbsp; Non so &nbsp; <input type="radio" name="flgDispTrasferta" <?php 
                                    if($tipo=="0" || !isset($tipo)){ echo 'checked="checked"'; }?> value="0" />
                                    </label>
                                </div>
                                <div class="span8">
                                    <label>Note</label>   
                                    <textarea style="width: 700px" name="noteIspettore" type="textarea" rows="4"><?= $ispettoreClass->getNoteIspettore() ?></textarea>
                                </div>
                            </div>
                        <div class="row">
                            <div class="span2">
                                <label>Ruolo</label>   
                                <?php $j=$ispettoreClass->getRuoloIdFk();
                                    if ($j >0) {
                                        ?><img src="<?= $colore[$j]?>" alt="icon"><?= $tiporuolo[$j]?>
                                        <input type="hidden" name="ispruolo" value="<?= $idruolo[$j] ?>"><?php ;
                                    }else{
                                        $j=3;   //nuovo
                                        ?><img src="<?= $colore[$j]?>" alt="icon"><?= $tiporuolo[$j]?>
                                        <input type="hidden" name="ispruolo" value="<?= $idruolo[$j] ?>">
                                        <?php ;
                                 } ?>
                            </div>
                            <div class="span4">
                                <label>Numero di ispezioni come Uditore (dal 2016):</label>
                                <?php 
                                if (isset($_GET["id"])) {
                                    $nId=$ispettoreClass->getIspettoreId();
                                    $k1=$ispettoreClass->contaIspezioniByUditore($db, $nId,1);
                                    $nudiz=0;
                                    while($row31= $db->fetchassoc2($k1)){ 
                                        if($db->mysqli_real_escape($row31["cont"])>0){
                                            $nudiz=$db->mysqli_real_escape($row31["cont"]);
                                        }
                                    }
                                }else{
                                    $nudiz=0;
                                }
                                   ?> <input style="width: 100px" name="nudiz" type="text" maxlength="10" value="<?= $nudiz ?>" disabled>

                            </div>
                            <div class="span4">
                                <label>Stato ispettore</label>
                                <label>Attivo &nbsp;<input type="radio" name="flgAttivo" value="0" <?php 
                                    $tipo=$ispettoreClass->getAttivo();
                                    if($tipo=="0"){ echo 'checked="checked"'; }
                                    ?> style="display: inline;"/> &nbsp; Non attivo &nbsp; <input type="radio" name="flgAttivo" <?php 
                                    if($tipo=="1" ){ echo 'checked="checked"'; }?> value="1" />
                                    &nbsp; Esperto &nbsp; <input type="radio" name="flgRuolo" <?php 
                                    if($j=="1" ){ echo 'checked="checked"'; }?> value="1" />
                                    &nbsp; Uditore &nbsp; <input type="radio" name="flgRuolo" <?php 
                                    if($j=="2" || $j=="3" ){ echo 'checked="checked"'; }?> value="2" />
                                </label>
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
                            <div class="span8"><label>ISPEZIONI ASSEGNATE</label>
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="span1">Anno</th>
                                            <th class="span4">Stabilimento</th>
                                            <th class="span4">Attivita Ind.</th>
                                            <th class="span2">Regione</th>
                                            <th class="span3">Stato</th>
                                            <th class="span3">Ruolo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    while($row2=$db->fetchassoc2($listaispezioni)){
                                        ?>
                                        <tr>
                                            <td valign="top"><?=$row2["anno"]?></td>
                                            <td valign="top">
                                                <?php
                                                $stab=$row2["stabIdFk"];
                                                $stabilimentoClass->setStabilimentoId($stab);
                                                $s=$stabilimentoClass->getDettaglioStabilimento($db);
                                                $r3=$db->fetchassoc2($s);
                                                ?>
                                                <a href="aggiungi_stabilimento.php?id=<?=$r3["stabilimentoId"]?>" target=”_blank”><?=$r3["stabilimentoDenominazione"]?></a>
                                            </td>
                                            <td valign="top">
                                                <?php
                                                $att=$r3["attivIndustrialeIdFk"];
                                                $a=$attivitaIndustrialeClass->getAttivitaById($db, $att);
                                                $r4=$db->fetchassoc2($a);
                                                ?><?=$r4["attivita"]?>
                                            </td>
                                            <td valign="top">
                                                <?php
                                                $reg=$r3["comuneIdFk"];
                                                $rg=$stabilimentoClass->getRegioneStabilimento($db, $reg);
                                                $r5=$db->fetchassoc2($rg);
                                                ?><?=$r5["nomeregione"]?>
                                            </td>
                                            <td valign="top"><a href="aggiungi_ispezione.php?id=<?=$row2["ispezioneId"]?>"><?php    //28/01/2019
                                                if($row2["statoIdFk"]==1){ //archiviata modifica 21/04/2017
                                                    ?><img src="<?=$colorestato[1]?>" alt="icon"><?= $tipostato[1]?><?php
                                                }elseif($row2["statoIdFk"]==2){ //assegnata
                                                    ?><img src="<?=$colorestato[2]?>" alt="icon"><?= $tipostato[2]?><?php
                                                }elseif($row2["statoIdFk"]==3){ //da pianificare 
                                                    ?><img src="<?=$colorestato[3]?>" alt="icon"><?= $tipostato[3]?><?php
                                                }elseif($row2["statoIdFk"]==4){ //sospesa
                                                    ?><img src="<?=$colorestato[4]?>" alt="icon"><?= $tipostato[4]?><?php
                                                }elseif($row2["statoIdFk"]==5){ //conclusa  modifica 21/04/2017
                                                    ?><img src="<?=$colorestato[5]?>" alt="icon"><?= $tipostato[5]?><?php
                                                }
                                                ?>
                                            </td>
                                            <td valign="top"><?php
                                                if($row2["ispettIdFk"]==$ispettore){    //Aggiunto il 27/04/2017
                                                    //è un ispettore, quindi esperto
                                                    ?><img src="<?=$colore[1]?>" alt="icon"><?= $tiporuolo[1]?><?php
                                                }else{
                                                    ?><img src="<?=$colore[2]?>" alt="icon"><?= $tiporuolo[2]?><?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                        if(mysqli_num_rows($listaispezionirifiutate)>0){  ?>
                            <div class="row">
                                <div class="span8"><label>ISPEZIONI NON SVOLTE</label>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="span1">Anno</th>
                                                <th class="span4">Stabilimento</th>
                                                <th class="span4">Comune</th>
                                                <th class="span4">Provincia</th>
                                                <th class="span2">Regione</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            while($row3=$db->fetchassoc2($listaispezionirifiutate)){
                                                ?>
                                            <tr>
                                                <td valign="top"><?=$row3["anno"]?></td>
                                                <td valign="top">
                                                    <a href="aggiungi_stabilimento.php?id=<?=$row3["stabilimentoId"]?>" target=”_blank”><?=$row3["stabilimentoDenominazione"]?></a>
                                                </td>
                                                <td valign="top"><?=$row3["comuneNome"]?></td>
                                                <td valign="top"><?=$row3["prov"]?></td>
                                                <td valign="top"><?=$row3["nomeregione"]?></td>
                                            </tr>
                                            
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } } ?>
                    </form>
                </div>
            </div>
		</div>
	</section>
	
<?php include '../include/footer.php'; ?> 
</body>
</html>
