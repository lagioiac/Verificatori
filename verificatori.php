<?php
    // Inizio PHP
    include 'config.php';
    include 'include/check_user.php';

    ob_start();
    $pageMenu="verificatori";

    // Visualizzazione degli errori
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // ricordarsi di chudere head derivante dal header.php
    include 'include/header.php'; // Chiude il tag <head> aperto in header.php più avanti

    include 'db/mysql.php';
    require 'class/PersonaleUOTClass.php';
    require 'class/QualificheClass.php';
    require 'class/RuoloClass.php';

    $pageName = $current_page; // per tenere traccia della navigazione - $current_page viene settata nel header.php

    $db = new DbConnect(); // nome del DB definito in config.php
    $db->open() or die($db->error());

    $PersonaleUOTClass = new PersonaleUOTClass();
    $QualificheClass = new QualificheClass();
    $ruoloClass = new RuoloClass();

    //modifica nei controlli cerca e nell'ordine alfabetico
    $searchPersonaleUOT = null;
    $searchuot = null;
    $searchregione = null;
    $searchruolo = null;

    if(!isset($_GET['ordinaPer'])) {
        $_GET['ordinaPer'] = 'uot.denominazione';
    }

    $ordinaPer = $_GET['ordinaPer'];
    $filter_applied = false; // Flag per controllare se un filtro è stato applicato
    
    if(isset($_POST["searchPersonaleUOT"]) || isset($_POST["searchuot"]) || isset($_POST["searchregione"]) || isset($_POST["searchruolo"])) {
        /* echo "DEBUG POST searchPersonaleUOT: " . (isset($_POST["searchPersonaleUOT"]) ? $_POST["searchPersonaleUOT"] : "NULL") . "<br>";
        echo "DEBUG POST searchuot: " . (isset($_POST["searchuot"]) ? $_POST["searchuot"] : "NULL") . "<br>";
        echo "DEBUG POST searchregione: " . (isset($_POST["searchregione"]) ? $_POST["searchregione"] : "NULL") . "<br>";
        echo "DEBUG POST searchruolo: " . (isset($_POST["searchruolo"]) ? $_POST["searchruolo"] : "NULL") . "<br>"; */
        $PersonaleUOT = $PersonaleUOTClass->getSearchPersonaleUOT($db, $_POST, $_GET['ordinaPer']);
        $searchPersonaleUOT = isset($_POST["searchPersonaleUOT"]) ? $_POST["searchPersonaleUOT"] : null;
        $searchuot = isset($_POST["searchuot"]) ? $_POST["searchuot"] : null;
        $searchregione = isset($_POST["searchregione"]) ? $_POST["searchregione"] : null;
        $searchruolo = isset($_POST["searchruolo"]) ? $_POST["searchruolo"] : null;
    } else {
        $PersonaleUOT = $PersonaleUOTClass->getListaPersonaleUOT($db, $_GET['ordinaPer']);
    }
    
    if ($searchPersonaleUOT || $searchuot || $searchregione || $searchruolo) 
        $filter_applied = true;

    $qualifica = $QualificheClass->getQualifiche($db);

    $colore = array();
    $tiporuolo = array();
    $IdRuolo = array();
    $elencoruoli = $ruoloClass->getRuoli($db);
    $i = 0;
    while ($r = $db->fetchassoc2($elencoruoli)) {
        $IdRuolo[$i] = $r["IdRuolo"];
        $colore[$i] = $r["iconaruolo"];
        $tiporuolo[$i] = $r["ruolo"];
        $i++;
    }
    
?>

<script type="text/javascript">
    document.getElementById("myButton").onclick = function () {
        location.href = "index.php?clean";
    };
    function cancella(id) {
        if (confirm("Sicuro di voler cancellare?"))
            $(location).attr('href',"deleteFormazione.php?&id="+id+"");
    }		

    <!-- *** SCRIPT per messaggio di cortesia prima di esportare la tabella in CSV *** -->
    function confermaEsportazione() {
        var conferma = confirm("Se si procede, verrà creato un file CSV da poter aprire con EXCEL. Procedere?");
        if (conferma) {
            window.location.href = 'csv_verificatori.php';
        }
    }
    function mostraMessaggioEsito(esito, messaggio) {
        alert(messaggio);
        if (esito === 'successo') {
            window.location.href = 'Download/Export_Verificatori.csv';
        }
    }
    
    function resetFilters() {
        window.location.href = 'verificatori.php';
    }
    
