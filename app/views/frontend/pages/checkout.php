<?php
    View::partial('frontend/sections/checkout-section', [
        'cartItems' => $cartItems ?? [],
        'cartTotal' => $cartTotal ?? 0,
        'cartCount' => $cartCount ?? 0,
        'userName' => $userName ?? '',
        'userEmail' => $userEmail ?? '',
        'userPhone' => $userPhone ?? '',
        'paymentMethods' => $paymentMethods ?? [],
    ]);