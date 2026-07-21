<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($operateurActuel['nom']) ?> - Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body class="bg-light operator-dashboard">

<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold" href="<?= site_url('operateur') ?>">Mobile Money</a>
        <div class="d-flex gap-2">
            <a href="<?= site_url('operateur') ?>" class="btn btn-outline-secondary btn-sm">Changer d'opérateur</a>
            <a href="<?= site_url('/') ?>" class="btn btn-outline-primary btn-sm">Côté client</a>
        </div>
    </div>
</nav>

<main class="container-fluid py-4 py-lg-5">
    <div class="row g-4 align-items-start">
        <aside class="col-lg-3 col-xxl-2">
            <div class="dashboard-sidebar sticky-lg-top">
                <a class="sidebar-brand" href="#operateur-selectionne">
                    <span class="sidebar-brand-icon">M</span>
                    <span>Mobile Money<small>Administration</small></span>
                </a>
                <p class="sidebar-label">Navigation</p>
                <nav class="nav flex-column dashboard-nav" aria-label="Navigation du tableau de bord">
                    <a class="nav-link active" href="#operateur-selectionne">Opérateur sélectionné</a>
                    <a class="nav-link" href="#prefixes">Préfixes valables</a>
                    <a class="nav-link" href="#types-operations">Types d’opérations</a>
                    <a class="nav-link" href="#baremes">Barèmes de frais</a>
                    <a class="nav-link" href="#gains">Situation des gains</a>
                    <a class="nav-link" href="#commission">Commission interopérateur</a>
                    <a class="nav-link" href="#montants-envoyer">Montants à envoyer</a>
                    <a class="nav-link" href="#comptes-clients">Comptes clients</a>
                </nav>

                <div class="selected-operator">
                    <span>Opérateur actif</span>
                    <strong><?= esc($operateurActuel['nom']) ?></strong>
                </div>
            </div>
        </aside>

        <div class="col-lg-9 col-xxl-10">
            <div class="dashboard-header mb-4">
                <div>
                    <p class="eyebrow mb-2">Tableau de bord</p>
                    <h1 class="h2 mb-1">Espace <?= esc($operateurActuel['nom']) ?></h1>
                    <p class="text-muted mb-0">Configuration et suivi des activités de votre opérateur.</p>
                </div>
                <div class="dashboard-total">
                    <span>Gains totaux via frais</span>
                    <strong><?= number_format((float) $totalGains, 0, ',', ' ') ?> <small>Ar</small></strong>
                </div>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger shadow-sm border-0"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success shadow-sm border-0"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

    <div class="row g-4 mb-4" id="configuration">
        <div class="col-lg-4 dashboard-primary-panel" data-dashboard-panel>
            <section class="app-card p-3 p-xl-4 h-100" id="operateur-selectionne">
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

        <div class="col-lg-4 dashboard-primary-panel" data-dashboard-panel>
            <section class="app-card p-3 p-xl-4 h-100" id="prefixes">
                <h2 class="h5 mb-3">Préfixes valables</h2>
                <p class="small text-muted">Ajoutez les préfixes des autres opérateurs pour autoriser les transferts interopérateurs.</p>
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

        <div class="col-lg-4 dashboard-primary-panel" data-dashboard-panel>
            <section class="app-card p-3 p-xl-4 h-100" id="types-operations">
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
        <div class="col-xl-7 dashboard-primary-panel" data-dashboard-panel>
            <section class="app-card p-3 p-xl-4" id="baremes">
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
                                    <?php $formId = 'bareme-' . (int) $bareme['idBaremeFrais']; ?>
                                    <td><?= esc($bareme['typeNom']) ?></td>
                                    <td><input form="<?= $formId ?>" type="number" name="montantMin" class="form-control form-control-sm" value="<?= esc($bareme['montantMin']) ?>" min="1" required></td>
                                    <td><input form="<?= $formId ?>" type="number" name="montantMax" class="form-control form-control-sm" value="<?= esc($bareme['montantMax']) ?>" min="1" required></td>
                                    <td><input form="<?= $formId ?>" type="number" name="frais" class="form-control form-control-sm" value="<?= esc($bareme['frais']) ?>" min="0" required></td>
                                    <td class="text-end">
                                        <form id="<?= $formId ?>" method="post" action="<?= site_url('operateur/' . $operateurActuel['idOperateur'] . '/baremes/' . $bareme['idBaremeFrais']) ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Modifier</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="col-xl-5 dashboard-secondary-column">
            <section class="app-card p-3 p-xl-4 mb-4" id="gains" data-dashboard-panel>
                <h2 class="h5 mb-3">Situation des gains de l’opérateur</h2>
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

            <section class="app-card p-3 p-xl-4 mb-4" id="commission" data-dashboard-panel>
                <h2 class="h5 mb-3">Commission interopérateur</h2>
                <p class="small text-muted">Pourcentage ajouté au transfert lorsque les deux préfixes appartiennent à des opérateurs différents.</p>
                <form method="post" action="<?= site_url('operateur/' . $operateurActuel['idOperateur'] . '/commission-interoperateur') ?>" class="input-group mb-3">
                    <?= csrf_field() ?>
                    <input type="number" class="form-control" name="pourcentage" min="0" max="100" step="0.01" value="<?= esc($commissionInteroperateur) ?>" required>
                    <span class="input-group-text">%</span>
                    <button class="btn btn-primary">Enregistrer</button>
                </form>
                <div class="d-flex justify-content-between border-top pt-3"><span>Commissions reçues des autres opérateurs</span><strong class="text-success"><?= number_format((float) $commissionsAutresOperateurs, 0, ',', ' ') ?> Ar</strong></div>
            </section>

            <section class="app-card p-3 p-xl-4 mb-4" id="montants-envoyer" data-dashboard-panel>
                <h2 class="h5 mb-3">Montants à envoyer aux autres opérateurs</h2>
                <?php if (empty($montantsAEnvoyer)): ?>
                    <p class="text-muted mb-0">Aucun transfert interopérateur sortant.</p>
                <?php else: ?>
                    <div class="table-responsive"><table class="table table-sm align-middle mb-0"><thead><tr><th>Opérateur destinataire</th><th class="text-end">Transferts</th><th class="text-end">Montant à envoyer</th></tr></thead><tbody><?php foreach ($montantsAEnvoyer as $montantAEnvoyer): ?><tr><td><?= esc($montantAEnvoyer['operateur']) ?></td><td class="text-end"><?= (int) $montantAEnvoyer['nombreTransferts'] ?></td><td class="text-end fw-semibold"><?= number_format((float) $montantAEnvoyer['montantAEnvoyer'], 0, ',', ' ') ?> Ar</td></tr><?php endforeach; ?></tbody></table></div>
                <?php endif; ?>
            </section>

            <section class="app-card p-3 p-xl-4" id="comptes-clients" data-dashboard-panel>
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
        </div>
    </div>
