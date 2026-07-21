<!DOCTYPE html>
<html lang="fr">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Historique - Mobile Money</title>

<link rel="stylesheet"
      href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-xl-11">

            <div class="card border-0 shadow-lg rounded-4">

                <!-- En-tête -->
                <div class="card-header bg-primary text-white py-4 rounded-top-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h3 class="mb-1">
                                📜 Historique des opérations
                            </h3>

                            <small>
                                <?= esc($client['telephone']) ?>
                            </small>

                        </div>

                        <a href="<?= site_url('client/solde') ?>"
                           class="btn btn-light">

                            ⬅ Retour

                        </a>

                    </div>

                </div>

                <div class="card-body">

                    <?php if (empty($operations)): ?>

                        <div class="alert alert-info text-center">

                            Aucune opération trouvée.

                        </div>

                    <?php else: ?>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-dark">

                                <tr>

                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Référence</th>

                                    <th class="text-end">
                                        Montant
                                    </th>

                                    <th class="text-end">
                                        Frais
                                    </th>

                                    <th class="text-end">
                                        Commission
                                    </th>

                                    <th class="text-end">
                                        Retrait inclus
                                    </th>

                                    <th class="text-center">
                                        État
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php foreach ($operations as $operation): ?>

                                <tr>

                                    <td>
                                        <?= esc($operation['dateOperation']) ?>
                                    </td>

                                    <td>

                                        <?php
                                        $type = strtoupper($operation['typeNom']);

                                        switch ($type) {
                                            case 'DEPOT':
                                                echo "💰 Dépôt";
                                                break;

                                            case 'RETRAIT':
                                                echo "💵 Retrait";
                                                break;

                                            case 'TRANSFERT':
                                                echo "🔄 Transfert";
                                                break;

                                            default:
                                                echo esc($operation['typeNom']);
                                        }
                                        ?>

                                    </td>

                                    <td class="fw-semibold">
                                        <?= esc($operation['reference']) ?>
                                    </td>

                                    <td class="text-end fw-bold">

                                        <?= number_format($operation['montant'],0,',',' ') ?>

                                        Ar

                                    </td>

                                    <td class="text-end">

                                        <?= number_format($operation['frais'],0,',',' ') ?>

                                        Ar

                                    </td>

                                    <td class="text-end text-primary fw-semibold">

                                        <?= number_format($operation['commissionInteroperateur'],0,',',' ') ?>

                                        Ar

                                    </td>

                                    <td class="text-end text-warning fw-semibold">

                                        <?= number_format($operation['fraisRetraitInclus'],0,',',' ') ?>

                                        Ar

                                    </td>

                                    <td class="text-center">

                                        <?php if (($operation['etat']) == 'SUCCES'): ?>

                                            <span class="badge bg-success">
                                                ✔ Succès
                                            </span>

                                        <?php elseif (($operation['etat']) == 'EN_ATTENTE'): ?>

                                            <span class="badge bg-warning text-dark">
                                                ⏳ En attente
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-danger">
                                                ✖ Échec
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>