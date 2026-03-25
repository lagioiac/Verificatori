<?php
// Inizio PHP
include 'config.php';
include 'include/check_user.php';

// ricordarsi di chudere head derivante dal header.php
include 'include/header.php'; // Chiude il tag <head> aperto in header.php più avanti

include 'db/mysql.php';
include 'class/PersonaleUOTClass.php';
include 'class/QualificheClass.php';
include 'class/RuoloClass.php';

?>

</head> <!-- Chiusura sezione head presente dentro il file header.php -->

<body>
<div class="container">
    <h1 style="text-align: center; font-size: 2em; font-weight: bold;">Verifiche</h1>

    <!-- Inizio Modulo di Ricerca -->
    <form method="POST" action="verifiche.php">
        <!-- Dropdown Codice ATECO -->
        <select name="search_codice_ateco">
            <option value="">Seleziona un Codice ATECO</option>
            <?php
            $db = new DbConnect(); // nome del DB definito in config.php
            $db->open() or die($db->error());

            $query_ateco = "SELECT idAttivita, attivita FROM attivita ORDER BY idAttivita";
            $result_ateco = $db->query($query_ateco);
            while ($ateco = $db->fetchassoc($result_ateco)) {
                $selected = filter_input(INPUT_POST, 'search_codice_ateco', FILTER_SANITIZE_STRING) == $ateco['idAttivita'] ? 'selected' : '';
                echo "<option value='{$ateco['idAttivita']}' $selected>{$ateco['idAttivita']} - {$ateco['attivita']}</option>";
            }
            ?>
        </select>

        <!-- Dropdown Stato Verifica -->
        <select name="search_stato_verifica">
            <option value="">Seleziona Stato Verifica</option>
            <?php
            $query_stato = "SELECT IdStato, stato FROM statoverifica ORDER BY stato";
            $result_stato = $db->query($query_stato);
            while ($stato = $db->fetchassoc($result_stato)) {
                $selected = filter_input(INPUT_POST, 'search_stato_verifica', FILTER_SANITIZE_STRING) == $stato['IdStato'] ? 'selected' : '';
                echo "<option value='{$stato['IdStato']}' $selected>{$stato['stato']}</option>";
            }
            ?>
        </select>

        <!-- Dropdown UOT -->
        <select name="search_uot">
            <option value="">Seleziona UOT</option>
            <?php
            $query_uot = "SELECT IdUot, denominazione FROM uot ORDER BY denominazione";
            $result_uot = $db->query($query_uot);
            while ($uot = $db->fetchassoc($result_uot)) {
                $selected = filter_input(INPUT_POST, 'search_uot', FILTER_SANITIZE_STRING) == $uot['IdUot'] ? 'selected' : '';
                echo "<option value='{$uot['IdUot']}' $selected>{$uot['denominazione']}</option>";
            }
            ?>
        </select>

        <!-- Input Verificatore -->
        <input type="text" name="search_verificatore" placeholder="Cerca per Cognome" value="<?= filter_input(INPUT_POST, 'search_verificatore', FILTER_SANITIZE_STRING) ?>">

        <!-- Dropdown Regione -->
        <select name="search_regione">
            <option value="">Seleziona Regione</option>
            <?php
            $query_regione = "SELECT idRegione, regione FROM regione ORDER BY regione";
            $result_regione = $db->query($query_regione);
            while ($regione = $db->fetchassoc($result_regione)) {
                $selected = filter_input(INPUT_POST, 'search_regione', FILTER_SANITIZE_STRING) == $regione['idRegione'] ? 'selected' : '';
                echo "<option value='{$regione['idRegione']}' $selected>{$regione['regione']}</option>";
            }
            ?>
        </select>

        <button type="submit">Cerca</button>
    </form>
    <!-- Fine Modulo di Ricerca -->

    <!-- Inizio Codice per Visualizzazione Verifiche -->
    <?php  // Verifica se ci sono filtri applicati e costruisce la query di conseguenza
    
    /* vecchia query: funzionava senza la ricerca del cognome del verificatore ma con solo ID presente nella tabella verifiche
    $query = "SELECT verifiche.*, statoverifica.stato, statoverifica.iconastato FROM verifiche 
              LEFT JOIN statoverifica ON verifiche.idstatoverifica = statoverifica.IdStato
              WHERE 1"; */
    
    
    // query (nuova) con la ricerca del cognome del verificatore coinvolgendo anche la tabella "personaleuot"
    $query = "SELECT verifiche.*, statoverifica.stato, statoverifica.iconastato, personaleuot.cognome FROM verifiche 
                  LEFT JOIN statoverifica ON verifiche.idstatoverifica = statoverifica.IdStato
                  LEFT JOIN personaleuot ON verifiche.idverificatore = personaleuot.IDXF
                  WHERE 1";

    $filter_applied = false; // Flag per controllare se un filtro è stato applicato

    // Condizioni di filtro
    $search_codice_ateco = filter_input(INPUT_POST, 'search_codice_ateco', FILTER_SANITIZE_STRING);
    $search_stato_verifica = filter_input(INPUT_POST, 'search_stato_verifica', FILTER_SANITIZE_STRING);
    $search_uot = filter_input(INPUT_POST, 'search_uot', FILTER_SANITIZE_STRING);
    $search_verificatore = filter_input(INPUT_POST, 'search_verificatore', FILTER_SANITIZE_STRING);
    $search_regione = filter_input(INPUT_POST, 'search_regione', FILTER_SANITIZE_STRING);

    if (!empty($search_codice_ateco)) {
        /* echo "<pre>Filtro Codice ATECO applicato: $search_codice_ateco</pre>"; */ // debug
        $query .= " AND idattivita = '$search_codice_ateco'";
        $filter_applied = true;
    }
    if (!empty($search_stato_verifica)) {
        /* echo "<pre>Filtro Stato Verifica applicato: $search_stato_verifica</pre>"; */ // debug
        $query .= " AND statoverifica.IdStato = '$search_stato_verifica'";
        $filter_applied = true;
    }
    if (!empty($search_uot)) {
        /* echo "<pre>Filtro UOT applicato: $search_uot</pre>"; */ // debug
        $query .= " AND iduot = '$search_uot'";
        $filter_applied = true;
    }
    /* vecchio if per verificatore senza ricerca per cognome
    if (!empty($search_verificatore)) {
        $query .= " AND idverificatore LIKE '%$search_verificatore%'";
        $filter_applied = true;
    }*/
    // nuovo if per verificatore con ricerca per cognome
    if (!empty($search_verificatore)) {
        /* echo "<pre>Filtro Verificatore applicato: $search_verificatore</pre>"; */ // debug
        $query .= " AND personaleuot.cognome LIKE '%$search_verificatore%'";
        $filter_applied = true;
    }
    if (!empty($search_regione)) {
        /* echo "<pre>Filtro Regione applicato: $search_regione</pre>"; */ // debug
        $query .= " AND regione = '$search_regione'";
        $filter_applied = true;
    }

    $result = $db->query($query);

    // Ottenere il conteggio delle verifiche filtrate
    $query_count = "SELECT COUNT(*) as total FROM ($query) as subquery";
    $result_count = $db->query($query_count);
    $row_count = $db->fetchassoc($result_count);
    $totalVerifiche = $row_count['total'];
    
    // per far stampare zero quando il contatore non ha valore
    if ($totalVerifiche = " ") {
        $totalVerifiche = 0;
    }
    
    // Debugging output
    /* echo "<pre>Query: $query</pre>";
    echo "<pre>Totale verifiche: $totalVerifiche</pre>"; */
    ?>
    <!-- Fine Codice per Visualizzazione Verifiche -->

    <!-- Inizio Codice per Contatore Verifiche -->
     
     <h2 style="text-align: center; font-size: 1.5em; font-weight: bold;">
        <?php
            if ($filter_applied){
                echo "Totale verifiche da filtro applicato:". $totalVerifiche;
            } else {
                echo "Totale verifiche: ". $totalVerifiche;
            }
        ?>

    </h2>
  
    <!-- Fine Codice per Contatore Verifiche -->

    <!-- Tasto per aggiungere una nuova verifica -->
    <div class="add-new-verification">
        <a href="aggiungi_verifica.php" class="btn btn-primary">Aggiungi Nuova Verifica</a>
    </div>

    <!-- Inizio Tabella Verifiche -->
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
                <?php
                if ($totalVerifiche > 0) {
                    while ($row = $db->fetchassoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['idVerifica']}</td>";
                        echo "<td>{$row['idverificatore']}</td>";
                        echo "<td>{$row['idSito_Verifica']}</td>";
                        echo "<td>{$row['iduot']}</td>";
                        echo "<td>{$row['dataAssegnazioneVer']}</td>";
                        echo "<td>{$row['dataInizioVer']}</td>";
                        echo "<td>{$row['dataSospensioneVer']}</td>";
                        echo "<td>{$row['dataFineVer']}</td>";
                        echo "<td>{$row['idtipoverifiche']}</td>";
                        echo "<td><img src='{$row['iconastato']}' alt='{$row['stato']}'>{$row['stato']}</td>";
                        echo "</tr>";
                    }
                } else {
                    // Mostra un messaggio se non ci sono dati da visualizzare
                    echo "<tr><td colspan='10'>Nessuna verifica trovata.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Fine Tabella Verifiche -->
</div>
    
<?php include 'include/footer.php';?>

</body>
</html>