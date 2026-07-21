<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrait - Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body>
<main class="container py-4 py-lg-5 operation-page">
    <div class="row justify-content-center"><div class="col-lg-6">
        <a href="<?= site_url('client/solde') ?>" class="back-link mb-3 d-inline-flex">‹ Retour au tableau de bord</a>
        <section class="card app-card overflow-hidden">
            <header class="operation-hero operation-hero-withdraw p-4 p-lg-5">
                <span class="operation-icon operation-icon-retrait bg-white">↑</span>
                <p class="text-uppercase small fw-semibold opacity-75 mt-3 mb-2">Opération</p>
                <h1 class="h3 mb-1">Retrait d'argent</h1>
                <p class="mb-0 opacity-75">Retirez de l'argent depuis votre compte Mobile Money.</p>
            </header>
            <div class="card-body p-4 p-lg-5">
                <div class="account-summary mb-4"><span>Compte</span><strong><?= esc($client['telephone']) ?></strong><div><small>Solde disponible</small><b><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</b></div></div>
                <?php if (session()->getFlashdata('error')): ?><div class="alert alert-danger alert-dismissible fade show"><?= esc(session()->getFlashdata('error')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
                <form method="post" action="<?= site_url('client/retrait') ?>">
                    <?= csrf_field() ?>
                    <label for="montant" class="form-label fw-semibold">Montant à retirer (Ar)</label>
                    <div class="input-group input-group-lg mb-2"><input type="number" id="montant" name="montant" class="form-control" placeholder="Ex. 10 000" min="1" step="1" value="<?= old('montant') ?>" required><span class="input-group-text">Ar</span></div>
                    <p class="form-text mb-4">Les frais applicables seront ajoutés automatiquement.</p>
                    <button type="submit" class="btn btn-warning w-100 py-2">Confirmer le retrait</button>
                </form>
            </div>
        </section>
    </div></div>
</main>
</body>
</html>
