<!-- app/Views/test_form.php -->
<!DOCTYPE html>
<html>
<body>
    <h2>TEST - Saisie numéro</h2>
    <form method="post" action="/client/check">
        <?= csrf_field() ?>
        <input type="text" name="telephone" placeholder="Ex: 0331234567" required>
        <button type="submit">Valider</button>
    </form>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
</body>
</html>