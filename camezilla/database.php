<?php
use Camezilla\Models\Database;

function is_database_enabled(): bool {
    $config = get_config();

    try {
        return $config->get('database.enabled') === true;
    } catch (Exception $_) {
        return false;
    }
}

function require_database_enabled(): void {
    if (!is_database_enabled()) {
        throw new Exception("Database is not enabled");
    }
}

function init_database(): void {
    if (!is_database_enabled()) {
        return;
    }

    $config = get_config();

    $host = $config->get('database.host');
    $user = $config->get('database.user');
    $password = $config->get('database.password');
    $name = $config->get('database.name');

    add_global_item('database', new Database($host, $user, $password, $name));
}

function get_database(): Database {
    require_database_enabled();

    if (get_global_item('database') === null) {
        throw new Exception("Database not initialized");
    }

    return get_global_item('database');
}

function connect_database(): void {
    require_database_enabled();

    $database = get_database();

    if ($database->is_connected()) {
        return;
    }
    
    try {
        $database->connect();
    } catch (Exception $_) {
        redirect_to_error_page();
    }
}

function redirect_to_error_page(): void {
    require_database_enabled();

    $config = get_config();

    header("Location: " . page($config->get('database.error-page')));
    exit();
}