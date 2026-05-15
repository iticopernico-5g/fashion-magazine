<?php
namespace App\Utils;

class  NumberUtils {

    public static function is_valid_number($number): bool {
        return is_numeric($number);
    }

    public static function is_positive_number($number): bool {
        return self::is_valid_number($number) && $number > 0;
    }

    public static function is_in_range($number, $min, $max): bool {
        return self::is_valid_number($number) && $number >= $min && $number <= $max;
    }

    public static function format_currency($number, $currency = 'EUR'): string {
        if (!self::is_valid_number($number)) {
            return '';
        }

        $formatted_number = number_format($number, 2, ',', '.');
        return "$currency $formatted_number";
    }
}