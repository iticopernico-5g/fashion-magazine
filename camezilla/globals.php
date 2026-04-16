<?php

function add_global_item(string $key, $value): void {
    $GLOBALS[$key] = $value;
}

function get_global_item(string $key) {
    return $GLOBALS[$key] ?? null;
}

function remove_global_item(string $key): void {
    unset($GLOBALS[$key]);
}