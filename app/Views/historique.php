<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Historique</h3>
            <p class="text-muted mb-0"><?= esc($client['telephone']) ?></p>
        </div>
        <a href="<?= site_url('client/solde') ?>" class="btn btn-outline-secondary">Retour</a>
    </div>

    <?php if (empty($operations)): ?>
        <div class="alert alert-info">Aucune opération trouvée.</div>
    <?php else: ?>
        <div class="table-responsive bg-white border rounded">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Référence</th>
                        <th class="text-end">Montant</th>
                        <th class="text-end">Frais</th>
                        <th class="text-end">Commission</th>
                        <th class="text-end">Retrait inclus</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operations as $operation): ?>
                        <tr>
                            <td><?= esc($operation['dateOperation'] ?? '') ?></td>
                            <td><?= esc($operation['typeNom'] ?? '') ?></td>
                            <td><?= esc($operation['reference'] ?? '') ?></td>
                            <td class="text-end">
                                <?= number_format((float) ($operation['montant'] ?? 0), 0, ',', ' ') ?> Ar
                            </td>
                            <td class="text-end">
                                <?= number_format((float) ($operation['frais'] ?? 0), 0, ',', ' ') ?> Ar
                            </td>
                            <td class="text-end">
                                <?= number_format((float) ($operation['commissionInteroperateur'] ?? 0), 0, ',', ' ') ?> Ar
                            </td>
                            <td class="text-end">
                                <?= number_format((float) ($operation['fraisRetraitInclus'] ?? 0), 0, ',', ' ') ?> Ar
                            </td>
                            <td><?= esc($operation['etat'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
