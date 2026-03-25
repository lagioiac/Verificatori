<?php
    include 'config.php';
    include 'include/check_user.php';
    
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

?>
</head> <!-- chiusura del head presente dentro il file header.php lasciato aperto volutamente -->

<body>
    <div class="container">
        <h1 style="text-align: center; font-size: 2em; font-weight: bold;">Personale della</h1>
        
        <?php
        // *** INIZIO MODIFICA ***
        $db = new DbConnect(); // Nome del DB definito in config.php
        $db->open() or die($db->error());

        // Ottieni l'ID della UOT corrente dal parametro POST
        $current_uot_id = isset($_POST['uot_select']) ? intval($_POST['uot_select']) : 0;
        
        // Ottieni il nome della pagina di provenienza
        $pageName = isset($_POST['pageName']) ? htmlspecialchars($_POST['pageName']) : 'index.php';

        // Recupera il nome della UOT
        $query_uot = "SELECT denominazione FROM uot WHERE IdUot = $current_uot_id";
        $result_uot = $db->query($query_uot);
        $row_uot = $db->fetchassoc($result_uot);
        $uot_name = $row_uot ? htmlspecialchars($row_uot['denominazione']) : 'UOT Non Trovata';

        // Ottenere il personale associato alla UOT corrente con la qualifica
        $query_personale = "
            SELECT personaleuot.*, qualifica.qualifica 
            FROM personaleuot 
            LEFT JOIN qualifica ON personaleuot.idqualifica = qualifica.idQualifica 
            WHERE personaleuot.iduot = $current_uot_id";
        $result_personale = $db->query($query_personale);
        // *** FINE MODIFICA ***
        ?>

        <!-- Visualizza il nome della UOT selezionata -->
        <h2 style="text-align: center; font-size: 1.5em; font-weight: bold;"><?= $uot_name ?></h2>
        
        <!-- Tasto per tornare indietro -->
        <a href="<?= $pageName ?>" class="back">Indietro</a>

        <!-- Tabella del personale UOT -->
        <div class="table-container">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Qualifica</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($db->numrows($result_personale) > 0): ?>
                        <?php while ($row = $db->fetchassoc($result_personale)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome']) ?></td>
                                <td><?= htmlspecialchars($row['cognome']) ?></td>
                                <td><?= htmlspecialchars($row['qualifica']) ?></td> <!-- *** INIZIO MODIFICA *** -->
                                <td><?= htmlspecialchars($row['cell']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['note']) ?></td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Nessun personale trovato per questa UOT.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tasto per tornare indietro -->
        <div class="back-button">
            <a href="<?= $pageName ?>" class="back">Indietro</a>
        </div>
    </div>
    
<?php include 'include/footer.php';?>

</body>
</html>

