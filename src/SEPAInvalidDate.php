<?php
namespace Congressus;

class SEPAInvalidDate extends SEPAInvalidFormat {
    public function __construct($message = "Invalid Date", $code = 0, \Throwable $previous = null) {
	parent::__construct($message, $code, $previous);
    }
}