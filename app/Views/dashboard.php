<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tableau de bord — Opérateur</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #0B1F1C;
    --panel: #12302B;
    --panel-alt: #173A33;
    --border: rgba(243,239,227,0.09);
    --text: #F3EFE3;
    --text-muted: #8FA89F;
    --gold: #D8A857;
    --teal: #3FCDB2;
    --danger: #E0785A;
    --radius: 14px;
  }
  *{box-sizing:border-box;}
  body{
    margin:0;
    background:
      radial-gradient(ellipse at top left, rgba(63,205,178,0.06), transparent 45%),
      var(--bg);
    color:var(--text);
    font-family:'Inter', sans-serif;
    -webkit-font-smoothing:antialiased;
  }
  .mono{ font-family:'JetBrains Mono', monospace; }
  a{ color:inherit; }

  /* ---- Topbar ---- */
  .topbar{
    display:flex; align-items:center; justify-content:space-between;
    padding:22px 40px;
    border-bottom:1px solid var(--border);
  }
  .brand{ display:flex; align-items:center; gap:12px; }
  .brand-mark{ width:30px; height:30px; flex-shrink:0; }
  .brand-name{ font-family:'Fraunces', serif; font-weight:600; font-size:18px; letter-spacing:0.01em; }
  .brand-tag{
    font-family:'JetBrains Mono', monospace; font-size:11px; color:var(--gold);
    border:1px solid rgba(216,168,87,0.35); border-radius:20px; padding:2px 9px; margin-left:8px;
  }
  .topbar-right{ font-size:13px; color:var(--text-muted); font-family:'JetBrains Mono', monospace;}
  .topbar-links{ display:flex; gap:22px; font-size:13px; color:var(--text-muted); }
  .topbar-links a:hover{ color:var(--text); }

  /* ---- Hero ---- */
  .hero{ padding:44px 40px 8px; }
  .hero-label{ font-size:13px; color:var(--text-muted); margin-bottom:10px; }
  .hero-amount{
    font-family:'Fraunces', serif; font-weight:600; font-size:clamp(40px,6vw,64px);
    line-height:1; letter-spacing:-0.01em;
  }
  .hero-amount .unit{ font-size:0.4em; color:var(--text-muted); font-weight:500; margin-left:8px; }

  /* ---- Layout ---- */
  .grid{
    display:grid;
    grid-template-columns: 340px 1fr;
    gap:22px;
    padding:28px 40px 40px;
    align-items:start;
  }
  @media (max-width: 900px){ .grid{ grid-template-columns:1fr; } }

  .stack{ display:flex; flex-direction:column; gap:22px; }

  .card{
    background:var(--panel);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:22px;
  }
  .card-title{
    font-size:12px; text-transform:uppercase; letter-spacing:0.08em;
    color:var(--text-muted); margin-bottom:16px; font-weight:600;
  }

  /* ---- KPI mini rows ---- */
  .kpi-row{ display:flex; align-items:baseline; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border); }
  .kpi-row:last-child{ border-bottom:none; }
  .kpi-label{ font-size:14px; color:var(--text-muted); }
  .kpi-value{ font-family:'JetBrains Mono', monospace; font-size:16px; font-weight:500; }

  .gain-bar-wrap{ margin:14px 0; }
  .gain-bar-head{ display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px; }
  .gain-bar-head .n{ color:var(--text-muted); font-family:'JetBrains Mono', monospace; font-size:12px; }
  .gain-bar-track{ height:8px; background:var(--panel-alt); border-radius:6px; overflow:hidden; }
  .gain-bar-fill{ height:100%; background:linear-gradient(90deg, var(--teal), var(--gold)); border-radius:6px; }

  /* ---- Fee ladder (signature element) ---- */
  .ladder-tabs{ display:flex; gap:6px; margin-bottom:18px; }
  .ladder-tab{
    font-size:12px; padding:6px 14px; border-radius:20px; cursor:pointer;
    border:1px solid var(--border); color:var(--text-muted); background:transparent;
    font-family:'Inter', sans-serif;
  }
  .ladder-tab.active{ background:var(--panel-alt); color:var(--text); border-color:rgba(216,168,87,0.4); }

  .ladder{ display:flex; flex-direction:column-reverse; gap:5px; }
  .ladder-step{
    display:grid; grid-template-columns: 1fr auto; align-items:center;
    background:var(--panel-alt);
    border-radius:8px; padding:8px 14px;
    border-left:3px solid var(--teal);
  }
  .ladder-step .range{ font-size:12.5px; color:var(--text-muted); }
  .ladder-step .fee{ font-family:'JetBrains Mono', monospace; font-size:13.5px; color:var(--gold); font-weight:500; }

  /* ---- Table ---- */
  table{ width:100%; border-collapse:collapse; font-size:13.5px; }
  th{
    text-align:left; font-size:11px; text-transform:uppercase; letter-spacing:0.06em;
    color:var(--text-muted); font-weight:600; padding:0 10px 10px; border-bottom:1px solid var(--border);
  }
  td{ padding:12px 10px; border-bottom:1px solid var(--border); }
  tr:last-child td{ border-bottom:none; }
  .status-pill{
    display:inline-block; font-size:11px; padding:3px 10px; border-radius:20px;
    font-family:'JetBrains Mono', monospace;
  }
  .status-succes{ background:rgba(63,205,178,0.14); color:var(--teal); }
  .status-echec{ background:rgba(224,120,90,0.14); color:var(--danger); }
  .status-en_attente{ background:rgba(216,168,87,0.14); color:var(--gold); }

  .empty-state{
    text-align:center; padding:34px 10px; color:var(--text-muted); font-size:13.5px;
  }
  .empty-state .big{ font-family:'Fraunces', serif; font-size:15px; color:var(--text); display:block; margin-bottom:6px;}
