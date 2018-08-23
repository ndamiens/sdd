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
	return $sepassd;
    }

    public function testInitInvalidIban() {
	$this->expectException(SEPAInvalidFormat::class);
	$config = [
	    "name" => "Test",
	    "IBAN" => "FR76AZEX6000011234567890189",
	    "BIC" => "BANKNL2A",
	    "batch" => true,
	    "creditor_id" => "00000",
	    "currency" => "EUR"
	];
	new SEPASDD($config);
    }

    /**
     * @depends testCreateInstance
     */
    public function testAddPayments(SEPASDD $sdd) {
	$payment1 = [
	    "name" => "Test von Testenstein",
	    "IBAN" => "FR7630001007941234567890185",
	    //"BIC" => "BANKNL2A", <- Optional, banks may disallow BIC in future
	    "amount" => "1000",
	    "type" => "FRST",
	    "collection_date" => date("Y-m-d"),
	    "mandate_id" => "1234",
	    "mandate_date" => date("2014-02-01"),
	    "description" => "Test transaction"
	];
	$sdd->addPayment($payment1);
	$infos = $sdd->getDirectDebitInfo();
	$this->assertArrayHasKey("TotalTransactions", $infos);
	$this->assertEquals(1, $infos['TotalTransactions']);
	$this->assertArrayHasKey("TotalAmount", $infos);
	$this->assertEquals(1000, $infos['TotalAmount']);
	$payment2 = [
	    "name" => "Test von Testenstein",
	    "IBAN" => "FR7630001007941234567890185",
	    //"BIC" => "BANKNL2A", <- Optional, banks may disallow BIC in future
	    "amount" => "1234",
	    "type" => "RCUR",
	    "collection_date" => date("Y-m-d"),
	    "mandate_id" => "1234",
	    "mandate_date" => date("2014-02-01"),
	    "description" => "Test transaction"
	];
	$sdd->addPayment($payment2);
	$infos = $sdd->getDirectDebitInfo();
	$this->assertArrayHasKey("TotalAmount", $infos);
	$this->assertEquals(2234, $infos['TotalAmount']);
	$this->assertArrayHasKey("TotalTransactions", $infos);
	$this->assertEquals(2, $infos['TotalTransactions']);
	return $sdd;
    }

    /**
     * @depends testCreateInstance
     */
    public function testInvalidPaymentType(SEPASDD $sdd) {
	$this->expectException(SEPAInvalidFormat::class);
	$payment = [
	    "name" => "Test von Testenstein",
	    "IBAN" => "FR7630001007941234567890185",
	    //"BIC" => "BANKNL2A", <- Optional, banks may disallow BIC in future
	    "amount" => "1000",
	    "type" => "PLOP",
	    "collection_date" => date("Y-m-d"),
	    "mandate_id" => "1234",
	    "mandate_date" => "2014-02-01",
	    "description" => "Test transaction"
	];
	$sdd->addPayment($payment);
    }

    /**
     * @depends testCreateInstance
     */
    public function testInvalidDateTest(SEPASDD $sdd) {
	$this->expectException(SEPAInvalidFormat::class);
	$sdd->validateDate("2014-13-22");
	$this->assertTrue($sdd->validateDate("2012-02-12"));
    }
    /**
     * @depends testCreateInstance
     */
    public function testInvalidDate(SEPASDD $sdd) {
	$this->expectException(SEPAInvalidFormat::class);
	$payment = [
	    "name" => "Test von Testenstein",
	    "IBAN" => "FR7630001007941234567890185",
	    //"BIC" => "BANKNL2A", <- Optional, banks may disallow BIC in future
	    "amount" => "1000",
	    "type" => "FRST",
	    "collection_date" => date("Y-m-d"),
	    "mandate_id" => "1234",
	    "mandate_date" => "2014-02-31",
	    "description" => "Test transaction"
	];
	$sdd->addPayment($payment);
    }

    /**
     * @depends testAddPayments
     */
    public function testXml(SEPASDD $sdd) {
	$xml = $sdd->save();
	$this->assertTrue($sdd->validate($xml));
    }

}
