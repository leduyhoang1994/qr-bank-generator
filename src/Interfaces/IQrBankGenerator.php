<?php

namespace RedFlag\QrBankGenerator\Interfaces;

use RedFlag\QrBankGenerator\BankQr;

interface IQrBankGenerator
{

    /**
     * Generate QR string from BankQr object
     * @param BankQr $qrCode
     * @return string
     */
    public function generate($qrCode);

    /**
     * Parse QR string into qr object
     * @param $qrString
     * @return BankQr
     */
    public function parse($qrString);

    public function getBankList();
}