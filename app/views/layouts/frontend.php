<!DOCTYPE html>
<html lang="en">

    <?php include PARTIALS . 'frontend/head.php'; ?>

<body class="text-center" data-page="<?= htmlspecialchars($pageKey ?? '') ?>">

    <?php
        // These are used on all pages in your current setup
        include PARTIALS . 'frontend/login-model.php';
        include PARTIALS . 'frontend/signup-model.php';
        include PARTIALS . 'frontend/sidebar.php';

        include PARTIALS . 'frontend/header.php';
    ?>

    <main>
        <?php require $viewFile; ?>
    </main>

    <?php include PARTIALS . 'frontend/footer.php'; ?>

    <div id="loaderOverlay"
        class="fixed inset-0 bg-white/80 flex items-center justify-center z-[9999] hidden">
        <div class="loader border-4 border-gray-200 border-t-brand rounded-full w-10 h-10 animate-spin"></div>
    </div>

    <script src="<?= JS_PATH ?>jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="<?= JS_PATH ?>main.js"></script>
</body>
</html>
