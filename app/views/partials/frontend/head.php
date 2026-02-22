<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= CSRF::generateToken() ?>">

    <link rel="icon" type="image/png" href="<?= getSetting('favicon') ? url(getSetting('favicon')) : IMG_PATH . 'egologo.png' ?>">
    
    <!-- Dynamic Theme Colors from Settings for Tailwind CSS -->
    <style>
        :root {
            /* Standard CSS variables for use in regular CSS */
            --primary-color: <?= getSetting('primary_color', '#b7926f') ?>;
            --secondary-color: <?= getSetting('secondary_color', '#9e7e59') ?>;
            --accent-color: <?= getSetting('accent_color', '#88663d') ?>;
            
            /* Tailwind brand color (uses primary color) */
            --color-brand: <?= getSetting('primary_color', 'rgb(183, 146, 103)') ?>;
            --color-brand-secondary: <?= getSetting('secondary_color', 'rgb(158, 126, 89)') ?>;
            --color-brand-accent: <?= getSetting('accent_color', 'rgb(136, 102, 61)') ?>;
            
            <?php if ($primaryFont = getSetting('primary_font')): ?>
            --primary-font: <?= htmlspecialchars($primaryFont) ?>;
            <?php endif; ?>
            <?php if ($secondaryFont = getSetting('secondary_font')): ?>
            --secondary-font: <?= htmlspecialchars($secondaryFont) ?>;
            <?php endif; ?>
        }
    </style>
    
    <link rel="stylesheet" href="<?= CSS_PATH ?>style.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>

    <!-- Optional Swiper (you used it on home). Keep globally for now -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <title><?= htmlspecialchars(getSetting('meta_title', 'Ego Luxury')) ?></title>
    <meta name="description" content="<?= htmlspecialchars(getSetting('meta_description', '')) ?>">
    <meta name="keywords" content="<?= htmlspecialchars(getSetting('meta_keywords', '')) ?>">

    <?php if ($gaId = getSetting('google_analytics_id')): ?>
        <!-- Google Analytics GA4 -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($gaId) ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?= htmlspecialchars($gaId) ?>');
        </script>
    <?php endif; ?>
</head>