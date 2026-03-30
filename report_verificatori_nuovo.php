<?php
// report_verificatori_nuovo.php
// Report avanzato per separare chiaramente: persone vs competenze.

include 'config.php';
include 'db/mysql.php';
include 'include/check_user.php';

// ricordarsi di chiudere head derivante dal header.php
include 'include/header.php';

$db = new DbConnect();
$db->open() or die($db->error());

/**
 * Subquery comune:
 * - deduplica persone su XF (fallback su IDXF se XF vuoto/null)
 * - normalizza i flag competenza a 0/1
 */
$baseDedup = "
    SELECT
        COALESCE(NULLIF(TRIM(p.XF), ''), CONCAT('IDXF_', p.IDXF)) AS persona_key,
        MIN(p.IDXF) AS idxf,
        MIN(p.iduot) AS iduot,
        MAX(CASE WHEN COALESCE(p.flgP, 0) = 1 THEN 1 ELSE 0 END) AS flgP,
        MAX(CASE WHEN COALESCE(p.flgS, 0) = 1 THEN 1 ELSE 0 END) AS flgS,
        MAX(CASE WHEN COALESCE(p.flgR, 0) = 1 THEN 1 ELSE 0 END) AS flgR,
        MAX(CASE WHEN COALESCE(p.flgT, 0) = 1 THEN 1 ELSE 0 END) AS flgT
    FROM personaleuot p
    GROUP BY COALESCE(NULLIF(TRIM(p.XF), ''), CONCAT('IDXF_', p.IDXF))
";

// BLOCCO 1 + BLOCCO 2 (riepilogo generale + distribuzione competenze P/S/R/T)
$queryRiepilogo = "
    SELECT
        COUNT(*) AS personale_totale,
        SUM(CASE WHEN (d.flgP + d.flgS + d.flgR + d.flgT) > 0 THEN 1 ELSE 0 END) AS verificatori_attivi,
        SUM(CASE WHEN (d.flgP + d.flgS + d.flgR + d.flgT) = 0 THEN 1 ELSE 0 END) AS personale_non_operativo,
        SUM(d.flgP + d.flgS + d.flgR + d.flgT) AS competenze_attive_totali,
        SUM(d.flgP) AS totale_p,
        SUM(d.flgS) AS totale_s,
        SUM(d.flgR) AS totale_r,
        SUM(d.flgT) AS totale_t
    FROM ( $baseDedup ) d
";

$resRiepilogo = $db->query($queryRiepilogo) or die($db->error());
$riepilogo = $db->fetchassoc2($resRiepilogo);

// Distribuzione competenze per persona (1,2,3,4 competenze)
$queryDistribuzionePersona = "
    SELECT
        d.n_competenze,
        COUNT(*) AS numero_persone
    FROM (
        SELECT
            (base.flgP + base.flgS + base.flgR + base.flgT) AS n_competenze
        FROM ( $baseDedup ) base
        WHERE (base.flgP + base.flgS + base.flgR + base.flgT) > 0
    ) d
    GROUP BY d.n_competenze
    ORDER BY d.n_competenze ASC
";

$resDistribuzionePersona = $db->query($queryDistribuzionePersona) or die($db->error());
$distribuzioneCompetenzePersona = [];
while ($row = $db->fetchassoc2($resDistribuzionePersona)) {
    $distribuzioneCompetenzePersona[(int)$row['n_competenze']] = (int)$row['numero_persone'];
}

// BLOCCO 3 - Sintesi per regione
$queryRegioni = "
    SELECT
        r.regione,
        SUM(CASE WHEN (d.flgP + d.flgS + d.flgR + d.flgT) > 0 THEN 1 ELSE 0 END) AS verificatori_attivi,
        SUM(d.flgP) AS totale_P,
        SUM(d.flgS) AS totale_S,
        SUM(d.flgR) AS totale_R,
        SUM(d.flgT) AS totale_T,
        SUM(d.flgP + d.flgS + d.flgR + d.flgT) AS totale_competenze
    FROM ( $baseDedup ) d
    JOIN uot u ON d.iduot = u.IdUot
    JOIN comune c ON u.idcomune = c.idComune
    JOIN provincia pr ON c.idprovincia = pr.idProvincia
    JOIN regione r ON pr.idregione = r.idRegione
    GROUP BY r.idRegione, r.regione
    ORDER BY r.regione ASC
";

$resRegioni = $db->query($queryRegioni) or die($db->error());
$righeRegioni = [];
while ($row = $db->fetchassoc2($resRegioni)) {
    $righeRegioni[] = $row;
}

// BLOCCO 4 - Dettaglio per UOT
$queryUot = "
    SELECT
        u.denominazione AS uot,
        SUM(CASE WHEN (d.flgP + d.flgS + d.flgR + d.flgT) > 0 THEN 1 ELSE 0 END) AS verificatori_attivi,
        SUM(d.flgP) AS totale_P,
        SUM(d.flgS) AS totale_S,
        SUM(d.flgR) AS totale_R,
        SUM(d.flgT) AS totale_T,
        SUM(d.flgP + d.flgS + d.flgR + d.flgT) AS totale_competenze
    FROM ( $baseDedup ) d
    JOIN uot u ON d.iduot = u.IdUot
    GROUP BY u.IdUot, u.denominazione
    ORDER BY u.denominazione ASC
