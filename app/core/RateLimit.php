<?php

/**
 * Rate Limiter Class
 * 
 * Provides session-based rate limiting to prevent brute force attacks.
 * Stores attempt counts and timestamps in $_SESSION for each rate-limited action.
 */
class RateLimit
{
    /**
     * Check if an action is allowed based on rate limits
     * 
     * @param string $key Unique identifier for the action (e.g., 'login_192.168.1.1')
     * @param int $maxAttempts Maximum number of attempts allowed in the time window
     * @param int $windowSeconds Time window in seconds (default: 900 = 15 minutes)
     * @return bool True if action is allowed, false if rate limit exceeded
     */
    public static function check(string $key, int $maxAttempts = 5, int $windowSeconds = 900): bool
    {
        if (!isset($_SESSION['rate_limit'])) {
            $_SESSION['rate_limit'] = [];
        }

        // If no attempts recorded yet, allow
        if (!isset($_SESSION['rate_limit'][$key])) {
            return true;
        }

        $data = $_SESSION['rate_limit'][$key];
        $count = $data['count'] ?? 0;
        $firstAttempt = $data['first_attempt'] ?? time();

        // Check if time window has expired
        if (time() - $firstAttempt > $windowSeconds) {
            // Window expired, reset and allow
            self::reset($key);
            return true;
        }

        // Check if attempts exceed limit
        if ($count >= $maxAttempts) {
            return false;
        }

        return true;
    }

    /**
     * Record an attempt for rate limiting
     * 
     * @param string $key Unique identifier for the action
     * @return void
     */
    public static function recordAttempt(string $key): void
    {
        if (!isset($_SESSION['rate_limit'])) {
            $_SESSION['rate_limit'] = [];
        }

        if (!isset($_SESSION['rate_limit'][$key])) {
            $_SESSION['rate_limit'][$key] = [
                'count' => 1,
                'first_attempt' => time()
            ];
        } else {
            $_SESSION['rate_limit'][$key]['count']++;
        }
    }

    /**
     * Reset/clear rate limit attempts for a key
     * 
     * @param string $key Unique identifier for the action
     * @return void
     */
    public static function reset(string $key): void
    {
        if (isset($_SESSION['rate_limit'][$key])) {
            unset($_SESSION['rate_limit'][$key]);
        }
    }

    /**
     * Get remaining attempts before rate limit is hit
     * 
     * @param string $key Unique identifier for the action
     * @param int $maxAttempts Maximum attempts allowed
     * @return int Number of remaining attempts
     */
    public static function getRemainingAttempts(string $key, int $maxAttempts = 5): int
    {
        if (!isset($_SESSION['rate_limit'][$key])) {
            return $maxAttempts;
        }

        $count = $_SESSION['rate_limit'][$key]['count'] ?? 0;
        return max(0, $maxAttempts - $count);
    }

    /**
     * Get time remaining until rate limit resets
     * 
     * @param string $key Unique identifier for the action
     * @param int $windowSeconds Time window in seconds
     * @return int Seconds remaining until reset, or 0 if not rate limited
     */
    public static function getTimeRemaining(string $key, int $windowSeconds = 900): int
    {
        if (!isset($_SESSION['rate_limit'][$key])) {
            return 0;
        }

        $firstAttempt = $_SESSION['rate_limit'][$key]['first_attempt'] ?? time();
        $elapsed = time() - $firstAttempt;
        $remaining = $windowSeconds - $elapsed;

        return max(0, $remaining);
    }
}
