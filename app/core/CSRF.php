<?php
/**
 * CSRF Protection System
 * Generates and validates tokens to prevent Cross-Site Request Forgery attacks
 */
class CSRF {
    
    /**
     * Token lifetime in seconds (2 hours)
     */
    private static $tokenLifetime = 7200;
    
    /**
     * Generate a new CSRF token if not exists or expired
     * @return string The CSRF token
     */
    public static function generateToken(): string {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate new token if doesn't exist or is expired
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time']) 
            || (time() - $_SESSION['csrf_token_time']) > self::$tokenLifetime) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Get the current CSRF token
     * @return string|null The current token or null if not set
     */
    public static function getToken(): ?string {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['csrf_token'] ?? null;
    }
    
    /**
     * Validate the CSRF token from request
     * @param string|null $token The token to validate (if null, checks POST/GET/JSON)
     * @return bool True if valid, false otherwise
     */
    public static function validateToken(?string $token = null): bool {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get token from parameter or request
        if ($token === null) {
            // Check form data (URL-encoded)
            $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? null;
            
            // Check custom header
            if ($token === null) {
                $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            }
            
            // Check JSON body (for application/json requests)
            if ($token === null) {
                $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
                if (strpos($contentType, 'application/json') !== false) {
                    $jsonBody = file_get_contents('php://input');
                    if ($jsonBody) {
                        $data = json_decode($jsonBody, true);
                        $token = $data['csrf_token'] ?? null;
                    }
                }
            }
        }
        
        // Check if token exists in session
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
            return false;
        }
        
        // Check if token is expired
        if ((time() - $_SESSION['csrf_token_time']) > self::$tokenLifetime) {
            return false;
        }
        
        // Check if tokens match (timing-safe comparison)
        if ($token === null || !hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Regenerate the CSRF token (call after successful form submission)
     */
    public static function regenerateToken(): void {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    
    /**
     * Generate a hidden input field with CSRF token
     * @return string HTML input field
     */
    public static function getHiddenInput(): string {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
    
    /**
     * Get token for JavaScript/AJAX requests
     * @return array Array with token key and value
     */
    public static function getTokenForAjax(): array {
        return [
            'name' => 'csrf_token',
            'value' => self::generateToken()
        ];
    }
    
    /**
     * Throw exception if CSRF validation fails
     * @throws Exception
     */
    public static function requireValidToken(): void {
        if (!self::validateToken()) {
            // Log CSRF failure for debugging
            error_log("CSRF validation failed - Token: " . ($_POST['csrf_token'] ?? $_GET['csrf_token'] ?? 'missing') . 
                      " | Session Token: " . ($_SESSION['csrf_token'] ?? 'missing') . 
                      " | Session Time: " . ($_SESSION['csrf_token_time'] ?? 'missing') . 
                      " | Current Time: " . time());
            
            throw new Exception('Invalid or expired security token. Your session may have expired. Please refresh the page and try again.');
        }
    }
}
