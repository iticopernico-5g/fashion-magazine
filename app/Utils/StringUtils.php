<?php
namespace App\Utils;

class StringUtils {

    public static function is_valid_text(string $text, int $max_length = 255): bool {
        return $text !== null && trim($text) !== '' && strlen($text) <= $max_length;
    }

    public static function is_valid_email(string $email, int $max_length = 255): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false && self::is_valid_text($email, $max_length);
    }

    public static function is_valid_password(string $password, int $min_length = 8): bool {
        return strlen($password) >= $min_length;
    }
}