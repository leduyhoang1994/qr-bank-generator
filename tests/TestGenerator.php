<?php

class TestGenerator extends \PHPUnit\Framework\TestCase {
    public function test_generate() {
        $bankCode = 'ICB';
        $accountNumber = '102870429563';
        $accountName = 'zxczxc';
        $amount = 50000;
        $content = 'TVU1703100_NguyenHuyHung_HocPhiKi_1';

        $qrCode = new \RedFlag\QrBankGenerator\QrCode($bankCode, $accountNumber, $accountName, $amount, $content);

        $qrString = $qrCode->toString();

        $this->assertIsString($qrString, 'QR string must be string');
    }

    public function test_parse() {
        $qrCode = \RedFlag\QrBankGenerator\QrCode::fromQrString('Lorem Ipsum');

        $this->assertInstanceOf(\RedFlag\QrBankGenerator\QrCode::class, $qrCode, 'QR string is invalid');
    }
}