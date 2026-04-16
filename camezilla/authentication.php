<?php

use Camezilla\Routers\HttpResponse;

function is_authentication_enabled(): bool {
    $config = get_config();
    return $config->get('authentication.enabled') === true;
}

function require_authentication_enabled(): void {
    if (!is_authentication_enabled()) {
        throw new Exception("Authentication is not enabled");
    }
}

function get_secret_key(): string {
    require_authentication_enabled();

    $config = get_config();
    return $config->get('authentication.secret-key');
}

function generate_jwt(array $payload, int $expire_seconds = 3600): string {
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];

    $payload['iat'] = time();
    $payload['exp'] = time() + $expire_seconds;

    $h = base_64_url_encode(json_encode($header));
    $p = base_64_url_encode(json_encode($payload));

    return $h . "." . $p . "." .
        base_64_url_encode(hash_hmac('sha256', "$h.$p", get_secret_key(), true));
}

function verify_jwt(string $jwt): ?array {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return null;

    [$h, $p, $s] = $parts;

    $expected = base_64_url_encode(
        hash_hmac('sha256', "$h.$p", get_secret_key(), true)
    );

    if (!hash_equals($expected, $s)) return null;

    $payload = json_decode(base64_decode(strtr($p, '-_', '+/')), true);

    if (!$payload || ($payload['exp'] ?? 0) < time()) return null;

    return $payload;
}

function authenticate_user(int $user_id, string $email): void {
    require_authentication_enabled();

    start_session();
    session_regenerate_id(true);

    add_session_item('authentication.user-id', $user_id);
    add_session_item('authentication.email', $email);
}

function remove_user_authentication(): void {
    require_authentication_enabled();
    
    start_session();
    remove_session_item('authentication.user-id');
    remove_session_item('authentication.email');
    session_destroy();
}

function is_user_authenticated(): bool {
    require_authentication_enabled();

    start_session();
    return get_session_item('authentication.user-id') !== null && get_session_item('authentication.email') !== null;
}

function get_authenticated_user_id(): ?int {
    require_authentication_enabled();

    start_session();
    return get_session_item('authentication.user-id');
}

function get_authenticated_email(): ?string {
    require_authentication_enabled();

    start_session();
    return get_session_item('authentication.email');
}

function require_user_authentication(): void {
    require_authentication_enabled();
    
    if (!is_user_authenticated()) {
        redirect_to_login_page();
    }
}

function redirect_to_login_page(): void {
    require_authentication_enabled();

    $config = get_config();
    header("Location: " . page($config->get('authentication.login-page')));
    exit();
}

function get_bearer_token(): ?string {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

    if (!$header && function_exists('getallheaders')) {
        $headers = getallheaders();
        $header = $headers['Authorization'] ?? null;
    }

    if (!$header) return null;

    if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
        return $matches[1];
    }

    return null;
}

function require_api_authentication(): void {
    if (!is_authentication_enabled()) {
        return;
    }

    $token = get_bearer_token();
    if (!$token || !verify_jwt($token)) {
        HttpResponse::unauthorized();
        exit;
    }
}

function get_authenticated_api_user(): ?array {
    $token = get_bearer_token();
    if (!$token) return null;

    return verify_jwt($token);
}