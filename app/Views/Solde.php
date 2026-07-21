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

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5 col-md-7">

            <div class="card border-0 shadow-lg rounded-4">

                <!-- En-tête -->
                <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">

                    <h3 class="mb-1">
                        📱 Mobile Money
                    </h3>

                    <small>
                        Tableau de bord client
                    </small>

                </div>

                <div class="card-body p-4">

                    <h6 class="text-muted text-center">
                        Bienvenue
                    </h6>

                    <h3 class="text-center fw-bold mb-4">
                        <?= esc($client['nom']) ?>
                    </h3>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <div class="border rounded-4 p-3 mb-4 bg-light">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                📞 Numéro
                            </span>

                            <strong>
                                <?= esc($client['telephone']) ?>
                            </strong>

                        </div>

                        <hr>

                        <div class="text-center">

                            <small class="text-muted">
                                Solde disponible
                            </small>

                            <h1 class="text-success fw-bold mt-2">

                                <?= number_format($client['solde'],0,',',' ') ?>

                                <small class="fs-4">
                                    Ar
                                </small>

                            </h1>

                        </div>

                    </div>

                    <div class="d-grid gap-3">

                        <a href="<?= site_url('client/depot') ?>"
                           class="btn btn-primary btn-lg">

                            💰 Dépôt

                        </a>

                        <a href="<?= site_url('client/retrait') ?>"
                           class="btn btn-warning btn-lg">

                            💵 Retrait

                        </a>

                        <a href="<?= site_url('client/transfert') ?>"
                           class="btn btn-info btn-lg text-white">

                            🔄 Transfert

                        </a>

                        <a href="<?= site_url('client/historique') ?>"
                           class="btn btn-outline-dark btn-lg">

                            📜 Historique

                        </a>

                    </div>

                    <hr class="my-4">

                    <div class="text-center">

                        <a href="<?= site_url('client/logout') ?>"
                           class="btn btn-outline-danger">

                            🚪 Déconnexion

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>