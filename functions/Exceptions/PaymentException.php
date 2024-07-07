<?php

namespace PaymentLibraryProject\Exceptions;

class PaymentException extends \Exception {
    protected $userMessage;

    public function __construct($message = "", $code = 0, \Exception $previous = null, $userMessage = "") {
        parent::__construct($message, $code, $previous);
        $this->userMessage = $userMessage;
    }

    public function getUserMessage() {
        return $this->userMessage;
    }
}
