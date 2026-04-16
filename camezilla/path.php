<?php

function get_absolute_url(string $relative_path): string {
    $config = get_config();
    
    $base_url = rtrim($config->get('url') ?? '', '/');
    return $base_url . '/' . ltrim($relative_path, '/');
}

function get_root_dir(): string {
    $server_root = realpath($_SERVER['DOCUMENT_ROOT']);

    $config = get_config();
    $base_dir = trim($config->get('base-directory') ?? '', '/');

    return rtrim($server_root . '/' . $base_dir, '/');
}

function current_url(): string {
    $config = get_config();
    $script_name = $_SERVER['SCRIPT_NAME'] ?? '';
    $query_string = $_SERVER['QUERY_STRING'] ?? '';

    $base_dir = '/' . trim($config->get('base-directory') ?? '', '/');

    if (str_starts_with($script_name, $base_dir)) {
        $relative_path = substr($script_name, strlen($base_dir));
    } else {
        $relative_path = $script_name;
    }

    $relative_path = ltrim($relative_path, '/');

    $url = get_absolute_url($relative_path);

    if (!empty($query_string)) {
        $url .= '?' . $query_string;
    }

    return $url;
}