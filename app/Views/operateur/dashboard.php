<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($operateurActuel['nom']) ?> - Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold" href="<?= site_url('operateur') ?>">Mobile Money</a>
        <div class="d-flex gap-2">
            <a href="<?= site_url('operateur') ?>" class="btn btn-outline-secondary btn-sm">Changer d'opérateur</a>
            <a href="<?= site_url('/') ?>" class="btn btn-outline-primary btn-sm">Côté client</a>
        </div>
    </div>
</nav>

<main class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Espace <?= esc($operateurActuel['nom']) ?></h1>
            <p class="text-muted mb-0">Configuration et situations propres à cet opérateur.</p>
        </div>
        <div class="text-end">
            <div class="text-muted small">Gains totaux via frais</div>
            <div class="h3 mb-0 text-success"><?= number_format((float) $totalGains, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <section class="bg-white border rounded p-3 h-100">
                <h2 class="h5 mb-3">Opérateur sélectionné</h2>
                <form method="post" action="<?= site_url('operateur/operateurs') ?>" class="row g-2 mb-3">
                    <?= csrf_field() ?>
                    <div class="col">
                        <input type="text" name="nom" class="form-control" placeholder="Nom opérateur" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th class="text-end">Préfixes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($operateurs as $operateur): ?>
                                <tr class="<?= (int) $operateur['idOperateur'] === (int) $operateurActuel['idOperateur'] ? 'table-primary' : '' ?>">
                                    <td><?= esc($operateur['nom']) ?></td>
                                    <td class="text-end">
                                        <a href="<?= site_url('operateur/' . $operateur['idOperateur']) ?>" class="btn btn-sm <?= (int) $operateur['idOperateur'] === (int) $operateurActuel['idOperateur'] ? 'btn-primary' : 'btn-outline-primary' ?>">
                                            <?= (int) $operateur['nombrePrefixes'] ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <section class="bg-white border rounded p-3 h-100">
                <h2 class="h5 mb-3">Préfixes valables</h2>
                <form method="post" action="<?= site_url('operateur/' . $operateurActuel['idOperateur'] . '/prefixes') ?>" class="row g-2 mb-3">
                    <?= csrf_field() ?>
                    <div class="col-4">
                        <input type="text" name="prefixe" maxlength="3" class="form-control" placeholder="033" required>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" value="<?= esc($operateurActuel['nom']) ?>" disabled>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Préfixe</th>
                                <th>Opérateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prefixes as $prefixe): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($prefixe['prefixe']) ?></td>
                                    <td><?= esc($prefixe['operateur']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <section class="bg-white border rounded p-3 h-100">
                <h2 class="h5 mb-3">Types d'opérations</h2>
                <form method="post" action="<?= site_url('operateur/' . $operateurActuel['idOperateur'] . '/types') ?>" class="row g-2 mb-3">
                    <?= csrf_field() ?>
                    <div class="col">
                        <input type="text" name="nom" class="form-control" placeholder="Nouveau type" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Barèmes</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($types as $type): ?>
                                <tr>
                                    <td><?= esc($type['nom']) ?></td>
                                    <td><?= (int) $type['nombreBaremes'] ?></td>
                                    <td>
                                        <form method="post" action="<?= site_url('operateur/' . $operateurActuel['idOperateur'] . '/types/' . $type['idTypeOperation'] . '/toggle') ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm <?= (int) ($type['actif'] ?? 1) === 1 ? 'btn-success' : 'btn-outline-secondary' ?>">
                                                <?= (int) ($type['actif'] ?? 1) === 1 ? 'Actif' : 'Inactif' ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <section class="bg-white border rounded p-3">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                    <h2 class="h5 mb-0">Barèmes de frais</h2>
                    <form method="post" action="<?= site_url('operateur/' . $operateurActuel['idOperateur'] . '/baremes') ?>" class="row g-2">
                        <?= csrf_field() ?>
                        <div class="col-auto">
                            <select name="idTypeOperation" class="form-select form-select-sm" required>
                                <option value="">Type</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= (int) $type['idTypeOperation'] ?>"><?= esc($type['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto"><input type="number" name="montantMin" class="form-control form-control-sm" placeholder="Min" min="1" required></div>
                        <div class="col-auto"><input type="number" name="montantMax" class="form-control form-control-sm" placeholder="Max" min="1" required></div>
                        <div class="col-auto"><input type="number" name="frais" class="form-control form-control-sm" placeholder="Frais" min="0" required></div>
                        <div class="col-auto"><button type="submit" class="btn btn-sm btn-primary">Ajouter</button></div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Min</th>
                                <th>Max</th>
                                <th>Frais</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($baremes as $bareme): ?>
                                <tr>
                                    <form method="post" action="<?= site_url('operateur/' . $operateurActuel['idOperateur'] . '/baremes/' . $bareme['idBaremeFrais']) ?>">
                                        <?= csrf_field() ?>
                                        <td><?= esc($bareme['typeNom']) ?></td>
                                        <td><input type="number" name="montantMin" class="form-control form-control-sm" value="<?= esc($bareme['montantMin']) ?>" min="1" required></td>
                                        <td><input type="number" name="montantMax" class="form-control form-control-sm" value="<?= esc($bareme['montantMax']) ?>" min="1" required></td>
                                        <td><input type="number" name="frais" class="form-control form-control-sm" value="<?= esc($bareme['frais']) ?>" min="0" required></td>
                                        <td class="text-end"><button type="submit" class="btn btn-sm btn-outline-primary">Modifier</button></td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="col-xl-5">
            <section class="bg-white border rounded p-3 mb-4">
                <h2 class="h5 mb-3">Situation des gains</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-end">Opérations</th>
                                <th class="text-end">Frais gagnés</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gains as $gain): ?>
                                <tr>
                                    <td><?= esc($gain['typeNom']) ?></td>
                                    <td class="text-end"><?= (int) $gain['nombreOperations'] ?></td>
                                    <td class="text-end"><?= number_format((float) $gain['totalFrais'], 0, ',', ' ') ?> Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="bg-white border rounded p-3">
                <h2 class="h5 mb-3">Situation des comptes clients</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th class="text-end">Solde</th>
                                <th class="text-end">Ops</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comptesClients as $client): ?>
                                <tr>
                                    <td><?= esc($client['nom']) ?></td>
                                    <td><?= esc($client['telephone']) ?></td>
                                    <td class="text-end"><?= number_format((float) $client['solde'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end"><?= (int) $client['nombreOperations'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</main>

</body>
</html>
