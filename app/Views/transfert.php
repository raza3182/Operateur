<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transfert - Mobile Money</title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <style>

        body{
            background:#eef3f8;
        }

        .main-card{
            border:none;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 12px 30px rgba(0,0,0,.12);
        }

        .header-card{
            background:linear-gradient(135deg,#0d6efd,#4b8dff);
            color:white;
        }

        .balance-card{
            background:linear-gradient(135deg,#14b86d,#0d9d5a);
            color:white;
            border-radius:18px;
        }

        .balance-card h2{
            font-size:2.2rem;
            font-weight:bold;
        }

        .form-control,
        .form-check{
            border-radius:12px;
        }

        .btn-action{
            border-radius:12px;
            padding:12px;
            font-weight:600;
        }

    </style>

</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div class="card main-card">

                <!-- En-tête -->
                <div class="card-header header-card text-center py-4">

                    <h2 class="mb-1">

                        🔄 Transfert d'argent

                    </h2>

                    <small>

                        Mobile Money

                    </small>

                </div>

                <div class="card-body p-4">

                    <!-- Compte -->
                    <div class="text-center mb-4">

                        <small class="text-muted">

                            Compte expéditeur

                        </small>

                        <h4 class="fw-bold">

                            <?= esc($client['telephone']) ?>

                        </h4>

                    </div>

                    <!-- Solde -->
                    <div class="balance-card p-4 text-center mb-4">

                        <small>

                            Solde disponible

                        </small>

                        <h2 class="mt-2">

                            <?= number_format($client['solde'],0,',',' ') ?>

                            <small class="fs-5">

                                Ar

                            </small>

                        </h2>

                    </div>

                    <!-- Messages -->

                    <?php if(session()->getFlashdata('error')): ?>

                        <div class="alert alert-danger alert-dismissible fade show">

                            <?= esc(session()->getFlashdata('error')) ?>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"></button>

                        </div>

                    <?php endif; ?>

                    <?php if(session()->getFlashdata('success')): ?>

                        <div class="alert alert-success alert-dismissible fade show">

                            <?= esc(session()->getFlashdata('success')) ?>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"></button>

                        </div>

                    <?php endif; ?>

                    <!-- Formulaire -->

                    <form method="post"
                          action="<?= site_url('client/transfert') ?>">

                        <?= csrf_field() ?>

                        <!-- Destinataires -->

                        <div class="mb-4">

                            <label class="form-label fw-bold">

                                📞 Destinataire(s)

                            </label>

                            <textarea

                                class="form-control"

                                rows="4"

                                name="destinataires"

                                placeholder="0331234567&#10;0349876543"

                                required><?= esc(old('destinataires')) ?></textarea>

                            <div class="form-text">

                                Un numéro par ligne ou séparé par une virgule.

                            </div>

                        </div>

                        <!-- Montant -->

                        <div class="mb-4">

                            <label class="form-label fw-bold">

                                💰 Montant à transférer

                            </label>

                            <input

                                type="number"

                                class="form-control form-control-lg"

                                name="montant"

                                placeholder="Ex : 20 000"

                                min="1"

                                value="<?= old('montant') ?>"

                                required>

                            <div class="form-text">

                                Les frais sont calculés automatiquement.

                            </div>

                        </div>

                        <!-- Checkbox -->

                        <div class="form-check border rounded-3 bg-light p-3 mb-4">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="inclure_frais_retrait"
                                name="inclure_frais_retrait"
                                value="1"
                                <?= old('inclure_frais_retrait') ? 'checked' : '' ?>>
                            <label
                                class="form-check-label fw-semibold"
                                for="inclure_frais_retrait">
                                + Frais de retrait
                            </label>

                            <div class="form-text">
                                Appliquée seulement sur même opérateur.
                            </div>
                        </div>
                        <!-- Boutons -->
                        <div class="d-grid gap-4">

                            <button

                                type="submit"

                                class="btn btn-info text-white btn-lg btn-action">

                                🔄 Transferer
                            </button>
                            <a
                                href="<?= site_url('client/solde') ?>"
                                class="btn btn-outline-secondary btn-lg btn-action">
                                ⬅ Retour
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