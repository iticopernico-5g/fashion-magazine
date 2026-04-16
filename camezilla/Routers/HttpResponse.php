<?php 

namespace Camezilla\Routers;

final class HttpResponse
{
    private function __construct() {}

    private static function send(int $code, $body = null): void {
        http_response_code($code);

        if (is_array($body)) {
            header('Content-Type: application/json');
            echo json_encode($body);
        } elseif ($body !== null) {
            header('Content-Type: text/plain');
            echo $body;
        }

        exit;
    }

    public static function ok($data = null): void {
        self::send(200, $data);
    }

    public static function created($data = null): void {
        self::send(201, $data);
    }

    public static function no_content(): void {
        self::send(204);
    }


    public static function bad_request(string $message = 'Bad Request'): void {
        self::send(400, ['error' => $message]);
    }

    public static function unauthorized(string $message = 'Unauthorized'): void {
        self::send(401, ['error' => $message]);
    }

    public static function forbidden(string $message = 'Forbidden'): void {
        self::send(403, ['error' => $message]);
    }

    public static function not_found(string $message = 'Not Found'): void {
        self::send(404, ['error' => $message]);
    }


    public static function internal_server_error(string $message = 'Internal Server Error'): void {
        self::send(500, ['error' => $message]);
    }
}