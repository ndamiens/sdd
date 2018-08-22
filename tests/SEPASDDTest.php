<?php
namespace Congressus;

class SEPASDDTest extends \PHPUnit\Framework\TestCase {
    public function testCreateInstance() {
	$config = [
	    "name" => "Test",
	    "IBAN" => "FR7630006000011234567890189",
	    "BIC" => "BANKNL2A",
	    "batch" => true,
	    "creditor_id" => "00000",
	    "currency" => "EUR"
	];

	$sepassd = new SEPASDD($config);
	$this->assertInstanceOf(SEPASDD::class, $sepassd);
    }
}