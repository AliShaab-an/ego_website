<?php
    View::partial('frontend/sections/cart-section', [
        'cartItems' => $cartItems ?? [],
        'cartTotal' => $cartTotal ?? 0,
        'cartCount' => $cartCount ?? 0,
    ]);