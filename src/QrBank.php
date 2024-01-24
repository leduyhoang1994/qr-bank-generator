<?php

namespace RedFlag\QrBankGenerator;

class QrBank
{
    protected $qrString;
    protected $bankCode;
    protected $accountNumber;
    protected $accountName;
    protected $amount;
    protected $content;
    protected $generator;

    /**
     * @param $bankCode
     * @param $accountNumber
     * @param $accountName
     * @param $amount
     * @param $content
     */
    public function __construct($bankCode = '', $accountNumber = '', $accountName = '', $amount = 0, $content = '')
    {
        $this->bankCode = $bankCode;
        $this->accountNumber = $accountNumber;
        $this->accountName = $accountName;
        $this->amount = $amount;
        $this->content = $content;

        $this->generator = new QrBankGenerator();
    }

    public static function fromQrString($string)
    {
        $generator = new QrBankGenerator();

        return $generator->parse($string);
    }

    /**
     * @return mixed
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * @param mixed $bankCode
     */
    public function setBankCode($bankCode)
    {
        $this->bankCode = $bankCode;
    }

    /**
     * @return mixed
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param mixed $accountNumber
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }

    /**
     * @return mixed
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * @param mixed $accountName
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->generator->generate($this);
    }
}