<?php

function api(string $relative_path, string $path): string {
    $config = get_config();

    $api_dir = rtrim($config->get('api') ?? 'api', '/');
    $base = get_absolute_url(
        $api_dir . '/' . ltrim($relative_path, '/')
    );
    $query = http_build_query([
        'path' => $path
    ]);

    return $base . '?' . $query;
}

