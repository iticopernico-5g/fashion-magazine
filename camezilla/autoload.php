<?php

function load_config(string $path): array {
    if (file_exists($path)) {
        return json_decode(file_get_contents($path), true);
    }
    return [];
}

function load_namespace(string $class, string $namespace, string $source_dir): void {
    $namespace_length = strlen($namespace);

    if (strncmp($namespace, $class, $namespace_length) === 0) {
        $relative_class = substr($class, $namespace_length);
        $file_path = __DIR__ . '/../' . ltrim($source_dir, '/') . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file_path)) {
            require $file_path;
            return;
        }
    }
}

spl_autoload_register(function ($class) {
    $config = load_config(__DIR__ . '/../camezilla.config.json');

    $namespaces = $config['autoload']['namespaces'] ?? [];

    foreach ($namespaces as $namespace => $source_dir) {
        load_namespace($class, $namespace, $source_dir);
    }
});