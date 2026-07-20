<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Solde - Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body text-center p-4">

            <h5 class="text-muted mb-1">Bienvenue</h5>
            <h4 class="mb-4"><?= esc($client['nom']) ?></h4>

            <p class="text-muted mb-1">Numéro</p>
            <p class="fw-bold mb-4"><?= esc($client['telephone']) ?></p>

            <div class="border rounded p-3 mb-4 bg-white">
                <p class="text-muted mb-1">Solde actuel</p>
                <h2 class="text-success mb-0">
                    <?= number_format($client['solde'], 0, ',', ' ') ?> Ar
                </h2>
            </div>

            <!-- Boutons d'opérations -->
            <div class="d-grid gap-2 mb-4">
                <a href="<?= site_url('client/depot') ?>" class="btn btn-primary">
                    Dépôt
                </a>
                <a href="<?= site_url('client/retrait') ?>" class="btn btn-warning">
                    Retrait
                </a>
                <a href="<?= site_url('client/transfert') ?>" class="btn btn-info text-white">
                    Transfert
                </a>
                <a href="<?= site_url('client/historique') ?>" class="btn btn-outline-dark">
                    Historique
                </a>
            </div>

            <a href="<?= site_url('client/logout') ?>" class="btn btn-outline-secondary btn-sm">
                Se déconnecter
            </a>

        </div>
    </div>
</div>

</body>
</html>