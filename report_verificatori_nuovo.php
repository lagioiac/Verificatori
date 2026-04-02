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
 * - usa ogni record della tabella personaleuot (nessuna deduplica su XF)
 * - normalizza i flag competenza a 0/1 per evitare valori null/non binari
 */
$basePersonale = "
    SELECT
        p.IDXF AS idxf,
        p.iduot,
        CASE WHEN COALESCE(p.flgP, 0) = 1 THEN 1 ELSE 0 END AS flgP,
        CASE WHEN COALESCE(p.flgS, 0) = 1 THEN 1 ELSE 0 END AS flgS,
        CASE WHEN COALESCE(p.flgR, 0) = 1 THEN 1 ELSE 0 END AS flgR,
        CASE WHEN COALESCE(p.flgT, 0) = 1 THEN 1 ELSE 0 END AS flgT
    FROM personaleuot p
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
    FROM ( $basePersonale ) d
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
        FROM ( $basePersonale ) base
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

$partiFormula = [];
for ($k = 1; $k <= 4; $k++) {
    $nPersone = isset($distribuzioneCompetenzePersona[$k]) ? (int)$distribuzioneCompetenzePersona[$k] : 0;
    $partiFormula[] = $k . '×' . $nPersone;
}
$formulaDistribuzione = implode(' + ', $partiFormula);
$totaleCompetenzeAttive = (int)$riepilogo['competenze_attive_totali'];

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
    FROM ( $basePersonale ) d
    LEFT JOIN uot u ON d.iduot = u.IdUot
    LEFT JOIN comune c ON u.idcomune = c.idComune
    LEFT JOIN provincia pr ON c.idprovincia = pr.idProvincia
    LEFT JOIN regione r ON pr.idregione = r.idRegione
    GROUP BY r.idRegione, r.regione
    ORDER BY COALESCE(r.regione, 'Regione non associata') ASC
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
    FROM ( $basePersonale ) d
    LEFT JOIN uot u ON d.iduot = u.IdUot
    GROUP BY u.IdUot, u.denominazione
    ORDER BY COALESCE(u.denominazione, 'UOT non associata') ASC
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

        <h1 class="center-title">📅 Report Verificatori in Tabelle</h1>

        <p class="report-note">
            Il totale delle competenze attive può essere superiore al numero dei verificatori attivi,
            poiché una stessa persona può possedere più competenze.
        </p>

        <h2>Blocco 1 — Riepilogo generale</h2>
        <ul class="summary-inline-list">
            <li><strong>Personale UOT totale:</strong> <?= (int)$riepilogo['personale_totale'] ?></li>
            <li><strong>Verificatori attivi:</strong> <?= (int)$riepilogo['verificatori_attivi'] ?></li>
            <li><strong>Personale non operativo nelle verifiche:</strong> <?= (int)$riepilogo['personale_non_operativo'] ?></li>
            <li><strong>Competenze attive complessive P, S, R, T:</strong> <?= $totaleCompetenzeAttive ?></li>
        </ul>

        <div class="row spacer"></div>
        <h2>Blocco 2 — Distribuzione competenze</h2>
        <table class="report-table report-table-v2">
            <thead>
                <tr><th>Competenza</th><th>Totale</th></tr>
            </thead>
            <tbody>
                <tr><td>Pressione (P)</td><td><?= (int)$riepilogo['totale_p'] ?></td></tr>
                <tr><td>Sollevamento (S)</td><td><?= (int)$riepilogo['totale_s'] ?></td></tr>
                <tr><td>Riscaldamento (R)</td><td><?= (int)$riepilogo['totale_r'] ?></td></tr>
                <tr><td>Terra / scariche atmosferiche (T)</td><td><?= (int)$riepilogo['totale_t'] ?></td></tr>
            </tbody>
            <tfoot>
                <tr class="table-footer-bar"><td colspan="2"></td></tr>
            </tfoot>
        </table>

        <h3>Blocco 3 - Distribuzione numero competenze per persona attiva</h3>
        <p class="table-caption">
            Lettura tabella: il totale delle competenze attive è dato dalla somma tra
            <em>competenze per persona × numero persone</em> per ciascuna riga.
            <br> <!-- Questo inserisce l'interruzione di riga -->
            In questo report: <?= htmlspecialchars($formulaDistribuzione) ?> = <?= $totaleCompetenzeAttive ?>.
        </p>        
        <table class="report-table report-table-v2">
            <thead>
                <tr><th>Competenze per persona</th><th>Numero persone</th></tr>
            </thead>
            <tbody>
                <?php for ($k = 1; $k <= 4; $k++): ?>
                    <tr>
                        <td><?= $k ?></td>
                        <td><?= isset($distribuzioneCompetenzePersona[$k]) ? $distribuzioneCompetenzePersona[$k] : 0 ?></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
            <tfoot>
                <tr class="table-footer-bar"><td colspan="2"></td></tr>
            </tfoot>
        </table>

        <div class="row spacer"></div>
        <h2>Blocco 4 — Distribuzione dei verificatori attivi nelle regioni</h2>
        <table class="report-table report-table-v2">
            <thead>
                <tr>
                    <th>Regione</th>
                    <th>Verificatori attivi</th>
                    <th>totale_P</th>
                    <th>totale_S</th>
                    <th>totale_R</th>
                    <th>totale_T</th>
                    <th>totale_competenze</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($righeRegioni as $riga): ?>
                    <tr>
                        <td><?= htmlspecialchars($riga['regione'] ?: 'Regione non associata') ?></td>
                        <td><?= (int)$riga['verificatori_attivi'] ?></td>
                        <td><?= (int)$riga['totale_P'] ?></td>
                        <td><?= (int)$riga['totale_S'] ?></td>
                        <td><?= (int)$riga['totale_R'] ?></td>
                        <td><?= (int)$riga['totale_T'] ?></td>
                        <td><?= (int)$riga['totale_competenze'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-footer-bar"><td colspan="7"></td></tr>
            </tfoot>
        </table>

        <div class="row spacer"></div>
        <h2>Blocco 5 — Dettaglio competenze per UOT</h2>
        <table class="report-table report-table-v2">
            <thead>
                <tr>
                    <th>UOT</th>
                    <th>Verificatori attivi</th>
                    <th>totale_P</th>
                    <th>totale_S</th>
                    <th>totale_R</th>
                    <th>totale_T</th>
                    <th>totale_competenze</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($righeUot as $riga): ?>
                    <tr>
                        <td><?= htmlspecialchars($riga['uot'] ?: 'UOT non associata') ?></td>
                        <td><?= (int)$riga['verificatori_attivi'] ?></td>
                        <td><?= (int)$riga['totale_P'] ?></td>
                        <td><?= (int)$riga['totale_S'] ?></td>
                        <td><?= (int)$riga['totale_R'] ?></td>
                        <td><?= (int)$riga['totale_T'] ?></td>
                        <td><?= (int)$riga['totale_competenze'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-footer-bar"><td colspan="7"></td></tr>
            </tfoot>
        </table>

        <div class="back-button">
            <a href="verificatori.php" class="back">Indietro</a>
        </div>
    </div>
</body>
</html>