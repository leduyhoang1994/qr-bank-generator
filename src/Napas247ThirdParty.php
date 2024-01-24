<?php

namespace RedFlag\QrBankGenerator;

use RedFlag\QrBankGenerator\Interfaces\IThirdParty;

class Napas247ThirdParty implements IThirdParty
{

    /**
     * @var array
     */
    private $config = [];
    private $banks = [];

    public function __construct()
    {
        $this->loadConfig();
        $this->loadBanks();
    }

    private function loadConfig()
    {
        $this->config = include(__DIR__.'/third-party.php');
    }

    /**
     * @return array
     */
    public function getBanks()
    {
        return $this->banks;
    }

    public function loadBanks()
    {
        $this->banks = $this->_banks();
    }

    protected function _banks()
    {
        if (!$this->config['napas247']['get_list_bank_by_api']) {
            return $this->config['napas247']['list_bank'];
        }

        $ch = curl_init($this->config['napas247']['list_bank_api']);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        $data = [];
        if (curl_errno($ch)) {
            echo 'Error: '.curl_error($ch);
        } else {
            $data = json_decode($response, true);
            // Process the data from $data array
        }
        curl_close($ch);

        return $data['data'];
    }

    public function getBankBy($fill, $value)
    {
        return array_find($fill, $value, $this->banks);
    }
}