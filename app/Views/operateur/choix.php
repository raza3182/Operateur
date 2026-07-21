<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir un opérateur - Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body class="bg-body-tertiary">

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="<?= site_url('operateur') ?>">
            Mobile Money
        </a>

        <a href="<?= site_url('/') ?>" class="btn btn-outline-primary rounded-pill px-3">
            Côté client
        </a>
    </div>
</nav>

<main class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-10 col-xl-9">

            <div class="text-center mb-5">
                <h1 class="display-6 fw-bold mb-2">
                    Choisir un opérateur
                </h1>

                <p class="text-secondary fs-5 mb-0">
                    Sélectionnez un opérateur pour accéder à son espace de configuration.
                </p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger shadow-sm">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success shadow-sm">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <div class="row g-4">

                <?php foreach ($operateurs as $operateur): ?>

                    <div class="col-md-6 col-xl-4">

                        <a href="<?= site_url('operateur/' . $operateur['idOperateur']) ?>"
                           class="text-decoration-none">

                            <div class="card h-100 border-0 shadow-sm">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-start mb-3">

                                        <div>
                                            <h5 class="card-title fw-bold text-dark mb-1">
                                                <?= esc($operateur['nom']) ?>
                                            </h5>

                                            <span class="badge text-bg-light border">
                                                <?= (int)$operateur['nombrePrefixes'] ?>
                                                préfixe(s)
                                            </span>
                                        </div>

                                        <span class="badge text-bg-primary">
                                            MM
                                        </span>

                                    </div>

                                    <p class="text-secondary small mb-4">
                                        Accéder à la configuration de cet opérateur et gérer ses paramètres.
                                    </p>

                                    <div class="d-grid">
                                        <span class="btn btn-primary">
                                            Ouvrir →
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </a>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

</main>

</body>
</html>
