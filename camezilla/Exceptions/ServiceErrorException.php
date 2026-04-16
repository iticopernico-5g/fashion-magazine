<?php
namespace Camezilla\Exceptions;

use Exception;

class ServiceErrorException extends Exception {

    public function __construct(string $message = "Service error", int $code = 0, ?Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}