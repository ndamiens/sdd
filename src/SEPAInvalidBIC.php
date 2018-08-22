<?php
namespace Congressus;

class SEPAInvalidBIC extends SEPAInvalidFormat {
    public function __construct($message = "Invalid BIC", $code = 0, \Throwable $previous = null) {
	parent::__construct($message, $code, $previous);
    }
}