";

$resUot = $db->query($queryUot) or die($db->error());
$righeUot = [];
while ($row = $db->fetchassoc2($resUot)) {
    $righeUot[] = $row;
}
?>

</head>
<body>
    <div class="container report-verificatori-v2">
        <a href="verificatori.php" class="back">Indietro</a>

        <h1 class="center-title">Report Verificatori (Nuovo)</h1>

        <p class="report-note">
            Il totale delle competenze attive può essere superiore al numero dei verificatori attivi,
            poiché una stessa persona può possedere più competenze.
        </p>

        <h2>Blocco 1 — Riepilogo generale</h2>
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-title">Personale UOT totale</div>
                <div class="summary-value"><?= (int)$riepilogo['personale_totale'] ?></div>
            </div>
            <div class="summary-card">
                <div class="summary-title">Verificatori attivi</div>
                <div class="summary-value"><?= (int)$riepilogo['verificatori_attivi'] ?></div>
            </div>
            <div class="summary-card">
                <div class="summary-title">Personale non operativo nelle verifiche</div>
                <div class="summary-value"><?= (int)$riepilogo['personale_non_operativo'] ?></div>
            </div>
            <div class="summary-card">
                <div class="summary-title">Competenze attive complessive P, S, R, T</div>
                <div class="summary-value"><?= (int)$riepilogo['competenze_attive_totali'] ?></div>
            </div>
        </div>

        <div class="row spacer"></div>
        <h2>Blocco 2 — Distribuzione competenze</h2>
        <table class="report-table">
            <tr><th>Competenza</th><th>Totale</th></tr>
            <tr><td>Pressione (P)</td><td><?= (int)$riepilogo['totale_p'] ?></td></tr>
            <tr><td>Sollevamento (S)</td><td><?= (int)$riepilogo['totale_s'] ?></td></tr>
            <tr><td>Riscaldamento (R)</td><td><?= (int)$riepilogo['totale_r'] ?></td></tr>
            <tr><td>Terra / scariche atmosferiche (T)</td><td><?= (int)$riepilogo['totale_t'] ?></td></tr>
        </table>

        <h3>Distribuzione numero competenze per persona attiva</h3>
        <table class="report-table">
            <tr><th>Competenze per persona</th><th>Numero persone</th></tr>
            <?php for ($k = 1; $k <= 4; $k++): ?>
                <tr>
                    <td><?= $k ?></td>
                    <td><?= isset($distribuzioneCompetenzePersona[$k]) ? $distribuzioneCompetenzePersona[$k] : 0 ?></td>
                </tr>
            <?php endfor; ?>
        </table>

        <div class="row spacer"></div>
        <h2>Blocco 3 — Distribuzione dei verificatori attivi nelle regioni</h2>
        <table class="report-table">
            <tr>
                <th>Regione</th>
                <th>Verificatori attivi</th>
                <th>totale_P</th>
                <th>totale_S</th>
                <th>totale_R</th>
                <th>totale_T</th>
                <th>totale_competenze</th>
            </tr>
            <?php foreach ($righeRegioni as $riga): ?>
                <tr>
                    <td><?= htmlspecialchars($riga['regione']) ?></td>
                    <td><?= (int)$riga['verificatori_attivi'] ?></td>
                    <td><?= (int)$riga['totale_P'] ?></td>
                    <td><?= (int)$riga['totale_S'] ?></td>
                    <td><?= (int)$riga['totale_R'] ?></td>
                    <td><?= (int)$riga['totale_T'] ?></td>
                    <td><?= (int)$riga['totale_competenze'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="row spacer"></div>
        <h2>Blocco 4 — Dettaglio competenze per UOT</h2>
        <table class="report-table">
            <tr>
                <th>UOT</th>
                <th>Verificatori attivi</th>
                <th>totale_P</th>
                <th>totale_S</th>
                <th>totale_R</th>
                <th>totale_T</th>
                <th>totale_competenze</th>
            </tr>
            <?php foreach ($righeUot as $riga): ?>
                <tr>
                    <td><?= htmlspecialchars($riga['uot']) ?></td>
                    <td><?= (int)$riga['verificatori_attivi'] ?></td>
                    <td><?= (int)$riga['totale_P'] ?></td>
                    <td><?= (int)$riga['totale_S'] ?></td>
                    <td><?= (int)$riga['totale_R'] ?></td>
                    <td><?= (int)$riga['totale_T'] ?></td>
                    <td><?= (int)$riga['totale_competenze'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="back-button">
            <a href="verificatori.php" class="back">Indietro</a>
        </div>
    </div>
</body>
</html>