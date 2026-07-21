<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert - Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body>
<main class="container py-4 py-lg-5 operation-page">
    <div class="row justify-content-center"><div class="col-lg-7">
        <a href="<?= site_url('client/solde') ?>" class="back-link mb-3 d-inline-flex">‹ Retour au tableau de bord</a>
        <section class="card app-card overflow-hidden">
            <header class="operation-hero operation-hero-transfer p-4 p-lg-5">
                <span class="operation-icon operation-icon-transfert bg-white">↗</span>
                <p class="text-uppercase small fw-semibold opacity-75 mt-3 mb-2">Opération</p>
                <h1 class="h3 mb-1">Transfert d'argent</h1>
                <p class="mb-0 opacity-75">Envoyez de l'argent à un ou plusieurs destinataires.</p>
            </header>
            <div class="card-body p-4 p-lg-5">
                <div class="account-summary mb-4"><span>Compte expéditeur</span><strong><?= esc($client['telephone']) ?></strong><div><small>Solde disponible</small><b><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</b></div></div>
                <?php if (session()->getFlashdata('error')): ?><div class="alert alert-danger alert-dismissible fade show"><?= esc(session()->getFlashdata('error')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
                <form method="post" action="<?= site_url('client/transfert') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-4"><label for="destinataires" class="form-label fw-semibold">Destinataire(s)</label><textarea id="destinataires" class="form-control" rows="4" name="destinataires" placeholder="0331234567&#10;0349876543" required><?= esc(old('destinataires')) ?></textarea><div class="form-text">Un numéro par ligne, ou plusieurs numéros séparés par une virgule.</div></div>
                    <div class="mb-4"><label for="montant" class="form-label fw-semibold">Montant à transférer (Ar)</label><div class="input-group input-group-lg"><input type="number" id="montant" class="form-control" name="montant" placeholder="Ex. 20 000" min="1" step="1" value="<?= old('montant') ?>" required><span class="input-group-text">Ar</span></div><div class="form-text">Les frais de transfert sont calculés automatiquement.</div></div>
                    <div class="prepaid-option mb-4"><input class="form-check-input" type="checkbox" id="inclure_frais_retrait" name="inclure_frais_retrait" value="1" <?= old('inclure_frais_retrait') ? 'checked' : '' ?>><label for="inclure_frais_retrait"><strong>Inclure les frais de retrait</strong><small>Disponible uniquement pour un transfert du même opérateur.</small></label></div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Confirmer le transfert</button>
                </form>
            </div>
        </section>
    </div></div>
</main>
</body>
</html>
