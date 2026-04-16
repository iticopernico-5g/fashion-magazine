<?php
namespace Camezilla\Exceptions;

use Exception;

class RepositoryErrorException extends Exception {

    public function __construct(string $message = "Repository error", int $code = 0, ?Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}