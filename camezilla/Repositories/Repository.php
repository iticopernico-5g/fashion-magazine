<?php

namespace Camezilla\Repositories;

use Camezilla\Models\Database;

abstract class Repository {

    protected Database $database;

    public function __construct() {
        $this->database = get_database();
        connect_database();
    }
}