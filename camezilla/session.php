<?php

function start_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function add_session_item(string $key, $value): void {
    start_session();
    $_SESSION[$key] = $value;
}

function get_session_item(string $key) {
    start_session();
    return $_SESSION[$key] ?? null;
}

function remove_session_item(string $key): void {
    start_session();
    unset($_SESSION[$key]);
}