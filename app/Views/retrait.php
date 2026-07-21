<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Retrait - Mobile Money</title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5 col-md-7">

            <div class="card border-0 shadow-lg rounded-4">
                <!-- En-tête -->
                <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                    <h2 class="mb-1">
                        💵 Retrait d'argent
                    </h2>
                </div>

                <div class="card-body p-4">

                    <div class="text-center mb-4">

                        <h6 class="text-muted">
                            Compte
                        </h6>

                        <h4 class="fw-bold">
                            <?= esc($client['telephone']) ?>
                        </h4>

                    </div>

                    <!-- Solde -->
                    <div class="bg-light border rounded-4 p-4 mb-4 text-center">

                        <small class="text-muted">
                            Solde disponible
                        </small>

                        <h1 class="text-success fw-bold mt-2">

                            <?= number_format($client['solde'], 0, ',', ' ') ?>

                            <small class="fs-4">
                                Ar
                            </small>

                        </h1>

                    </div>

                    <!-- Messages -->
                    <?php if (session()->getFlashdata('error')): ?>

                        <div class="alert alert-danger alert-dismissible fade show">

                            <?= esc(session()->getFlashdata('error')) ?>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"></button>

                        </div>

                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>

                        <div class="alert alert-success alert-dismissible fade show">

                            <?= esc(session()->getFlashdata('success')) ?>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"></button>

                        </div>

                    <?php endif; ?>

                    <!-- Formulaire -->
                    <form method="post"
                          action="<?= site_url('client/retrait') ?>">

                        <?= csrf_field() ?>

                        <div class="mb-4">

                            <label for="montant"
                                   class="form-label fw-bold">

                                💰 Montant

                            </label>

                            <input
                                type="number"
                                id="montant"
                                name="montant"
                                class="form-control form-control-lg"
                                placeholder="Ex : 10 000"
                                min="1"
                                step="1"
                                value="<?= old('montant') ?>"
                                required>

                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-3">

                            <button type="submit"
                                    class="btn btn-warning btn-lg">

                                💵 Confirmer

                            </button>

                            <a href="<?= site_url('client/solde') ?>"
                               class="btn btn-outline-secondary btn-lg">

                                ⬅ Retour au tableau de bord

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>