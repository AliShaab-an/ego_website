<?php
// Reset Password page - renders section with the token from the URL
View::partial('frontend/sections/reset-password-section', [
    'token' => $token ?? '',
]);
