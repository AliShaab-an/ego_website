<?php 

// Root directory (absolute path for PHP includes)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2) . '/');
}

// Base URL (for links in HTML, relative to localhost)
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Ego_website/');
}

// === Public asset URLs (used in <link>, <script>, <img>) ===
if (!defined('CSS_PATH')) {
    define('CSS_PATH', BASE_URL . 'public/assets/css/');
}
if (!defined('JS_PATH')) {
    define('JS_PATH', BASE_URL . 'public/assets/js/');
}
if (!defined('IMG_PATH')) {
    define('IMG_PATH', BASE_URL . 'public/assets/images/');
}
if (!defined('ADMIN_JS_PATH')) {
    define('ADMIN_JS_PATH', BASE_URL . 'public/admin/assets/js/');
}

// === System paths (used in PHP require/include) ===
if (!defined('CORE')) {
    define('CORE', ROOT_PATH . 'app/core/');
}
if (!defined('CONFIG')) {
    define('CONFIG', ROOT_PATH . 'app/config/');
}
if (!defined('BACKEND_VIEWS')) {
    define('BACKEND_VIEWS', ROOT_PATH . 'app/views/backend/');
}

if (!defined('CONT')) {
    define('CONT', ROOT_PATH . 'app/controllers/');
}

if (!defined('MODELS')) {
    define('MODELS', ROOT_PATH . 'app/models/');
}
