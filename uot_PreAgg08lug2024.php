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

</head> <!-- chiusura del head presente in header.php lasciato aperto volutamente -->


<body>
    <div class="container">
        <h1>Gestione UOT</h1>

        <form method="POST" action="uot.php">
            <label for="uot_select">Seleziona una UOT:</label>
            <select id="uot_select" name="uot_select">
                <option value="">-- Seleziona una UOT --</option>
                <?php
                $db = new DbConnect();
                $db->open() or die($db->error());
                $query = "SELECT IdUot, denominazione FROM uot ORDER BY denominazione";
                $result = $db->query($query);

                while ($row = $db->fetchassoc($result)) {
                    $selected = isset($_POST['uot_select']) && $_POST['uot_select'] == $row['IdUot'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['IdUot']) . "' $selected>" . htmlspecialchars($row['denominazione']) . "</option>";
                }
                ?>
            </select>
            <button type="submit">Cerca</button>
        </form>

        <?php
        if (isset($_POST['uot_select']) && !empty($_POST['uot_select'])) {
            $uot_id = filter_input(INPUT_POST, 'uot_select', FILTER_SANITIZE_NUMBER_INT);
            $query = "SELECT * FROM uot WHERE IdUot = '" . $db->mysqli_real_escape($uot_id) . "'";
            $result = $db->query($query);
            $row = $db->fetchassoc($result);

            if ($row) {
                echo "<div class='uot-details'>";
                echo "<h2>Dettagli UOT</h2>";
                echo "<p><strong>Denominazione:</strong> " . htmlspecialchars($row['denominazione']) . "</p>";
                echo "<p><strong>Indirizzo:</strong> " . htmlspecialchars($row['indirizzo']) . "</p>";
                echo "<p><strong>Telefono:</strong> " . htmlspecialchars($row['telefono']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                echo "<p><strong>PEC:</strong> " . htmlspecialchars($row['PEC']) . "</p>";
                echo "<p><strong>Note:</strong> " . htmlspecialchars($row['note']) . "</p>";

                echo "<div class='navigation-buttons'>";
                echo "<form method='POST' action='uot.php' style='display:inline;'>";
                echo "<input type='hidden' name='uot_select' value='" . getPreviousUOTId($row['IdUot']) . "'>";
                echo "<button type='submit'>&laquo; Precedente</button>";
                echo "</form>";

                echo "<form method='POST' action='personale_uot.php' style='display:inline;'>";
                echo "<input type='hidden' name='uot_select' value='" . htmlspecialchars($row['IdUot']) . "'>";
                echo "<input type='hidden' name='pageName' value='uot.php'>";
                echo "<button type='submit'>Visualizza Personale</button>";
                echo "</form>";

                echo "<form method='POST' action='uot.php' style='display:inline;'>";
                echo "<input type='hidden' name='uot_select' value='" . getNextUOTId($row['IdUot']) . "'>";
                echo "<button type='submit'>Successiva &raquo;</button>";
                echo "</form>";

                echo "</div>";
                echo "</div>";
            } else {
                echo "<p>UOT non trovata.</p>";
            }
        } else {
            echo "<p>Seleziona una UOT per visualizzarne i dettagli.</p>";
        }

        $db->close();
        ?>
    </div>
    
<?php include 'include/footer.php';?>

</body>
</html>