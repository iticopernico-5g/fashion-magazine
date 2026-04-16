<?php

namespace Camezilla\Models;

use Exception;

class Config {
    private array $data = [];

    public function __construct(array $data) {
        $this->data = $data;
    }
    
    public static function load(string $path): Config {
        if (!file_exists($path)) {
            throw new Exception("Config file not found: " . $path);
        }
        
        $decoded = json_decode(file_get_contents($path), true);

        if (!is_array($decoded)) {
            throw new Exception("Invalid config format: " . $path);
        }

        return new self($decoded);
    }

    public function get(string $path, mixed $default = null): mixed {
        $current = $this->data;
        $parts = explode('.', $path);

        foreach ($parts as $part) {
            if (is_array($current) && array_key_exists($part, $current)) {
                $current = $current[$part];
            } else if ($default !== null) {
                return $default;
            } else {
                throw new Exception("Config path not found: " . $path);
            }
        }

        return $current;
    }

    public function all(): array {
        return $this->data;
    }
}