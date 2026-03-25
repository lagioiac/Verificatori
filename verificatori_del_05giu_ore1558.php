<?php // Inizio PHP
	ob_start();
	$pageMenu="verificatori";

	require 'config.php';
	require 'db/mysql.php';
	require 'class/PersonaleUOTClass.php';
	require 'class/QualificheClass.php';
	require 'class/RuoloClass.php';
	include("include/check_user.php");

	// ricordarsi di chudere head derivante dal header.php
	include 'include/header.php'; 

	/* nel vecchio codice $pageName era valorizzata con il nome del file, ovvero della pagina che veniva visualizzata */
	$pageName=$current_page; // per tenere traccia della navigazione - $current_page viene settata nel header.php  

	$PersonaleUOTCognome="";

	$db= new DbConnect(); // nome del DB definito in config.php
        
	$PersonaleUOTClass= new PersonaleUOTClass();
	$QualificheClass = new QualificheClass();
	$ruoloClass = new RuoloClass();

	$db->open() or die($db->error());
        
        if ($db->connect_error) // connessione fallita al DB - controllo già fatto in mysql.php - verificare se togliere
            die("Connection failed: " . $db->connect_error);

	//modifica nei controlli cerca e nell'ordine alfabetico
	$searchPersonaleUOT=null;
	$searchuot=null;
	$searchregione=null;

	if(!isset($_GET['ordinaPer'])) 
            $_GET['ordinaPer'] = 'uot.Denominazione';

	$ordinaPer=$_GET['ordinaPer'];
	if(isset($_POST["searchPersonaleUOT"]) || isset($_POST["searchuot"]) || isset($_POST["searchregione"]))
	{
            // Esegui la query per ottenere tutti i record dalla tabella "personaleuot"
            $PersonaleUOT=$PersonaleUOTClass->getSearchPersonaleUOT($db, $_POST,$_GET['ordinaPer']);
            if(isset($_POST["searchPersonaleUOT"]) != "")
                    $searchPersonaleUOT=$_POST["searchPersonaleUOT"];
            else
                    $searchPersonaleUOT=null;

            if(isset($_POST["searchuot"]) != "")
                    $searchuot=$_POST["searchuot"];
            else
                    $searchuot=null;

            if(isset($_POST["searchregione"]) != "")
                    $searchregione=$_POST["searchregione"];
            else
                    $searchregione=null;

        }else
            // Esegui la query per ottenere tutti i record dalla tabella "personaleuot"
            $PersonaleUOT=$PersonaleUOTClass->getListaPersonaleUOT($db,$_GET['ordinaPer']);

        
        /* pezzo di codice da veificare */
	$qualifica=$QualificheClass->getQualifiche($db); 
        
 
        $colore = array();
	$tiporuolo = array();
	$IdRuolo=array();
	$elencoruoli = $ruoloClass->getRuoli($db);
	$i=0;
	while ($r = $db->fetchassoc2($elencoruoli))
	{
            $IdRuolo[$i] = $r["IdRuolo"];
            $colore[$i] = $r["iconaruolo"];
            $tiporuolo[$i] = $r["ruolo"];
            $i++;
	} 
        
                
        /* vecchio codice per il conteggio del personale
        if(mysqli_num_rows($PersonaleUOT))
            $nPersonaleUOT=mysqli_num_rows($PersonaleUOT);
        else
            $nPersonaleUOT=0; */
        
        // Inizializzazione delle variabili di conteggio
        $nPersonaleUOT = 0;
        $nVerificatoriAttivi = 0;
        $nVerificatoriStandby = 0;
        
        echo "Prima del IF" . "<br>";
        echo "N PersonaleUOT " . $nPersonaleUOT . "<br>";
        echo "N Verificatori Attivi " . $nVerificatoriAttivi . "<br>";
        echo "N Verificatori Standby " . $nVerificatoriStandby . "<br>";
        echo "N Personale rimanente " . ($nPersonaleUOT-$nVerificatoriAttivi-$nVerificatoriStandby) . "<br>";
        echo "<br>";
        
        if (mysqli_num_rows($PersonaleUOT)) 
        {
            $nPersonaleUOT = mysqli_num_rows($PersonaleUOT);

            echo "Dentro IF" . "<br>";
            echo "N PersonaleUOT " . $nPersonaleUOT . "<br>";
                       
            // Conta i Verificatori attivi e quelli in standby
            $i=0;
            while ($i < $nPersonaleUOT) // vecchia condizione provata (no in RISPE) $row = $db->fetch_assoc($PersonaleUOT)
            {
                echo "row_idruolo: " . $row["idruolo"] . "<br>";
                if ($row["idruolo"] == 1 || $row["idruolo"] == 2) 
                    $nVerificatoriAttivi++;
                elseif ($row["idruolo"] == 3) 
                    $nVerificatoriStandby++;
                $i++;
            }
            
            echo "N Verificatori Attivi " . $nVerificatoriAttivi . "<br>";
            echo "N Verificatori Standby " . $nVerificatoriStandby . "<br>";
            echo "N Personale rimanente " . ($nPersonaleUOT-$nVerificatoriAttivi-$nVerificatoriStandby) . "<br>";
            echo "<br>"; 
        } 
        else 
            $nPersonaleUOT = 0;
        
        
        /*$nVerificatoriAttivi=0;
        $nVerificatoriStandby=0;
        $i=0;
	while ($i = $nPersonaleUOT)
	{
            if($row["idqualifica"]>0
            $IdRuolo[$i] = $r["IdRuolo"];
            $colore[$i] = $r["iconaruolo"];
            $tiporuolo[$i] = $r["ruolo"];
            $i++;
	}*/ 
 

        /* pezzo di codice spostato qualche riga su 
	$qualifica=$QualificheClass->getQualifiche($db); 
        
 
        $colore = array();
	$tiporuolo = array();
	$IdRuolo=array();
	$elencoruoli = $ruoloClass->getRuoli($db);
	$i=0;
	while ($r = $db->fetchassoc2($elencoruoli))
	{
            $IdRuolo[$i] = $r["IdRuolo"];
            $colore[$i] = $r["iconaruolo"];
            $tiporuolo[$i] = $r["ruolo"];
            $i++;
	} */
                
