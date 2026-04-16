<?php

function page(string $relative_path, array $params = []): string {
    $config = get_config();

    $pages_dir = rtrim($config->get('pages') ?? 'pages', '/');
    $url = get_absolute_url($pages_dir . '/' . ltrim($relative_path, '/'));
    
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    return $url;
}