<?php

function is_texts_enabled(): bool {
    $config = get_config();
    
    try {
        return $config->get('texts.enabled') === true;
    } catch (Exception $_) {
        return false;
    }
}

function require_texts_enabled(): void {
    if (!is_texts_enabled()) {
        throw new Exception("Texts are not enabled");
    }
}

function init_texts(): void {
    if (!is_texts_enabled()) {
        return;
    }

    $config = get_config();

    add_global_item('texts', load_texts());
    set_language($config->get('texts.default-language') ?? get_first_language());
}

function load_texts_file(): array {
    require_texts_enabled();

    $texts_file = get_config()->get('texts.path');

    if (!$texts_file) {
        throw new RuntimeException("Texts path not set in config.");
    }

    $texts_path = realpath(__DIR__ . '/../' . $texts_file);

    if (!$texts_path || !is_file($texts_path)) {
        throw new RuntimeException("Texts file not found: $texts_file");
    }

    $data = json_decode(file_get_contents($texts_path), true);

    if (!is_array($data)) {
        throw new RuntimeException("Invalid JSON in texts file.");
    }

    return $data;
}

function load_texts(): array {
    require_texts_enabled();

    $data = load_texts_file();

    $texts = [];
    foreach ($data as $key => $langs) {
        foreach ($langs as $lang => $text) {
            $texts[$lang][$key] = $text;
        }
    }

    return $texts;
}

function get_texts(): array {
    require_texts_enabled();

    if (get_global_item('texts') === null) {
        throw new RuntimeException("Texts not initialized.");
    }

    return get_global_item('texts');
}


function get_first_language(): string {
    require_texts_enabled();

    return array_key_first(get_texts());
}

function is_valid_language(string $lang): bool {
    require_texts_enabled();

    return isset(get_texts()[$lang]);
}

function set_language(string $lang): void {
    require_texts_enabled();

    $texts = get_texts();
    if (!empty($texts) && !isset($texts[$lang])) {
        throw new RuntimeException("Language '$lang' not found in texts.");
    }

    add_global_item('texts.language', $lang);
}

function get_language(): string {
    require_texts_enabled();

    if (get_global_item('texts.language') === null) {
        throw new RuntimeException("Language not set.");
    }

    return get_global_item('texts.language');
}

function t(string $key, array $placeholders = [], ?string $lang = null): string {
    require_texts_enabled();
    
    try {
        $messages = get_texts();

        if ($lang === null) {
            $lang = get_language();
        }

        if (!isset($messages[$lang][$key])) {
            throw new RuntimeException("Message not found for key '$key' in language '$lang'.");
        }

        $message = $messages[$lang][$key];

        foreach ($placeholders as $placeholder => $value) {
            $message = str_replace('{' . $placeholder . '}', $value, $message);
        }
    }
    catch (Exception $e) {
        return "Text not found: $key";
    }

    return e($message);
}