</style>
</head>
<body>

<div class="topbar">
  <div class="brand">
    <svg class="brand-mark" viewBox="0 0 30 30" fill="none">
      <path d="M4 15 Q10 6 15 15 T26 15" stroke="#D8A857" stroke-width="2" fill="none" stroke-linecap="round"/>
      <path d="M4 21 Q10 12 15 21 T26 21" stroke="#3FCDB2" stroke-width="2" fill="none" stroke-linecap="round" opacity="0.7"/>
    </svg>
    <span class="brand-name">MoneyOp</span>
    <span class="brand-tag">V1</span>
  </div>
  <div class="topbar-links">
    <a href="#">Préfixes</a>
    <a href="#">Types d'opération</a>
    <a href="#">Comptes clients</a>
  </div>
  <div class="topbar-right"><?= date('d/m/Y — H:i') ?></div>
</div>

<div class="hero">
  <div class="hero-label">Solde cumulé de tous les comptes clients</div>
  <div class="hero-amount"><?= number_format($soldeTotal, 0, ',', ' ') ?><span class="unit">Ar</span></div>
</div>

<div class="grid">

  <!-- Colonne gauche : KPI + gains -->
  <div class="stack">

    <div class="card">
      <div class="card-title">Situation générale</div>
      <div class="kpi-row">
        <span class="kpi-label">Clients actifs</span>
        <span class="kpi-value mono"><?= number_format($nbClientsActifs, 0, ',', ' ') ?></span>
      </div>
      <div class="kpi-row">
        <span class="kpi-label">Gains totaux (frais perçus)</span>
        <span class="kpi-value mono"><?= number_format($gainsTotal, 0, ',', ' ') ?> Ar</span>
      </div>
    </div>

    <div class="card">
      <div class="card-title">Gains par type d'opération</div>
      <?php if (empty($gainsParType)): ?>
        <div class="empty-state">
          <span class="big">Aucune opération enregistrée</span>
          Les gains apparaîtront ici dès la première transaction.
        </div>
      <?php else: ?>
        <?php
          $maxGain = max(array_column($gainsParType, 'gains')) ?: 1;
        ?>
        <?php foreach ($gainsParType as $g): ?>
          <div class="gain-bar-wrap">
            <div class="gain-bar-head">
              <span><?= esc($g['libelle'] ?? $g['code']) ?></span>
              <span class="n"><?= number_format($g['gains'], 0, ',', ' ') ?> Ar · <?= (int) $g['nb_operations'] ?> op.</span>
            </div>
            <div class="gain-bar-track">
              <div class="gain-bar-fill" style="width:<?= max(4, round(($g['gains'] / $maxGain) * 100)) ?>%"></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>

  <!-- Colonne droite : barème (escalier) + transactions -->
  <div class="stack">

    <div class="card">
      <div class="card-title">Barème de frais par tranche</div>
      <div class="ladder-tabs">
        <?php $first = true; foreach ($bareme as $code => $b): ?>
          <div class="ladder-tab <?= $first ? 'active' : '' ?>" data-target="ladder-<?= esc($code) ?>"><?= esc($b['libelle']) ?></div>
        <?php $first = false; endforeach; ?>
      </div>

      <?php $first = true; foreach ($bareme as $code => $b): ?>
        <div class="ladder" id="ladder-<?= esc($code) ?>" style="<?= $first ? '' : 'display:none;' ?>">
          <?php if (empty($b['tranches'])): ?>
            <div class="empty-state">Aucune tranche configurée pour ce type d'opération.</div>
          <?php else: foreach ($b['tranches'] as $t): ?>
            <div class="ladder-step">
              <span class="range">
                <?= number_format($t['montant_min'], 0, ',', ' ') ?> — <?= number_format($t['montant_max'], 0, ',', ' ') ?> Ar
              </span>
              <span class="fee"><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</span>
            </div>
          <?php endforeach; endif; ?>
        </div>
      <?php $first = false; endforeach; ?>
    </div>

    <div class="card">
      <div class="card-title">Dernières transactions</div>
      <?php if (empty($dernieresTransactions)): ?>
        <div class="empty-state">
          <span class="big">Aucune transaction pour le moment</span>
          Les dépôts, retraits et transferts des clients s'afficheront ici.
        </div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Client</th>
              <th>Opération</th>
              <th>Montant</th>
              <th>Frais</th>
              <th>Statut</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dernieresTransactions as $t): ?>
              <tr>
                <td class="mono"><?= esc($t['numero_telephone'] ?? '—') ?></td>
                <td><?= esc($t['type_libelle'] ?? '—') ?></td>
                <td class="mono"><?= number_format($t['montant'], 0, ',', ' ') ?> Ar</td>
                <td class="mono"><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
                <td><span class="status-pill status-<?= esc($t['statut']) ?>"><?= esc($t['statut']) ?></span></td>
                <td class="mono"><?= date('d/m H:i', strtotime($t['date_creation'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
  document.querySelectorAll('.ladder-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.ladder-tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.ladder').forEach(l => l.style.display = 'none');
      tab.classList.add('active');
      document.getElementById(tab.dataset.target).style.display = 'flex';
    });
  });
</script>

</body>
</html>
