<?php

class TestGenerator extends \PHPUnit\Framework\TestCase {
    public function test_generate() {
        $bankCode = '';
        $accountNumber = '';
        $accountName = '';
        $amount = 0;
        $content = '';

        $qrCode = new \RedFlag\QrBankGenerator\QrCode($bankCode, $accountNumber, $accountName, $amount, $content);

        $qrString = $qrCode->toString();

        $this->assertIsString($qrString, 'QR string must be string');
    }

    public function test_parse() {
        $qrCode = \RedFlag\QrBankGenerator\QrCode::fromQrString('Lorem Ipsum');

        $this->assertTrue($qrCode instanceof \RedFlag\QrBankGenerator\QrCode, 'QR string is invalid');
    }
}