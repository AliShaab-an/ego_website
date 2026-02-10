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
