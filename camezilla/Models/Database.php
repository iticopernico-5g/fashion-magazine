<?php
namespace Camezilla\Models;

use Exception;
use PDO;
use PDOException;

class Database {
    private ?PDO $connection;
    private string $host;
    private string $username;
    private string $password;
    private string $database;
    private string $error;

    public function __construct(string $host, string $username, string $password, string $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connection = null;
        $this->error = '';
    }

    public function get_error(): string {
        return $this->error;
    }

    public function connect(): void {
        try {
            $this->connection = new PDO($this->get_dsn(), $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            throw new Exception("Connection failed: " . $this->error);
        }
    }

    public function disconnect(): void {
        $this->connection = null;
    }

    public function is_connected(): bool {
        return $this->connection !== null;
    }

    public function query(string $sql) {
        $this->require_connection();

        return $this->connection->query($sql);
    }

    public function prepare(string $sql) {
        $this->require_connection();

        return $this->connection->prepare($sql);
    }

    public function execute(string $sql, array $params = []): bool {
        return $this->prepare($sql)->execute($params);
    }

    private function require_connection(): void {
        if (!$this->is_connected()) {
            $this->error = "Not connected to the database.";
            throw new Exception($this->error);
        }
    }

    private function get_dsn() {
        return "mysql:host={$this->host};dbname={$this->database};charset=utf8";
    }
}
