<?php

/**
 * CSRF Protection Utility
 * Provides CSRF token generation, validation, and form integration
 */

class CsrfProtection {
    private static $tokenName = 'csrf_token';
    private static $tokenLength = 32;

    /**
     * Generate a new CSRF token and store in session
     * @return string The generated token
     */
    public static function generateToken(): string {
        $token = bin2hex(random_bytes(self::$tokenLength));
        $_SESSION[self::$tokenName] = $token;
        return $token;
    }

    /**
     * Get the current CSRF token (generate if not exists)
     * @return string The current token
     */
    public static function getToken(): string {
        if (empty($_SESSION[self::$tokenName])) {
            return self::generateToken();
        }
        return $_SESSION[self::$tokenName];
    }

    /**
     * Validate a CSRF token
     * @param string $token The token to validate
     * @param bool $regenerate Whether to regenerate token after validation
     * @return bool True if valid, false otherwise
     */
    public static function validateToken(string $token, bool $regenerate = true): bool {
        $valid = !empty($_SESSION[self::$tokenName]) && 
                 hash_equals($_SESSION[self::$tokenName], $token);
        
        if ($valid && $regenerate) {
            // Regenerate token after successful validation to prevent reuse
            self::generateToken();
        }
        
        return $valid;
    }

    /**
     * Validate CSRF token without regenerating (for failed validations)
     * @param string $token The token to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateTokenNoRegen(string $token): bool {
        return !empty($_SESSION[self::$tokenName]) && 
               hash_equals($_SESSION[self::$tokenName], $token);
    }

    /**
     * Get HTML for hidden CSRF input field
     * @return string HTML input element
     */
    public static function getTokenField(): string {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::$tokenName . '" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Get CSRF token value (for AJAX requests)
     * @return string The token value
     */
    public static function getTokenValue(): string {
        return self::getToken();
    }

    /**
     * Verify CSRF token from POST data (regenerates token on success)
     * @param array $postData The POST data array
     * @return bool True if valid
     */
    public static function verifyFromPost(array $postData): bool {
        $token = $postData[self::$tokenName] ?? '';
        return self::validateToken($token);
    }

    /**
     * Verify CSRF token from POST data WITHOUT regenerating token
     * Use this when validation might fail (e.g., login with wrong password)
     * @param array $postData The POST data array
     * @return bool True if valid
     */
    public static function verifyFromPostNoRegen(array $postData): bool {
        $token = $postData[self::$tokenName] ?? '';
        return self::validateTokenNoRegen($token);
    }

    /**
     * Verify CSRF token from GET data (for links like delete actions)
     * @param array $getData The GET data array
     * @return bool True if valid
     */
    public static function verifyFromGet(array $getData): bool {
        $token = $getData[self::$tokenName] ?? '';
        return self::validateToken($token);
    }
}

/**
 * Helper function to get CSRF token field HTML
 * Usage in forms: echo csrf_field();
 */
function csrf_field(): string {
    return CsrfProtection::getTokenField();
}

/**
 * Helper function to get CSRF token value
 * Usage in AJAX: csrf_token()
 */
function csrf_token(): string {
    return CsrfProtection::getTokenValue();
}

/**
 * Verify CSRF token from request
 * Usage: if (!csrf_verify()) { die('Invalid CSRF token'); }
 */
function csrf_verify(): bool {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return CsrfProtection::verifyFromPost($_POST);
    }
    return CsrfProtection::verifyFromGet($_GET);
}