?> <!--  Fine PHP -->

<script type="text/javascript">
    document.getElementById("myButton").onclick = function () 
    {
        location.href = "index.php?clean";
    };
    function cancella(id)
    {
        if(confirm("Sicuro di voler cancellare?"))
            $(location).attr('href',"deleteFormazione.php?&id="+id+"");
    }		
</script>

</head> <!-- chiusura sezione head presente dentro il file header.php -->

<body>
    <section id="page" class="verificatore">
        <div class="container ">
            <div class="header">
                <!--<style>
                .info 
                {
                  margin: 0;
                  padding: 0;
                }
                </style>-->
                <div><strong>Personale UOT:</strong> <?=$nPersonaleUOT ?></div>
                <div><strong>Verificatori ATTIVI nelle UOT:</strong> <?=$nVerificatoriAttivi ?></div>
                <div><strong>Verificatori in STANDBY nelle UOT:</strong> <?=$nVerificatoriStandby ?></div><br>
                
                <form method="POST" action="verificatori.php?ordinaPer=<?=$ordinaPer?>">
                    <aside>
                        <div>
                            <input name="searchverificatori" type="text" placeholder="Cerca verificatore" value="<?=$searchverificatore?>" style="display: inline;">
                            <input name="searchuot" type="text" placeholder="Cerca UOT" value="<?=$searchuot?>" style="display: inline;">
                            <input name="searchregione" type="text" placeholder="Cerca Regione" value="<?=$searchregione?>" style="display: inline;">
                            <input type="submit" style="position: absolute; left: -9999px"/>
                        </div>
                        <div>
                            <a href="aggiungi_verificatore.php" class="button add">Aggiungi Verificatore</a>  <!-- ***ALDO*** da controlla il php -->
                            <a href="uot.php" class="button">UOT</a>  <!-- ***ALDO*** da controlla il php -->
                            <a href="csv_verificatori.php" class="button">Esporta Excel</a>  <!-- ***ALDO*** da controlla il php -->
                            <a href="csv_verificatori_note.php" class="button">Esporta Note</a>  <!-- ***ALDO*** da controlla il php -->
                            <a href="verificatori_out.php" class="button">Non attivi</a>  <!-- ***ALDO*** da controlla il php -->
                            <a href="home.php" class="back">Indietro</a>  <!-- ***ALDO*** da controlla il php -->
                        </div>
                    </aside>
                </form>
            </div>

            <?php 
            if(isset($_GET["succes"])) 
            { ?> 
                <div class="row addnew">
                    <div class="span12">
                        <div class="alert alert-success">Modifica avvenuta con successo!</div>
                    </div> 
                </div>
            <?php 
            } ?>

            <div class="row">
                <div class="span12">
                    <table>
                        <thead>
                                <tr>
                                    <th class="span5"><a href="verificatori.php?ordinaPer=personaleuot.cognome"><img src="img/icon_sort_asc.png" alt="icon"></a> Cognome</th>
                                    <th class="span5"> Nome</th>
                                    <th class="span5"><a href="verificatori.php?ordinaPer=uot.denominazione"><img src="img/icon_sort_asc.png" alt="icon"></a> UOT</th>
                                    <th class="span5"> Qualifica</th>
                                    <th class="span5"> Ruolo</th>
                                </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(mysqli_num_rows($PersonaleUOT)>0)
                            {
                                while($row=$db->fetchassoc2($PersonaleUOT))
                                { ?>
                                    
                            
                            
                                    <?php /* ** inizio nuovo codice 
                                    echo "IDXF: " . $row["IDXF"] . "<br>";
                                    echo "cognome: " . $row["cognome"] . "<br>";
                                    echo "nome: " . $row["nome"] . "<br>";
                                    echo "iduot: " . $row["iduot"] . "<br>";
                                    echo "denominazione: " . $row["denominazione"] . "<br>";
                                    
                                    echo "idqualifica: " . $row["idqualifica"] . "<br>";
                                    echo "idruolo: " . $row["idruolo"] . "<br>";
                                                                        
                                    echo "compIdFk: " . $row["compIdFk"] . "<br>";
                                    
                                    /* ** fine nuovo codice ** */ ?> 
                            
                                    
                                    
                                    <tr>
                                        <td valign="top">
                                            <a href="aggiungi_verificatore.php?id=<?=$row["IDXF"]?>">
                                                    <?=$row["cognome"]?>
                                            </a>
                                        </td>
                                        <td valign="top"><?=$row["nome"]?></td>
                                        <td valign="top">
                                            <!-- UOT DIVENTA UN LINK ATTIVO -->
                                            <a href="aggiungi_uot.php?id=<?=$row["iduot"]?>" target=”_blank”>
                                                <?=$row["denominazione"]?>
                                            </a>
                                        </td>
                                        <td valign="top">
                                            <?php if($row["idqualifica"]>0) // in RISPE la condizione era con "compIdFk" adesso con "idqualifica"
                                            {
                                                $comp = $QualificheClass->getQualificaById($db, $row["idqualifica"]);
                                                $row2 = $db->fetchassoc2($comp);?>
                                                <?=$row2["qualifica"]?>
                                            <?php
                                            }
                                            else
                                            {?>
                                                <?=""?>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            
                                            
                                            
                                            /* ** inizio nuovo codice ** */
                                            /*echo "row[idruolo]: " . $row["idruolo"] . "<br>";*/

                                            if ($row["idruolo"] == $IdRuolo[0]) {
                                                /*echo "ZEROrow[IdRuoloFk]: " . $row["IdRuoloFk"] . "<br>";
                                                echo "IdRuolo[0]: " . $IdRuolo[0] . "<br>";
                                                echo "colore[0]: " . $colore[0] . "<br>"; 
                                                echo "tiporuolo[0]: " . $tiporuolo[0] . "<br>";*/ ?>
                                                <img src="<?=$colore[0]?>" alt="icon"><?= $tiporuolo[0]?> 
                                            <?php } elseif ($row["idruolo"] == $IdRuolo[1]) {
                                                /*echo "UNOrow[IdRuoloFk]: " . $row["IdRuoloFk"] . "<br>";
                                                echo "IdRuolo[1]: " . $IdRuolo[1] . "<br>";
                                                echo "colore[1]: " . $colore[1] . "<br>";
                                                echo "tiporuolo[1]: " . $tiporuolo[1] . "<br>";*/ ?>
                                                <img src="<?=$colore[1]?>" alt="icon"><?= $tiporuolo[1]?> 
                                            <?php } elseif ($row["idruolo"] == $IdRuolo[2]) {
                                                /*echo "DUErow[IdRuoloFk]: " . $row["IdRuoloFk"] . "<br>";
                                                echo "IdRuolo[2]: " . $IdRuolo[2] . "<br>";
                                                echo "colore[2]: " . $colore[2] . "<br>";
                                                echo "tiporuolo[2]: " . $tiporuolo[2] . "<br>";*/ ?>
                                                <img src="<?=$colore[2]?>" alt="icon"><?= $tiporuolo[2]?> 
                                            <?php } elseif ($row["idruolo"] == $IdRuolo[3]) {
                                                /*echo "TRErow[IdRuoloFk]: " . $row["IdRuoloFk"] . "<br>";
                                                echo "IdRuolo[3]: " . $IdRuolo[3] . "<br>";
                                                echo "colore[3]: " . $colore[3] . "<br>";
                                                echo "tiporuolo[3]: " . $tiporuolo[3] . "<br>";*/ ?>
                                                <img src="<?=$colore[3]?>" alt="icon"><?= $tiporuolo[3]?> 
                                            <?php } ?>
                                            <?php /* ** fine nuovo codice ** */ ?>
                                            
                                            
                                            
                                            
                                            <!-- codice originale tolto e messo nel file IfElseIf.txt -->
                                                                                                                                       
                                        </td>
                                    </tr>
                                <?php 
                                } 
                            } 
                            else
                            {?>
                                <tr>
                                    <td colspan="8">Nessun nominativo presente nel Personale delle UOT</td>
                                </tr>
                            <?php 
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
     <!--   </div> // chiusura container - da verificare se  è corretta la posiszione perchè su RISPE mancava la chiusura    -->
    </section>

<?php include '../include/footer.php';?>
    
</body>
</html> <!-- verifcare dove è stato aperto... probabilmente in un qualche include -->