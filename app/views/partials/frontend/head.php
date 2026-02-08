<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="assets/images/egologo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>

    <!-- Optional Swiper (you used it on home). Keep globally for now -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <title><?= htmlspecialchars($metaTitle ?? getSetting('meta_title') ?? 'Ego Clothing') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? getSetting('meta_description') ?? '') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($metaKeywords ?? getSetting('meta_keywords') ?? '') ?>">

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