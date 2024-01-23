<?php

namespace RedFlag\QrBankGenerator;

use RedFlag\QrBankGenerator\Interfaces\IQrBankGenerator;

class QrBankGenerator implements IQrBankGenerator
{
    private $config = [];

    public function __construct()
    {
        $this->loadConfig();
    }

    private function loadConfig()
    {
        $this->config = include_once (__DIR__ . '/config.php');
    }

    public function generate($qrCode)
    {
        // TODO: Implement generate() method.
        return '';
    }

    public function parse($qrString)
    {
        // TODO: Implement reverse() method.
        $qrCode = new QrCode();

        return $qrCode;
    }

    public function getBankList()
    {
        return isset($this->config['bank_list']) ? $this->config['bank_list'] : [];
    }
}