<?php

namespace Camezilla\Routers;

use Exception;

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

class Router {

    private $endpoints = [];

    public function get(string $path, callable $action) {
        $this->add_endpoint('GET', $path, $action);
    }

    public function post(string $path, callable $action) {
        $this->add_endpoint('POST', $path, $action);
    }

    private function add_endpoint(string $method, string $path, callable $action) {
        $this->endpoints[] = new Endpoint($method, $path, $action);
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_GET['path'] ?? null;

        if (!$path) {
            HttpResponse::not_found();
            return;
        }

        $params = $this->get_params($method);

        foreach ($this->endpoints as $endpoint) {
            if ($endpoint->method === $method && $endpoint->path === $path) {
                try {
                    return ($endpoint->action)($params);
                }
                catch (Exception $e) {
                    HttpResponse::internal_server_error($e->getMessage());
                    return;
                }
            }
        }
        
        HttpResponse::not_found();
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
}