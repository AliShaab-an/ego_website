<?php
/**
 * Get/Refresh CSRF Token
 * 
 * This endpoint returns a fresh CSRF token to prevent token expiration during long checkout sessions.
 * CSRF tokens have a 2-hour lifetime, and users may spend more time on the checkout page.
 */

require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    // No authentication required - anyone can get a CSRF token
    // Note: This endpoint is exempt from CSRF validation (it's a GET request)
    
    // Generate/refresh the CSRF token
    $token = CSRF::generateToken();
    
    Response::json([
        'success' => true,
        'token' => $token,
        'expires_in' => 7200 // 2 hours in seconds
    ]);
});