</main>

<script>
    (() => {
        const navigation = document.querySelector('.dashboard-nav');
        const links = [...navigation.querySelectorAll('a[href^="#"]')];
        const panels = [...document.querySelectorAll('[data-dashboard-panel]')];
        const primaryPanels = [...document.querySelectorAll('.dashboard-primary-panel')];
        const secondaryColumn = document.querySelector('.dashboard-secondary-column');

        function afficherPanneau(hash) {
            const cible = document.querySelector(hash);
            const panneau = cible?.closest('[data-dashboard-panel]');

            if (!panneau) {
                return;
            }

            panels.forEach((element) => {
                element.hidden = element !== panneau;
            });
            primaryPanels.forEach((element) => {
                element.classList.replace('col-lg-12', 'col-lg-4');
                element.classList.replace('col-xl-12', 'col-xl-7');
            });
            secondaryColumn.classList.replace('col-xl-12', 'col-xl-5');

            if (panneau.classList.contains('dashboard-primary-panel')) {
                if (panneau.classList.contains('col-lg-4')) {
                    panneau.classList.replace('col-lg-4', 'col-lg-12');
                }
                if (panneau.classList.contains('col-xl-7')) {
                    panneau.classList.replace('col-xl-7', 'col-xl-12');
                }
            } else {
                secondaryColumn.classList.replace('col-xl-5', 'col-xl-12');
            }
            links.forEach((link) => {
                link.classList.toggle('active', link.getAttribute('href') === hash);
            });
        }

        navigation.addEventListener('click', (event) => {
            const link = event.target.closest('a[href^="#"]');
            if (!link) {
                return;
            }

            event.preventDefault();
            const hash = link.getAttribute('href');
            history.replaceState(null, '', hash);
            afficherPanneau(hash);
        });

        afficherPanneau(links.some((link) => link.getAttribute('href') === window.location.hash)
            ? window.location.hash
            : '#operateur-selectionne');
    })();
</script>

</body>
</html>