</script> 

</head> <!-- chiusura sezione head presente dentro il file header.php -->

<body>
    <section id="page" class="verificatore">
        <div class="container">
            <div class="header">
                
                <div class="row spacer"></div> <!-- Riga vuota sopra - aldo -->
                
                <!-- <a href="home.php" class="back">Indietro</a> -->
                <br>
                <form method="POST" action="verificatori.php?ordinaPer=<?=$ordinaPer?>">
                    <aside>
                        <div class="centered-wrapper">
                            
                            <div class="button-container" style="margin-bottom: 10px;">
                                                                
                                <!-- Tasto REPORT storico (lasciato invariato per confronto) -->
                                <a href="report_verificatori.php" class="button"> REPORT </a>
                                
                                <!-- Nuovo report con metriche separate persone/competenze -->
                                <a href="report_verificatori_nuovo.php" class="button"> REPORT NUOVO </a>
                                
                                <!-- Tasto "Aggiungi o modifica Verificatore -->
                                <a href="aggiungi_verificatore.php" class="button add"> Aggiungi o Modifica Verificatore </a>

                                <!-- nuova chiamata all'esportazione in Excel con il tasto "esporta Excel" della tabella -->
                                <a href="javascript:void(0);" class="button" onclick="confermaEsportazione()"> Esporta per Excel (CSV) </a>

                                <div class="row spacer"></div> <!-- Riga vuota sopra - aldo -->
                                
                                <div class="filters">         
                                    <span>Filtra tabella per:</span>
                                    
                                    <input name="searchPersonaleUOT" type="text" placeholder="Cognome da cercare" value="<?= $searchPersonaleUOT ?>">

                                    <select id="searchuot" name="searchuot">
                                        <option value="">-- Seleziona una UOT --</option>
                                        <?php
                                        $query_uot = "SELECT IdUot, denominazione FROM uot ORDER BY denominazione";
                                        $result_uot = $db->query($query_uot);
                                        if (!$result_uot) {
                                            die("Errore nella query UOT: " . $db->error());
                                        }
                                        while ($row_uot = $db->fetchassoc($result_uot)) {
                                            $selected = isset($_POST['$searchuot']) && $_POST['$searchuot'] == $row_uot['IdUot'] ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($row_uot['IdUot']) . "' $selected>" . htmlspecialchars($row_uot['denominazione']) . "</option>";
                                        } ?>
                                    </select>

                                    <select id="searchregione" name="searchregione">
                                        <option value="">-- Seleziona una Regione --</option>
                                        <?php 
                                        $query_regione = "SELECT idRegione, regione FROM regione ORDER BY regione";
                                        $result_regione = $db->query($query_regione);
                                        if (!$result_regione) {
                                                die("Errore nella query Regione: " . $db->error());
                                        }
                                        while ($row_regione = $db->fetchassoc($result_regione)) { 
                                            $selected = isset($_POST['$searchregione']) && $_POST['$searchregione'] == $row_regione['idRegione'] ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($row_regione['idRegione']) . "' $selected>" . htmlspecialchars($row_regione['regione']) . "</option>";
                                        } ?>
                                    </select>

                                    <select id="searchruolo" name="searchruolo">   
                                        <option value="">-- Seleziona per Competenze --</option>
                                        <?php 
                                        $query_ruolo = "SELECT IdRuolo, ruolo FROM ruolo ORDER BY ruolo";
                                        $result_ruolo = $db->query($query_ruolo);
                                        if (!$result_ruolo) {
                                            die("Errore nella query Ruolo: " . $db->error());
                                        }
                                        while ($row_ruolo = $db->fetchassoc($result_ruolo)) {
                                            $selected = isset($_POST['$searchruolo']) && $_POST['$searchruolo'] == $row_ruolo['IdRuolo'] ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($row_ruolo['IdRuolo']) . "' $selected>" . htmlspecialchars($row_ruolo['ruolo']) . "</option>";
                                        } ?>
                                    </select>

                                    <!-- vecchio submit  
                                    <input type="submit" style="position: absolute; left: -9999px"/>  -->
                                    
                                    <button type="submit" class="center-button">Cerca</button>
                                    
                                    <!-- Tasto per cercare tramite i filtri appliccati
                                    <div class="button-container" style="allign">
                                        <button type="submit"> Cerca </button>  
                                    </div> -->
                                    
                                    <div class="row spacer"></div> <!-- Riga vuota sopra - aldo -->
                                </div>  
                                <?php if ($filter_applied) {?>
                                    <div> <span>Filtro Applicato</span> </div>
                                    <button type="button" onclick="resetFilters()">Elimina Filtri</button>
                                <?php } ?>
                                
                                    
                            </div>    
                        </div> 
                    </aside>
                </form>
            </div>

            <?php if(isset($_GET["succes"])) { ?> 
                <div class="row addnew">
                    <div class="span12">
                        <div class="alert alert-success">Modifica avvenuta con successo!</div>
                    </div> 
                </div>
            <?php } ?>

            <div class="row">
                <div class="span12">
                    <table>
                        <thead>
                                <tr>
                                    <th class="span5"><a href="verificatori.php?ordinaPer=personaleuot.cognome"><img src="img/icon_sort_asc.png" alt="icon"></a> Cognome</th>
                                    <th class="span5"> Nome</th>
                                    <th class="span5"><a href="verificatori.php?ordinaPer=uot.denominazione"><img src="img/icon_sort_asc.png" alt="icon"></a> UOT</th>
                                    <th class="span5"> Qualifica</th>
                                    <th class="span5"> Competenze</th>
                                </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($PersonaleUOT) > 0) {
                                // Resetta il puntatore del risultato della query
                                mysqli_data_seek($PersonaleUOT, 0);

                                while($row = $db->fetchassoc2($PersonaleUOT)) { ?>
                                    <tr>
                                        <td valign="top">
                                            <a href="aggiungi_verificatore.php?id=<?=$row["IDXF"]?>">
                                                <?=$row["cognome"]?>
                                            </a>
                                        </td>
                                        <td valign="top"><?=$row["nome"]?></td>
                                        <td valign="top">
                                            <!-- UOT DIVENTA UN LINK ATTIVO -->
                                            <form method="POST" action="personale_uot.php" style="display:inline;">
                                                <input type="hidden" name="uot_select" value="<?= $row['iduot'] ?>">
                                                <input type="hidden" name="pageName" value="verificatori.php">
                                                <button type="submit" style="background:none;border:none;padding:0;color:inherit;cursor:pointer;">
                                                    <a> <?= $row["denominazione"] ?> </a>
                                                </button>
                                            </form>
                                        </td>
                                        <td valign="top">
                                            <?php if($row["idqualifica"] > 0) {
                                                $comp = $QualificheClass->getQualificaById($db, $row["idqualifica"]);
                                                $row2 = $db->fetchassoc2($comp);
                                                echo $row2["qualifica"];
                                            } ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($row["idruolo"] == $IdRuolo[0]) { ?>
                                                <img src="<?=$colore[0]?>" alt="icon"><?= $tiporuolo[0]?> 
                                            <?php } elseif ($row["idruolo"] == $IdRuolo[1]) { ?>
                                                <img src="<?=$colore[1]?>" alt="icon"><?= $tiporuolo[1]?> 
                                            <?php } elseif ($row["idruolo"] == $IdRuolo[2]) { ?>
                                                <img src="<?=$colore[2]?>" alt="icon"><?= $tiporuolo[2]?> 
                                            <?php } elseif ($row["idruolo"] == $IdRuolo[3]) { ?>
                                                <img src="<?=$colore[3]?>" alt="icon"><?= $tiporuolo[3]?> 
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="6">Nessun verificatore trovato</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    
<?php include 'include/footer.php';?>

</body>
</html>