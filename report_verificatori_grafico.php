<?php
include 'config.php';
include 'db/mysql.php';
include 'include/check_user.php';
include 'include/header.php';

$db = new DbConnect();
$db->open() or die($db->error());

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
$distribuzioneCompetenzePersona = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
while ($row = $db->fetchassoc2($resDistribuzionePersona)) {
    $distribuzioneCompetenzePersona[(int)$row['n_competenze']] = (int)$row['numero_persone'];
}

$queryRegioni = "
    SELECT
        COALESCE(r.regione, 'Regione non associata') AS regione,
        SUM(CASE WHEN (d.flgP + d.flgS + d.flgR + d.flgT) > 0 THEN 1 ELSE 0 END) AS verificatori_attivi,
        SUM(d.flgP + d.flgS + d.flgR + d.flgT) AS totale_competenze
    FROM ( $basePersonale ) d
    LEFT JOIN uot u ON d.iduot = u.IdUot
    LEFT JOIN comune c ON u.idcomune = c.idComune
    LEFT JOIN provincia pr ON c.idprovincia = pr.idProvincia
    LEFT JOIN regione r ON pr.idregione = r.idRegione
    GROUP BY r.idRegione, r.regione
    ORDER BY regione ASC
";
$resRegioni = $db->query($queryRegioni) or die($db->error());
$regioni = [];
$verificatoriRegione = [];
$competenzeRegione = [];
while ($row = $db->fetchassoc2($resRegioni)) {
    $regioni[] = $row['regione'];
    $verificatoriRegione[] = (int)$row['verificatori_attivi'];
    $competenzeRegione[] = (int)$row['totale_competenze'];
}
?>

