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
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?= site_url('operateur') ?>">Mobile Money</a>
        <a href="<?= site_url('/') ?>" class="btn btn-outline-primary btn-sm">Côté client</a>
    </div>
</nav>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <h1 class="h3 mb-1">Choisir un opérateur</h1>
                    <p class="text-muted mb-0">Chaque opérateur ouvre son propre espace de configuration.</p>
                </div>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <section class="bg-white border rounded p-3 mb-4">
                <form method="post" action="<?= site_url('operateur/operateurs') ?>" class="row g-2 align-items-end">
                    <?= csrf_field() ?>
                    <div class="col-md">
                        <label for="nom" class="form-label">Nouvel opérateur</label>
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="Ex: MVola" required>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </div>
                </form>
            </section>

            <div class="row g-3">
                <?php foreach ($operateurs as $operateur): ?>
                    <div class="col-md-6 col-xl-4">
                        <a href="<?= site_url('operateur/' . $operateur['idOperateur']) ?>" class="text-decoration-none text-reset">
                            <section class="bg-white border rounded p-3 h-100">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <h2 class="h5 mb-1"><?= esc($operateur['nom']) ?></h2>
                                        <p class="text-muted mb-0">
                                            <?= (int) $operateur['nombrePrefixes'] ?> préfixe(s)
                                        </p>
                                    </div>
                                    <span class="btn btn-sm btn-outline-primary align-self-start">Ouvrir</span>
                                </div>
                            </section>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

</body>
</html>
