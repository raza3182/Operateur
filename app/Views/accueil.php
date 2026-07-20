<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>
<?= $titre ?>
</title>


<link 
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">
                Mobile Money
                </h3>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('erreur')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= esc(session()->getFlashdata('erreur')) ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="<?= base_url('connexion') ?>">
                    <div class="mb-3">

                    <label class="form-label">

                    Choisir votre opérateur

                    </label>


                    <select 
                    class="form-select"
                    name="prefixe">


                    <option value="">
                    -- Choisir --
                    </option>
                    <?php foreach($prefixes as $p): ?>
                    <option value="<?= $p['prefixe'] ?>">
                    <?= $p['prefixe'] ?>

                    -
                    <?= $p['operateur'] ?>
                    </option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">

                    Numéro téléphone

                    </label>
                    <input 
                    type="text"
                    name="numero"
                    class="form-control"
                    placeholder="Ex: 11 965 41">
                    </div>
                    <button 
                    class="btn btn-success w-100">
                    Continuer
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
