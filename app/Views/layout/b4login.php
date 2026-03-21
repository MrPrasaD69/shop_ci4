<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop App</title>

    <!-- JQuery -->
    <script src="<?= base_url('assets/js/jquery.js') ?>" crossorigin="anonymous"></script>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<style>
    @font-face {
        font-family: 'FiraCode';
        src: url('<?= base_url("/assets/font/FiraCode-Regular.ttf") ?>') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'UbuntuCondensed';
        src: url('<?= base_url("/assets/font/UbuntuCondensed-Regular.ttf") ?>') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    body {
        font-family: 'UbuntuCondensed', sans-serif;
    }
</style>
<body>
    <main class="container mt-4">
        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>