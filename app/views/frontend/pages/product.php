<?php
    // Product detail page - expects $product data from controller
    View::partial('frontend/components/product-card', ['product' => $product ?? []]);
?>