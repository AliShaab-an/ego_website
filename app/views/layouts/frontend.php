<!DOCTYPE html>
<html lang="en">

    <?php include PARTIALS . 'frontend/head.php'; ?>

<body class="text-center page-<?= htmlspecialchars($pageKey ?? '') ?>" data-page="<?= htmlspecialchars($pageKey ?? '') ?>"<?php if (isset($categoryId)): ?> data-category-id="<?= htmlspecialchars($categoryId) ?>"<?php endif; ?>>

    <?php
        // These are used on all pages in your current setup
        include PARTIALS . 'frontend/login-model.php';
        include PARTIALS . 'frontend/signup-model.php';
        include PARTIALS . 'frontend/sidebar.php';

        // Header (nav bar)
        include PARTIALS . 'frontend/header.php';
        
        // Hero section (conditional)
        if (!empty($hasHero) && $hasHero === true):
    ?>
    <!-- Hero Section -->
    <section class="relative w-screen h-screen min-h-screen overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?= htmlspecialchars($heroImage ?? '') ?>'); "></div>
        
        <!-- Optional dark overlay for readability -->
        <div class="absolute inset-0 bg-black/30"></div>
        
        <!-- Bottom white gradient fade -->
        <div class="absolute inset-x-0 bottom-0 h-44 bg-gradient-to-t from-white to-white/0"></div>
        
        <!-- Center content -->
        <div class="relative z-10 h-full flex flex-col items-center justify-center text-white text-center px-4 pt-16 md:pt-20">
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-normal drop-shadow-lg">
                <?= htmlspecialchars($heroTitle ?? '') ?>
            </h1>
            <?php if (!empty($heroSubtitle)): ?>
                <p class="mt-2 text-sm md:text-base lg:text-lg opacity-90 drop-shadow-md">
                    <?= $heroSubtitle ?>
                </p>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

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
