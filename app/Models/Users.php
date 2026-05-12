<?php

namespace App\Models;

class Users {

    private int $id;
    private string $first_name;
    private string $last_name;
    private string $email;
    private string $password_hash;
    private Role $role;

    public function __construct(int $id, string $first_name, string $last_name, string $email, string $password_hash, Role $role) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->role = $role;
    }

    public function get_id(): int {
        return $this->id;
    }

    public function get_first_name(): string {
        return $this->first_name;
    }

    public function get_last_name(): string {
        return $this->last_name;
    }  

    public function get_email(): string {
        return $this->email;
    }

    public function get_password_hash(): string {
        return $this->password_hash;
    }

    public function get_role(): Role {
        return $this->role;
    }
}