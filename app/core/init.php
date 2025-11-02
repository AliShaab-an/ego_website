<?php


// === Include global path definitions ===
require_once __DIR__ . '/../config/path.php';

// === Optional: include global helpers ===
require_once CORE . 'Session.php';
require_once CORE . 'DB.php';

// === Class Autoloader ===
spl_autoload_register(function ($class) {
    // Folders where PHP should look for class files
    $directories = [
        ROOT_PATH . 'app/controllers/',
        ROOT_PATH . 'app/controllers/frontend/',
        ROOT_PATH . 'app/controllers/backend/',
        ROOT_PATH . 'app/models/',
        ROOT_PATH . 'app/core/',
    ];

    foreach ($directories as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// === Optional: start session globally (if needed) ===
if (class_exists('Session')) {
    Session::configure(1800, '/', true);
    Session::startSession();
}
