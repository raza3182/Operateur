<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dépôt - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">

            <h4 class="text-center mb-4">Dépôt</h4>

            <p class="text-muted mb-1">Compte</p>
            <p class="fw-bold mb-3"><?= esc($client['telephone']) ?></p>

            <div class="border rounded p-2 mb-4 bg-white text-center">
                <small class="text-muted">Solde actuel</small>
                <h5 class="text-success mb-0">
                    <?= number_format($client['solde'], 0, ',', ' ') ?> Ar
                </h5>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('client/depot') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="montant" class="form-label">Montant à déposer (Ar)</label>
                    <input
                        type="number"
                        step="1"
                        min="1"
                        name="montant"
                        id="montant"
                        class="form-control"
                        placeholder="Ex: 10000"
                        value="<?= old('montant') ?>"
                        required
                    >
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Confirmer le dépôt</button>
                    <a href="<?= site_url('client/solde') ?>" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>