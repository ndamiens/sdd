<?php
namespace Congressus;

class SEPAInvalidIBAN extends SEPAInvalidFormat {
    public function __construct($message = "Invalid IBAN", $code = 0, \Throwable $previous = null) {
	parent::__construct($message, $code, $previous);
    }
}