<?php

namespace RedFlag\QrBankGenerator\Interfaces;

use RedFlag\QrBankGenerator\QrCode;

interface IQrBankGenerator
{

    /**
     * Generate QR string from QrCode object
     * @param QrCode $qrCode
     * @return string
     */
    public function generate($qrCode);

    /**
     * Parse QR string into qr object
     * @param $qrString
     * @return QrCode
     */
    public function parse($qrString);

    public function getBankList();
}