</head>
<body>
    <div class="container report-verificatori-v2">
        <a href="verificatori.php" class="back">Indietro</a>
        <h1 class="center-title">📊 Report Verificatori con Grafici</h1>

        <p class="report-note">Rappresentazione grafica dei blocchi del report verificatori.</p>

        <div class="chart-section">
            <h2>Blocco 1 — Riepilogo generale</h2>
            <canvas id="chartRiepilogo"></canvas>
        </div>

        <div class="chart-section chart-section-pie">
            <h2>Blocco 2 — Distribuzione competenze P/S/R/T</h2>
            <canvas id="chartCompetenze"></canvas>
        </div>

        <div class="chart-section">
            <h3>Blocco 3 - Distribuzione numero competenze per persona attiva</h3>
            <canvas id="chartCompetenzePersona"></canvas>
        </div>

        <div class="chart-section">
            <h2>Blocco 4 — Distribuzione dei verificatori attivi nelle regioni</h2>
            <canvas id="chartRegioni"></canvas>
        </div>

        <div class="chart-section">
            <h2>Blocco 5 — Competenze complessive per regione</h2>
            <canvas id="chartCompetenzeRegioni"></canvas>
        </div>

        <div class="back-button">
            <a href="verificatori.php" class="back">Indietro</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const palette = {
            blu: '#0B3C8C',
            petrolio: '#0B8F8C',
            arancio: '#F28E2B',
            magenta: '#D45087',
            verde: '#2E7D32',
            rosso: '#C62828',
            grigio: '#4B5563'
        };

        const valueLabelPlugin = {
            id: 'valueLabelPlugin',
            afterDatasetsDraw(chart) {
                if (chart.config.type !== 'bar') {
                    return;
                }

                const {ctx} = chart;
                chart.data.datasets.forEach((dataset, datasetIndex) => {
                    const meta = chart.getDatasetMeta(datasetIndex);
                    meta.data.forEach((bar, index) => {
                        const value = dataset.data[index];
                        if (value === null || typeof value === 'undefined') {
                            return;
                        }
                        ctx.save();
                        ctx.font = 'bold 12px Arial';
                        ctx.fillStyle = '#111827';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        ctx.fillText(value, bar.x, bar.y - 4);
                        ctx.restore();
                    });
                });
            }
        };

        const pieInnerLabelPlugin = {
            id: 'pieInnerLabelPlugin',
            afterDatasetsDraw(chart) {
                if (chart.config.type !== 'pie') {
                    return;
                }

                const dataset = chart.data.datasets[0];
                const total = dataset.data.reduce((sum, val) => sum + val, 0);
                const meta = chart.getDatasetMeta(0);
                const {ctx} = chart;
                const values = dataset.data.map((val) => Number(val) || 0);

                // Percentuali con 1 decimale che sommano SEMPRE a 100.0%
                // Metodo "largest remainder" in decimi di punto percentuale (100.0 => 1000 decimi)
                const rawTenths = values.map((v) => (total > 0 ? (v * 1000) / total : 0));
                const baseTenths = rawTenths.map((v) => Math.floor(v));
                let assigned = baseTenths.reduce((s, v) => s + v, 0);
                const remainder = Math.max(0, 1000 - assigned);

                const order = rawTenths
                    .map((v, i) => ({i, frac: v - Math.floor(v)}))
                    .sort((a, b) => {
                        if (b.frac !== a.frac) {
                            return b.frac - a.frac;
                        }
                        return a.i - b.i;
                    });

                for (let k = 0; k < remainder; k++) {
                    const idx = order[k % order.length].i;
                    baseTenths[idx] += 1;
                }

                meta.data.forEach((arc, index) => {
                    const value = values[index];
                    const percentage = (baseTenths[index] / 10).toFixed(1);
                    const angle = (arc.startAngle + arc.endAngle) / 2;
                    const radius = arc.outerRadius * 0.62;
                    const x = arc.x + Math.cos(angle) * radius;
                    const y = arc.y + Math.sin(angle) * radius;

                    ctx.save();
                    ctx.font = 'bold 12px Arial';
                    ctx.fillStyle = '#ffffff';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(value, x, y - 8);
                    ctx.fillText(percentage + '%', x, y + 8);
                    ctx.restore();
                });
            }
        };

        Chart.register(valueLabelPlugin, pieInnerLabelPlugin);

        new Chart(document.getElementById('chartRiepilogo'), {
            type: 'bar',
            data: {
                labels: ['Personale totale', 'Verificatori attivi', 'Non operativo', 'Competenze attive'],
                datasets: [{
                    label: 'Totale',
                    data: [
                        <?= (int)$riepilogo['personale_totale'] ?>,
                        <?= (int)$riepilogo['verificatori_attivi'] ?>,
                        <?= (int)$riepilogo['personale_non_operativo'] ?>,
                        <?= (int)$riepilogo['competenze_attive_totali'] ?>
                    ],
                    backgroundColor: [palette.blu, palette.petrolio, palette.grigio, palette.verde]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.2,
                plugins: {legend: {display: false}},
                scales: {y: {beginAtZero: true, grace: '8%'}}
            }
        });

        new Chart(document.getElementById('chartCompetenze'), {
            type: 'pie',
            data: {
                labels: ['Pressione (P)', 'Sollevamento (S)', 'Riscaldamento (R)', 'Terra (T)'],
                datasets: [{
                    data: [
                        <?= (int)$riepilogo['totale_p'] ?>,
                        <?= (int)$riepilogo['totale_s'] ?>,
                        <?= (int)$riepilogo['totale_r'] ?>,
                        <?= (int)$riepilogo['totale_t'] ?>
                    ],
                    backgroundColor: [palette.blu, palette.arancio, palette.verde, palette.magenta],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.3,
                plugins: {legend: {position: 'top'}}
            }
        });

        new Chart(document.getElementById('chartCompetenzePersona'), {
            type: 'bar',
            data: {
                labels: ['1 competenza', '2 competenze', '3 competenze', '4 competenze'],
                datasets: [{
                    label: 'Numero persone',
                    data: [
                        <?= (int)$distribuzioneCompetenzePersona[1] ?>,
                        <?= (int)$distribuzioneCompetenzePersona[2] ?>,
                        <?= (int)$distribuzioneCompetenzePersona[3] ?>,
                        <?= (int)$distribuzioneCompetenzePersona[4] ?>
                    ],
                    backgroundColor: [palette.blu, palette.petrolio, palette.arancio, palette.magenta]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.2,
                plugins: {legend: {display: false}},
                scales: {y: {beginAtZero: true, grace: '10%'}}
            }
        });

        new Chart(document.getElementById('chartRegioni'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($regioni) ?>,
                datasets: [{
                    label: 'Verificatori attivi',
                    data: <?= json_encode($verificatoriRegione) ?>,
                    backgroundColor: palette.petrolio
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.6,
                scales: {
                    x: {ticks: {maxRotation: 55, minRotation: 20}},
                    y: {beginAtZero: true, grace: '10%'}
                }
            }
        });

        new Chart(document.getElementById('chartCompetenzeRegioni'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($regioni) ?>,
                datasets: [{
                    label: 'Competenze complessive',
                    data: <?= json_encode($competenzeRegione) ?>,
                    backgroundColor: palette.blu
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.6,
                scales: {
                    x: {ticks: {maxRotation: 55, minRotation: 20}},
                    y: {beginAtZero: true, grace: '10%'}
                }
            }
        });
    </script>
</body>
</html>