<?php // Inizio PHP
    include 'config.php';
    include 'db/mysql.php';
    include 'class/PersonaleUOTClass.php';
    include 'class/QualificheClass.php';
    include 'class/RuoloClass.php';
    include 'include/check_user.php';

    // ricordarsi di chudere head derivante dal header.php
    include 'include/header.php'; 

    ob_start();
    $pageMenu="verifiche";     
    
    $pageName=$current_page; // per tenere traccia della navigazione - $current_page viene settata nel header.php  

    $db = new DbConnect(); // nome del DB definito in config.php
    $db->open() or die($db->error());
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifiche</title>
    <link rel="stylesheet" href="css/style.css">
</head>

</head> <!-- chiusura sezione head presente dentro il file header.php -->

<body>
    <div class="container">
        <h1 style="text-align: center; font-size: 2em; font-weight: bold;">Verifiche</h1>
        
        <!-- *** Inizio Modulo di Ricerca *** -->
        <form method="POST" action="verifiche.php">
            <input type="text" name="search_codice_ateco" placeholder="Cerca per codice ATECO">
            <input type="text" name="search_stato_verifica" placeholder="Cerca per stato della verifica">
            <input type="text" name="search_uot" placeholder="Cerca per UOT">
            <input type="text" name="search_verificatore" placeholder="Cerca per verificatore">
            <input type="text" name="search_regione" placeholder="Cerca per regione">
            <button type="submit">Cerca</button>
        </form>
        <!-- *** Fine Modulo di Ricerca *** -->

        <!-- *** Inizio Codice per Visualizzazione Verifiche *** -->
        <?php
        // Verifica se ci sono filtri applicati e costruisce la query di conseguenza
        $query = "SELECT verifiche.*, statoverifica.stato, statoverifica.iconastato FROM verifiche 
                  LEFT JOIN statoverifica ON verifiche.idstatoverifica = statoverifica.IdStato
                  WHERE 1";

        if (isset($_POST['search_codice_ateco']) && $_POST['search_codice_ateco'] != '') {
            $query .= " AND idattivita = '" . $db->mysqli_real_escape_string($_POST['search_codice_ateco']) . "'";
        }
        if (isset($_POST['search_stato_verifica']) && $_POST['search_stato_verifica'] != '') {
            $query .= " AND statoverifica.stato LIKE '%" . $db->mysqli_real_escape_string($_POST['search_stato_verifica']) . "%'";
        }
        if (isset($_POST['search_uot']) && $_POST['search_uot'] != '') {
            $query .= " AND iduot = '" . $db->mysqli_real_escape_string($_POST['search_uot']) . "'";
        }
        if (isset($_POST['search_verificatore']) && $_POST['search_verificatore'] != '') {
            $query .= " AND idverificatore = '" . $db->mysqli_real_escape_string($_POST['search_verificatore']) . "'";
        }
        if (isset($_POST['search_regione']) && $_POST['search_regione'] != '') {
            $query .= " AND regione LIKE '%" . $db->mysqli_real_escape_string($_POST['search_regione']) . "%'";
        }

        $result = $db->query($query);

        // Ottenere il conteggio delle verifiche filtrate
        $query_count = "SELECT COUNT(*) as total FROM ($query) as subquery";
        $result_count = $db->query($query_count);
        $row_count = $db->fetchassoc($result_count);
        $totalVerifiche = $row_count['total'];
        ?>
        <!-- *** Fine Codice per Visualizzazione Verifiche *** -->

        <!-- *** Inizio Codice per Contatore Verifiche *** -->
        <h2 style="text-align: center; font-size: 1.5em; font-weight: bold;">Totale Verifiche: <?= $totalVerifiche ?></h2>
        <!-- *** Fine Codice per Contatore Verifiche *** -->

        <!-- Tasto per aggiungere una nuova verifica -->
        <div class="add-new-verification">
            <a href="aggiungi_verifica.php" class="btn btn-primary">Aggiungi Nuova Verifica</a>
        </div>
        
        
        <!-- *** Inizio Tabella Verifiche *** -->
        <div class="table-container">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>ID Verifica</th>
                        <th>Verificatore</th>
                        <th>Sito Verifica</th>
                        <th>UOT</th>
                        <th>Data Assegnazione</th>
                        <th>Data Inizio</th>
                        <th>Data Sospensione</th>
                        <th>Data Fine</th>
                        <th>Tipo Verifica</th>
                        <th>Stato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $db->fetchassoc($result)) { ?>
                        <tr>
                            <td><?= $row['idVerifica'] ?></td>
                            <td><?= $row['idverificatore'] ?></td>
                            <td><?= $row['idSito_Verifica'] ?></td>
                            <td><?= $row['iduot'] ?></td>
                            <td><?= $row['dataAssegnazioneVer'] ?></td>
                            <td><?= $row['dataInizioVer'] ?></td>
                            <td><?= $row['dataSospensioneVer'] ?></td>
                            <td><?= $row['dataFineVer'] ?></td>
                            <td><?= $row['idtipoverifiche'] ?></td>
                            <td><img src="<?= $row['iconastato'] ?>" alt="<?= $row['stato'] ?>"><?= $row['stato'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- *** Fine Tabella Verifiche *** -->

    </div>
</body>
</html>