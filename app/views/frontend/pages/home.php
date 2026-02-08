<?php

View::partial('frontend/sections/collections', [
    'categoriesWithProducts' => $categoriesWithProducts ?? [],
]);

View::partial('frontend/sections/shop-the-look', [
    'shopTheLookProducts' => $shopTheLookProducts ?? [],
]);

View::partial('frontend/sections/top-products', [
    'topProducts' => $topProducts ?? [],
]);

View::partial('frontend/sections/new-products', [
    'newProducts' => $newProducts ?? [],
]);

View::partial('frontend/sections/home-contact');

// include FRONTEND_VIEWS . '/partials/collections.php';
// include FRONTEND_VIEWS . '/partials/shopTheLook.php';
// include FRONTEND_VIEWS . '/partials/topProducts.php';
// include FRONTEND_VIEWS . '/partials/newProducts.php';
// include FRONTEND_VIEWS . '/partials/homeContact.php';
