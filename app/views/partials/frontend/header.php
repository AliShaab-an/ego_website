<?php
// Determine header classes based on variant and theme
$headerVariant = $headerVariant ?? 'solid';
$headerTheme = $headerTheme ?? 'light';

$headerClasses = [];
$textColorClass = '';
$themeClass = '';

if ($headerVariant === 'transparent') {
    $headerClasses[] = 'absolute top-0 left-0 w-full z-50 bg-transparent';
    $textColorClass = 'text-white';
} else {
    $headerClasses[] = 'relative w-full bg-white shadow-sm';
    $textColorClass = 'text-gray-900';
}

// Add theme class for styling nav links and icons
if ($headerTheme === 'dark') {
    $themeClass = 'header-dark';
} else {
    $themeClass = 'header-light';
}

$headerClassString = implode(' ', $headerClasses);
?>
<header class="<?= $headerClassString ?> <?= $textColorClass ?> <?= $themeClass ?>">
    <!-- Nav -->
    <div class="font-bold py-4">
        <?php include PARTIALS . 'frontend/nav.php'; ?>
    </div>
</header>