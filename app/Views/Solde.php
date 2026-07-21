<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte - Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body>
<main class="container py-4 py-lg-5">
    <section class="dashboard-hero p-4 p-lg-5 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <p class="text-uppercase small fw-semibold mb-2 opacity-75">Espace personnel</p>
                <h1 class="h3 mb-1">Bonjour, <?= esc($client['nom']) ?></h1>
                <p class="mb-0 opacity-75">Compte <?= esc($client['telephone']) ?></p>
            </div>
            <a href="<?= site_url('client/logout') ?>" class="btn btn-light btn-sm">Se déconnecter</a>
        </div>
        <div class="balance-display mt-4"><span>Solde disponible</span><strong><?= number_format($client['solde'], 0, ',', ' ') ?> <small>Ar</small></strong></div>
    </section>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= esc(session()->getFlashdata('error')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"><?= esc(session()->getFlashdata('success')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <section id="depot" class="card app-card h-100"><div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4"><span class="operation-icon operation-icon-depot">↓</span><div><h2 class="section-title mb-1">Effectuer un dépôt</h2><p class="text-muted small mb-0">Créditez votre compte instantanément.</p></div></div>
                <form method="post" action="<?= site_url('client/depot') ?>">
                    <?= csrf_field() ?>
                    <label for="montant" class="form-label fw-semibold">Montant à déposer (Ar)</label>
                    <div class="input-group input-group-lg mb-3"><input type="number" step="1" min="1" name="montant" id="montant" class="form-control" placeholder="Ex. 10 000" value="<?= old('montant') ?>" required><span class="input-group-text">Ar</span></div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Confirmer le dépôt</button>
                </form>
            </div></section>
        </div>
        <div class="col-lg-7">
            <section class="card app-card h-100"><div class="card-body p-4">
                <h2 class="section-title mb-3">Autres opérations</h2>
                <div class="row g-3">
                    <div class="col-sm-6"><a class="operation-link" href="<?= site_url('client/retrait') ?>"><span class="operation-icon operation-icon-retrait">↑</span><span><strong>Retrait</strong><small>Retirer de l'argent</small></span><b>›</b></a></div>
                    <div class="col-sm-6"><a class="operation-link" href="<?= site_url('client/transfert') ?>"><span class="operation-icon operation-icon-transfert">↗</span><span><strong>Transfert</strong><small>Envoyer de l'argent</small></span><b>›</b></a></div>
                </div>
            </div></section>
        </div>
    </div>

    <section id="historique" class="card app-card mt-4"><div class="card-body p-4 p-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-4"><div><h2 class="section-title mb-1">Historique des opérations</h2><p class="text-muted small mb-0">Vos dernières transactions.</p></div><span class="badge text-bg-light border"><?= count($operations) ?> opération(s)</span></div>
        <?php if (empty($operations)): ?>
            <div class="empty-state">Aucune opération pour le moment.</div>
        <?php else: ?>
            <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Opération</th><th>Référence</th><th>Date</th><th class="text-end">Montant</th><th class="text-end">Frais</th><th class="text-end">Commission</th><th class="text-end">Frais retrait inclus</th><th class="text-end">État</th></tr></thead><tbody>
            <?php foreach ($operations as $operation): ?>
                <?php $type = strtoupper($operation['typeNom']); $libelle = $type === 'DEPOT' ? 'Dépôt' : ($type === 'RETRAIT' ? 'Retrait' : 'Transfert'); ?>
                <tr><td class="fw-semibold"><?= esc($libelle) ?></td><td class="small text-muted"><?= esc($operation['reference']) ?></td><td class="small"><?= esc($operation['dateOperation']) ?></td><td class="text-end fw-semibold"><?= number_format($operation['montant'], 0, ',', ' ') ?> Ar</td><td class="text-end text-muted"><?= number_format($operation['frais'], 0, ',', ' ') ?> Ar</td><td class="text-end text-primary"><?= number_format($operation['commissionInteroperateur'], 0, ',', ' ') ?> Ar</td><td class="text-end text-warning-emphasis"><?= number_format($operation['fraisRetraitInclus'], 0, ',', ' ') ?> Ar</td><td class="text-end"><span class="badge <?= $operation['etat'] === 'SUCCES' ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= esc($operation['etat']) ?></span></td></tr>
            <?php endforeach; ?>
            </tbody></table></div>
        <?php endif; ?>
    </div></section>
</main>
</body>
</html>
