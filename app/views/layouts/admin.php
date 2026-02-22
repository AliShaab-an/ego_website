<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="<?= CSRF::generateToken() ?>">

        <link rel="icon" type="image/png" href="<?= IMG_PATH ?>egologo.png">
        <link rel="stylesheet" href="<?= CSS_PATH ?>/style.css">
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
        <script src="<?= ADMIN_JS_PATH ?>chart.umd.min.js"></script>
        <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>

        <title><?= htmlspecialchars($pageTitle ?? 'Admin Panel - Ego Clothing') ?></title>
    </head>

    <body class="flex bg-white h-screen overflow-hidden" data-page="<?= htmlspecialchars($action ?? '') ?>">
        <div class="h-screen flex w-full">

            <?php require VIEWS . 'partials/admin/sidebar.php'; ?>

            <main class="flex-1 h-full overflow-y-auto bg-gray-50">
                <div class="px-8 py-6">
                    <?php require $viewFile; ?>
                </div>
            </main>

        </div>

        <script src="../assets/js/jquery-3.7.1.min.js"></script>
        <script type="module" src="assets/js/main.js"></script>
    </body>
</html>
