<?php

namespace RedFlag\QrBankGenerator\Interfaces;

interface IThirdParty
{
    public function getBanks();

    public function loadBanks();

    public function getBankBy($fill, $value);
}