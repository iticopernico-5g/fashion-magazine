<?php

function resource(string $relative_path): string {
    $config = get_config();

    $resources_dir = rtrim($config->get('resources') ?? 'resources', '/');
    return get_absolute_url($resources_dir . '/' . ltrim($relative_path, '/'));
}