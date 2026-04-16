<?php

namespace Camezilla\Loggers;

class Logger {

    private static $log_file;

    private function __construct() {}

    public static function init(string $log_file_path) {
        self::$log_file = $log_file_path;
    }

    public static function set_log_file(string $log_file_path) {
        self::$log_file = $log_file_path;
    }

    public static function clear() {
        file_put_contents(self::$log_file, '');
    }

    public static function log(string $message, string $level = 'INFO') {
        $date = date('Y-m-d H:i:s');
        $formatted_message = "[$date] [$level] $message" . PHP_EOL;

        file_put_contents(self::$log_file, $formatted_message, FILE_APPEND | LOCK_EX);
    }

    public static function error(string $message) {
        self::log($message, 'ERROR');
    }

    public static function warning(string $message) {
        self::log($message, 'WARNING');
    }

    public static function info(string $message) {
        self::log($message, 'INFO');
    }

    public static function debug(string $message) {
        self::log($message, 'DEBUG');
    }
}