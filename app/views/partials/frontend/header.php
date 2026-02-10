<?php
// Determine header classes based on variant and theme
$headerVariant = $headerVariant ?? 'solid';
$headerTheme = $headerTheme ?? 'light';

$headerClasses = [];
$textColorClass = '';

if ($headerVariant === 'transparent') {
    $headerClasses[] = 'absolute top-0 left-0 w-full z-50 bg-transparent';
    $textColorClass = 'text-white';
} else {
    $headerClasses[] = 'relative w-full bg-white shadow-sm';
    $textColorClass = 'text-gray-900';
}

$headerClassString = implode(' ', $headerClasses);
?>
<header class="<?= $headerClassString ?> <?= $textColorClass ?>">
    <!-- Nav -->
    <div class="font-bold py-4">
        <?php include PARTIALS . 'frontend/nav.php'; ?>
    </div>
</header>