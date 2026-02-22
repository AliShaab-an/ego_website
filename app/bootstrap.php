<?php

    // Load Composer autoloader (PHPMailer, etc.)
    $composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
    if (file_exists($composerAutoload)) {
        require_once $composerAutoload;
    }

    require_once __DIR__ . '/config/path.php';
    require_once HELPER . 'SettingsHelper.php';
    require_once HELPER . 'UrlHelper.php';
    require_once HELPER . 'ViewHelper.php';
    require_once HELPER . 'PriceHelper.php';

    if (!function_exists('getSetting')) {
        function getSetting(string $key, $default = null) {
            return SettingsHelper::get($key, $default);
        }
    }

    if (!function_exists('url')) {
        function url(string $path = ''): string {
            return PUBLIC_URL . ltrim($path, '/');
        }
    }

    if (!function_exists('page_url')) {
        function page_url(string $page, array $params = []): string {
            $params = array_merge(['page' => $page], $params);
            return url('index.php?' . http_build_query($params));
        }
    }

    if (!function_exists('asset')) {
        function asset(string $path = ''): string {
            return UrlHelper::asset($path);
        }
    }

    if (!function_exists('redirect')) {
        function redirect(string $url): void {
            UrlHelper::redirect($url);
        }
    }

    if (!function_exists('sidebarLink')) {
        function sidebarLink($action, $currentAction, $label, $icon): void {
            ViewHelper::sidebarLink($action, $currentAction, $label, $icon);
        }
    }

    if (!function_exists('formatPrice')) {
        function formatPrice($amount): string {
            return PriceHelper::format($amount);
        }
    }

    if(defined('IS_LOCAL') && IS_LOCAL){
        // Log errors but don't display them (to prevent breaking JSON responses in API requests)
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        ini_set('log_errors', '1');
        ini_set('error_log', ROOT_PATH . 'app/logs/php_errors.log');
        error_reporting(E_ALL);

    }else{
        ini_set('display_errors', '0');
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    }

    require_once CORE . 'Session.php';
    require_once CORE . 'DB.php';
    require_once CORE . 'View.php';
    require_once CORE . 'Response.php';
    require_once CORE . 'ApiRunner.php';
    require_once CORE . 'Authorization.php';
    require_once CORE . 'CSRF.php';
    require_once CORE . 'Logger.php';

    if (file_exists(CORE . 'Auth.php'))    require_once CORE . 'Auth.php';

    // Initialize session for all requests
    $isAdminRequest = strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/') !== false;
    $redirectUrl = $isAdminRequest ? url('admin/login.php') : url('index.php');
    Session::configure(1800, $redirectUrl, $isAdminRequest);
    Session::startSession();


    spl_autoload_register(function ($class) {
    $path = [
        CORE . $class . '.php',
        MODELS . $class . '.php',
        CONT . $class . '.php',
        CONT . 'frontend/' . $class . '.php',
        CONT . 'admin/' . $class . '.php',
        HELPER . $class . '.php',
        ROOT_PATH . 'app/services/' . $class . '.php',
    ];

    foreach ($path as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});