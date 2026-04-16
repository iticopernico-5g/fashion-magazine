<?php
use Camezilla\Loggers\Logger;

function init_logger(): void {
    $config = get_config();
    $file_path = get_root_dir() . '/' . $config->get('log-file');

    if (!file_exists($file_path)) {
        file_put_contents($file_path, '');
    }

    Logger::init($file_path);
}

function set_log_file(string $file_path): void {
    Logger::set_log_file($file_path);
}

function log_debug(string $message): void {
    Logger::debug($message);
}

function log_info(string $message): void {
    Logger::info($message);
}

function log_warning(string $message): void {
    Logger::warning($message);
}

function log_error(string $message): void {
    Logger::error($message);
}