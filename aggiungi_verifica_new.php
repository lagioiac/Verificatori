<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Verifica</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 style="text-align: center; font-size: 2em; font-weight: bold;">Aggiungi Verifica</h1>
        <form method="POST" action="inserisci_verifica.php">
            <!-- Selezione del verificatore -->
            <label for="idverificatore">Verificatore:</label>
            <select name="idverificatore" id="idverificatore">
                <?php
                $verificatori = $db->query("SELECT IDXf, Cognome, Nome FROM personaleuot");
                while ($row = $db->fetchassoc($verificatori)) {
                    echo "<option value='{$row['IDXf']}'>{$row['Cognome']} {$row['Nome']}</option>";
                }
                ?>
            </select>

            <!-- Selezione del sito di verifica -->
            <label for="idSito_Verifica">Sito di Verifica:</label>
            <select name="idSito_Verifica" id="idSito_Verifica">
                <?php
                $siti = $db->query("SELECT idSito_Verifica, denominazione FROM sito_verifica");
                while ($row = $db->fetchassoc($siti)) {
                    echo "<option value='{$row['idSito_Verifica']}'>{$row['denominazione']}</option>";
                }
                ?>
            </select>

            <!-- Selezione della UOT -->
            <label for="iduot">UOT:</label>
            <select name="iduot" id="iduot">
                <?php
                $uot = $db->query("SELECT IdUot, denominazione FROM uot");
                while ($row = $db->fetchassoc($uot)) {
                    echo "<option value='{$row['IdUot']}'>{$row['denominazione']}</option>";
}
                ?>
            </select>
            
            <!-- Selezione del tipo di verifica -->
            <label for="idtipoverifiche">Tipo di Verifica:</label
            
            <select name="idtipoverifiche" id="idtipoverifiche">
                <?php
                $tipoverifiche = $db->query("SELECT IdTipoVerifiche, tipo_verifica FROM tipoverifiche");
                while ($row = $db->fetchassoc($tipoverifiche)) {
                    echo "<option value='{$row['IdTipoVerifiche']}'>{$row['tipo_verifica']}</option>";
                }
                ?>
            </select>

            <!-- Selezione dello stato della verifica -->
            <label for="idstatoverifica">Stato della Verifica:</label>
            <select name="idstatoverifica" id="idstatoverifica">
                <?php
                $statoverifiche = $db->query("SELECT IdStato, stato FROM statoverifica");
                while ($row = $db->fetchassoc($statoverifiche)) {
                    echo "<option value='{$row['IdStato']}'>{$row['stato']}</option>";
                }
                ?>
            </select>

            <!-- Input per la data di assegnazione, inizio, sospensione e fine della verifica -->
            <label for="dataAssegnazioneVer">Data Assegnazione:</label>
            <input type="date" name="dataAssegnazioneVer" id="dataAssegnazioneVer" required>

            <label for="dataInizioVer">Data Inizio:</label>
            <input type="date" name="dataInizioVer" id="dataInizioVer">

            <label for="dataSospensioneVer">Data Sospensione:</label>
            <input type="date" name="dataSospensioneVer" id="dataSospensioneVer">

            <label for="dataFineVer">Data Fine:</label>
            <input type="date" name="dataFineVer" id="dataFineVer">

            <!-- Input per le note -->
            <label for="note">Note:</label>
            <textarea name="note" id="note"></textarea>

            <!-- Bottone per inviare il form -->
            <button type="submit">Aggiungi Verifica</button>
        </form>
    </div>
</body>
</html>
