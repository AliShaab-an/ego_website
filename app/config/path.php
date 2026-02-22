<?php 

// ============================================================================
// MAIN CONFIGURATION - CHANGE THESE FOR HOSTING
// ============================================================================

// Set to TRUE for localhost, FALSE for production
// WARNING: Set to FALSE before deploying to production!
if (!defined('IS_LOCAL')) {
    define('IS_LOCAL', true); // Set to FALSE for production deployment
}

// Root directory (absolute path for PHP includes)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2) . '/');
}

// Base URL (for links in HTML, relative to localhost)
if (!defined('BASE_URL')) {
    // Local: '/Ego_website/'
    // Production: '/' (if root) or '/subfolder/' (if subfolder)
    define('BASE_URL', IS_LOCAL ? '/Ego_website/' : '/');
}

// Public folder path
if (!defined('PUBLIC_URL')) {
    // Local: '/Ego_website/public/'
    // Production: '/' (if root) or '/subfolder/' (if subfolder)
    define('PUBLIC_URL', IS_LOCAL ? '/Ego_website/public/' : '/');
}

// ============================================================================
// AUTO-GENERATED PATHS - DON'T CHANGE BELOW
// ============================================================================

// === Public asset URLs (used in <link>, <script>, <img>) ===
if (!defined('CSS_PATH')) {
    define('CSS_PATH', PUBLIC_URL . 'assets/css/');
}
if (!defined('JS_PATH')) {
    define('JS_PATH', PUBLIC_URL . 'assets/js/');
}
if (!defined('IMG_PATH')) {
    define('IMG_PATH', PUBLIC_URL . 'assets/images/');
}
if (!defined('ADMIN_JS_PATH')) {
    define('ADMIN_JS_PATH', PUBLIC_URL . 'admin/assets/js/');
}
if (!defined('ADMIN_CSS_PATH')) {
    define('ADMIN_CSS_PATH', PUBLIC_URL . 'admin/assets/css/');
}

// API paths
if (!defined('API_URL')) {
    define('API_URL', PUBLIC_URL . 'api/');
}
if (!defined('ADMIN_API_URL')) {
    define('ADMIN_API_URL', PUBLIC_URL . 'admin/api/');
}

// === System paths (used in PHP require/include) ===
if (!defined('CORE')) {
    define('CORE', ROOT_PATH . 'app/core/');
}
if (!defined('CONFIG')) {
    define('CONFIG', ROOT_PATH . 'app/config/');
}

if(!defined('HELPER')){
    define('HELPER', ROOT_PATH . 'app/helpers/' );
}

if(!defined('VIEWS')){
    define('VIEWS', ROOT_PATH . 'app/views/');
}

if(!defined('ADMIN_VIEWS')){
    define('ADMIN_VIEWS', VIEWS . 'admin/');
}

if (!defined('FRONTEND_VIEWS')) {
    define('FRONTEND_VIEWS', VIEWS . 'frontend/');
}

if(!defined('LAYOUTS')){
    define('LAYOUTS', VIEWS . 'layouts/');
}

if(!defined('PARTIALS')){
    define('PARTIALS', VIEWS . 'partials/');
}

if (!defined('CONT')) {
    define('CONT', ROOT_PATH . 'app/controllers/');
}

if (!defined('MODELS')) {
    define('MODELS', ROOT_PATH . 'app/models/');
}



// Function to get full URL path (useful for redirects and links)
if (!function_exists('url')) {
    function url($path = '') {
        return PUBLIC_URL . ltrim($path, '/');
    }
}

// Function to get admin asset path
if (!function_exists('admin_asset')) {
    function admin_asset($path = '') {
        return PUBLIC_URL . 'admin/assets/' . ltrim($path, '/');
    }
}

