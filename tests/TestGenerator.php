<?php

class TestGenerator extends \PHPUnit\Framework\TestCase {
    protected $qrString = '';
    protected $bankCode = 'ICB';
    protected $accountNumber = '102870429563';
    protected $accountName = 'zxczxc';
    protected $amount = 50000;
    protected $content = '182932921 NguyenHuyHung HPK 1';

    public function test_generate() {
        $qrCode = new \RedFlag\QrBankGenerator\BankQr($this->bankCode, $this->accountNumber, $this->accountName, $this->amount, $this->content);

        $this->qrString = $qrCode->toString();
        var_dump($this->qrString);die;
        $this->assertIsString($this->qrString, 'QR string must be string');

        $bankQr = \RedFlag\QrBankGenerator\BankQr::fromQrString($this->qrString);

        $check =
            $this->bankCode == $bankQr->getBankCode()
            && $this->accountNumber == $bankQr->getAccountNumber()
//            && $this->accountName == $bankQr->getAccountName()
            && $this->amount == $bankQr->getAmount()
            && $this->content == $bankQr->getContent();

        $this->assertTrue($check, 'QR string is invalid');
    }
}