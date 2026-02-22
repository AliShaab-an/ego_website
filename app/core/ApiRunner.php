<?php 

    class ApiRunner{

        public static function run(callable $fn):void{
            try{
                // Validate CSRF token for all POST, PUT, DELETE requests
                if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
                    // Skip CSRF validation for login endpoints (they don't have tokens yet)
                    $skipCsrfPaths = [
                        '/api/login-user.php',
                        '/api/register-user.php',
                        '/api/forgot-password.php',
                        '/admin/api/login-admin.php'
                    ];
                    
                    $currentPath = $_SERVER['PHP_SELF'] ?? '';
                    $shouldSkip = false;
                    
                    foreach ($skipCsrfPaths as $path) {
                        if (strpos($currentPath, $path) !== false) {
                            $shouldSkip = true;
                            break;
                        }
                    }
                    
                    if (!$shouldSkip) {
                        CSRF::requireValidToken();
                    }
                }
                
                $fn();
            }catch(Throwable $e){
                error_log("API Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                Response::error($e->getMessage(), $e->getTraceAsString());
            }
        }
    }