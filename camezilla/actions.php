<?php

function action(string $relative_path, string $path, string $redirect, array $params = []): string {
    $config = get_config();

    $actions_dir = rtrim($config->get('actions') ?? 'actions', '/');
    $base = get_absolute_url(
        $actions_dir . '/' . ltrim($relative_path, '/')
    );
    $query = http_build_query([
        'path' => $path,
        'redirect' => $redirect,
        'back' => current_url()
    ] + $params);
    
    return $base . '?' . $query;
}

function set_action_success(string $success) {
    start_session();
    add_session_item('action.success', $success);
}

function get_action_success(?bool $clear = false): ?string {
    start_session();

    $success = get_session_item('action.success');
    if ($clear) {
        clear_action_success();
    }

    return $success;
}

function clear_action_success() {
    start_session();
    remove_session_item('action.success');
}

function set_action_error(string $error) {
    start_session();
    add_session_item('action.error', $error);
}

function get_action_error(?bool $clear = false): ?string {
    start_session();

    $error = get_session_item('action.error');
    if ($clear) {
        clear_action_error();
    }
    
    return $error;
}

function clear_action_error() {
    start_session();
    remove_session_item('action.error');
}