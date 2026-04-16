<?php 

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function hash_password(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verify_password(string $password, string $hash): bool {
    return password_verify($password, $hash);
}

function is_valid_password(string $password): bool {
    return strlen($password) >= 10;
}

function base_64_url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}