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
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">

            <h4 class="text-center mb-4">Transfert</h4>

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

            <form method="post" action="<?= site_url('client/transfert') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="destinataires" class="form-label">Numéro(s) destinataire(s)</label>
                    <textarea name="destinataires" id="destinataires" class="form-control" rows="3" placeholder="Ex: 0381234567&#10;0371234567" required><?= esc(old('destinataires')) ?></textarea>
                    <div class="form-text">Un numéro par ligne, ou séparez-les par une virgule. Le montant total sera divisé équitablement.</div>
                </div>

                <div class="mb-3">
                    <label for="montant" class="form-label">Montant à transférer (Ar)</label>
                    <input
                        type="number"
                        step="1"
                        min="1"
                        name="montant"
                        id="montant"
                        class="form-control"
                        placeholder="Ex: 5000"
                        value="<?= old('montant') ?>"
                        required
                    >
                    <div class="form-text">Les frais normaux s’appliquent. Une commission est ajoutée si le destinataire est chez un autre opérateur.</div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="inclure_frais_retrait" value="1" id="inclure_frais_retrait" <?= old('inclure_frais_retrait') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="inclure_frais_retrait">Inclure les frais de retrait pour les destinataires du même opérateur</label>
                    <div class="form-text">Aucun frais de retrait n’est ajouté pour les autres opérateurs.</div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-info text-white">Confirmer le transfert</button>
                    <a href="<?= site_url('client/solde') ?>" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>
