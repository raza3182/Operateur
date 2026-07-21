<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>
<?= $titre ?>
</title>


<link 
 rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>


</head>
<body class="bg-light">
   <div class="container py-5">
        <div class="row justify-content-center">

            <div class="col-lg-6 col-md-8">

                <div class="card border-0 shadow-lg rounded-4">

                    <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                        <h2 class="mb-0">
                            Mobile Money
                        </h2>
                    </div>

                    <div class="card-body p-4">

                        <?php if (session()->getFlashdata('erreur')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= esc(session()->getFlashdata('erreur')) ?>
                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= base_url('connexion') ?>">

                            <label class="form-label fw-bold">
                                Numéro de téléphone
                            </label>

                            <div class="input-group mb-4">
                                <select
                                    class="form-select fw-semibold"
                                    name="prefixe"
                                    style="max-width:220px;"
                                    required>
                                    <option value="">📱 Choisir</option>
                                    <?php foreach($prefixes as $p): ?>
                                        <option value="<?= $p['prefixe'] ?>">
                                            🇲🇬 +261 (<?= substr($p['prefixe'], 1) ?>)
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="numero"
                                    id="numero"
                                    maxlength="7"
                                    placeholder="Ex : 1196541"
                                    required>

                            </div>

                            <button class="btn btn-success btn-lg w-100">

                                Continuer

                            </button>

                        </form>
                        <div class="text-center mt-4">
                            <a class="text-decoration-none"
                            href="<?= site_url('operateur') ?>">
                                🔐 Espace opérateur
                            </a>
                        </div>
                    </div>

                </div>

                

            </div>

        </div>
    </div>

<script>

const numero = document.getElementById("numero");

numero.addEventListener("input", function () {

    this.value = this.value.replace(/\D/g, "");

});

</script>
</body>
</html>
