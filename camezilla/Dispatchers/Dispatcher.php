<?php

namespace Camezilla\Dispatchers;

class Endpoint {

    public string $method;
    public string $path;
    public $action;

    public function __construct(string $method, string $path, callable $action) {
        $this->method = $method;
        $this->path = $path;
        $this->action = $action;
    }
}

class Dispatcher {

    private $endpoints = [];
    private ?string $not_found_page;
    private ?string $internal_server_error_page;

    public function __construct(?string $not_found_page = null, ?string $internal_server_error_page = null) {
        $this->not_found_page = $not_found_page;
        $this->internal_server_error_page = $internal_server_error_page;
    }

    public function get(string $path, callable $action) {
        $this->add_endpoint('GET', $path, $action);
    }

    public function post(string $path, callable $action) {
        $this->add_endpoint('POST', $path, $action);
    }

    private function add_endpoint( string $method, string $path, callable $action) {
        $this->endpoints[] = new Endpoint($method, $path, $action);
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_GET['path'] ?? null;

        $params = $this->get_params($method);

        if (!$path) {
            $this->not_found();
        }

        foreach ($this->endpoints as $endpoint) {
            if ($endpoint->method === $method && $endpoint->path === $path) {
                try {
                    ($endpoint->action)($params);
                } catch (\Exception $e) {
                    $this->internal_server_error();
                }
            }
        }

        $this->not_found();
    }

    public static function ok_redirect(?string $success = null) {
        $redirect = $_GET['redirect'] ?? null;

        if ($success) {
            set_action_success($success);
        }

        header('Location: ' . page($redirect));
        exit();
    }

    public static function error_go_back(?string $error = null) {
        $back = $_GET['back'] ?? null;
        
        if ($error) {
            set_action_error($error);
        }
        
        if ($back) {
            header('Location: ' . $back);
            exit();
        } else {
            echo 'Error: ' . e($error);
            exit();
        }
    }

    private function get_params($method) {
        switch ($method) {
            case 'GET':
                return $_GET;
            case 'POST':
                return $_POST;
            default:
                return [];
        }
    }

    private function not_found() {
        if ($this->not_found_page) {
            header('Location: ' . $this->not_found_page);
            exit();
        } else {
            echo '404 Not Found';
            exit();
        }
    }

    private function internal_server_error() {
        if ($this->internal_server_error_page) {
            header('Location: ' . $this->internal_server_error_page);
            exit();
        } else {
            echo '500 Internal Server Error';
            exit();
        }
    }
}
