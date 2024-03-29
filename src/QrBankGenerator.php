<?php

namespace RedFlag\QrBankGenerator;

use RedFlag\QrBankGenerator\Interfaces\IQrBankGenerator;

class QrBankGenerator implements IQrBankGenerator
{
    private $config = [];
    private $thirdParty;

    public function __construct()
    {
        $this->loadConfig();

        $this->thirdParty = new Napas247ThirdParty();
    }

    private function loadConfig()
    {
        $this->config = include(__DIR__.'/config.php');
    }

    protected function generateConsumerAccountInformation($bankCode, $accountNumber)
    {
        $bank = $this->thirdParty->getBankBy('code', $bankCode);

        $acquierBin = $bank['bin'];

        $acquier  = $this->generateEntityCode('acquier', $acquierBin);
        $merchant = $this->generateEntityCode('merchant', $accountNumber);

        $guid = $this->generateEntityCode('guid', $this->config['config']['guid'], true);

        $beneficiaryBank = $this->generateEntityCode('beneficiary_bank', sprintf('%s%s', $acquier, $merchant));

        $thirdParty = $this->generateEntityCode('service', $this->config['config']['service'], true);

        return sprintf('%s%s%s', $guid, $beneficiaryBank, $thirdParty);
    }

    public function generate($qrCode)
    {
        $amount        = $qrCode->getAmount();
        $bankCode      = $qrCode->getBankCode();
        $accountName   = $qrCode->getAccountName();
        $accountNumber = $qrCode->getAccountNumber();
        $content       = $qrCode->getContent();

        $payloadFormatIndicator  = $this->generateEntityCode('payload_format_indicator', '01');
        $pointOfInitiationMethod = $this->generateEntityCode('point_of_initiation_method',
            $this->config['config']['point_of_initiation_method'], true);

        $consumerAccountInformation = $this->generateEntityCode('consumer_account_information',
            $this->generateConsumerAccountInformation($bankCode, $accountNumber));

        $transactionCurrency         = $this->generateEntityCode('transaction_currency',
            $this->config['cast_country_code']['VNM']);
        $transactionAmount           = $this->generateEntityCode('transaction_amount', $amount);
        $countryCode                 = $this->generateEntityCode('country_code', 'VN');

        $contentCode = $this->generateEntityCode('content', $content);
        $additionalDataFieldTemplate = $this->generateEntityCode('additional_data_field_template', $contentCode);

        $contentQR = sprintf('%s%s%s%s%s%s%s', $payloadFormatIndicator, $pointOfInitiationMethod,
            $consumerAccountInformation, $transactionCurrency, $transactionAmount, $countryCode,
            $additionalDataFieldTemplate);

        $crc = self::calcCRC(sprintf('%s%s%s', $contentQR, $this->config['qr_id']['cyclic_redundancy_check'], '04'));

        $contentQR .= $this->generateEntityCode('cyclic_redundancy_check', $crc);

        return $contentQR;
    }

    protected static function convertNum($value)
    {
        if ((filter_var($value, FILTER_VALIDATE_INT) !== false) && (int) $value < 10) {
            $value = '0'.$value;
        }

        return (string) $value;
    }


    protected function generateEntityCode($entity, $value, $useConfig = false)
    {
        $entityCode = self::convertNum($this->config['qr_id'][$entity]);
        if ($useConfig) {
            $value = $this->config[$entity][$value];
        }

        $len = self::convertNum(strlen($value));
        return sprintf('%s%s%s', $entityCode, $len, $value);
    }

    protected static function calcCRC($str)
    {
        return sprintf('%04X', crc16_ccitt_ffff($str));
    }

    protected static function calcDecimal($str)
    {
        $decimalTable = [
            0x0000, 0x1021, 0x2042, 0x3063, 0x4084, 0x50a5, 0x60c6, 0x70e7, 0x8108,
            0x9129, 0xa14a, 0xb16b, 0xc18c, 0xd1ad, 0xe1ce, 0xf1ef, 0x1231, 0x0210,
            0x3273, 0x2252, 0x52b5, 0x4294, 0x72f7, 0x62d6, 0x9339, 0x8318, 0xb37b,
            0xa35a, 0xd3bd, 0xc39c, 0xf3ff, 0xe3de, 0x2462, 0x3443, 0x0420, 0x1401,
            0x64e6, 0x74c7, 0x44a4, 0x5485, 0xa56a, 0xb54b, 0x8528, 0x9509, 0xe5ee,
            0xf5cf, 0xc5ac, 0xd58d, 0x3653, 0x2672, 0x1611, 0x0630, 0x76d7, 0x66f6,
            0x5695, 0x46b4, 0xb75b, 0xa77a, 0x9719, 0x8738, 0xf7df, 0xe7fe, 0xd79d,
            0xc7bc, 0x48c4, 0x58e5, 0x6886, 0x78a7, 0x0840, 0x1861, 0x2802, 0x3823,
            0xc9cc, 0xd9ed, 0xe98e, 0xf9af, 0x8948, 0x9969, 0xa90a, 0xb92b, 0x5af5,
            0x4ad4, 0x7ab7, 0x6a96, 0x1a71, 0x0a50, 0x3a33, 0x2a12, 0xdbfd, 0xcbdc,
            0xfbbf, 0xeb9e, 0x9b79, 0x8b58, 0xbb3b, 0xab1a, 0x6ca6, 0x7c87, 0x4ce4,
            0x5cc5, 0x2c22, 0x3c03, 0x0c60, 0x1c41, 0xedae, 0xfd8f, 0xcdec, 0xddcd,
            0xad2a, 0xbd0b, 0x8d68, 0x9d49, 0x7e97, 0x6eb6, 0x5ed5, 0x4ef4, 0x3e13,
            0x2e32, 0x1e51, 0x0e70, 0xff9f, 0xefbe, 0xdfdd, 0xcffc, 0xbf1b, 0xaf3a,
            0x9f59, 0x8f78, 0x9188, 0x81a9, 0xb1ca, 0xa1eb, 0xd10c, 0xc12d, 0xf14e,
            0xe16f, 0x1080, 0x00a1, 0x30c2, 0x20e3, 0x5004, 0x4025, 0x7046, 0x6067,
            0x83b9, 0x9398, 0xa3fb, 0xb3da, 0xc33d, 0xd31c, 0xe37f, 0xf35e, 0x02b1,
            0x1290, 0x22f3, 0x32d2, 0x4235, 0x5214, 0x6277, 0x7256, 0xb5ea, 0xa5cb,
            0x95a8, 0x8589, 0xf56e, 0xe54f, 0xd52c, 0xc50d, 0x34e2, 0x24c3, 0x14a0,
            0x0481, 0x7466, 0x6447, 0x5424, 0x4405, 0xa7db, 0xb7fa, 0x8799, 0x97b8,
            0xe75f, 0xf77e, 0xc71d, 0xd73c, 0x26d3, 0x36f2, 0x0691, 0x16b0, 0x6657,
            0x7676, 0x4615, 0x5634, 0xd94c, 0xc96d, 0xf90e, 0xe92f, 0x99c8, 0x89e9,
            0xb98a, 0xa9ab, 0x5844, 0x4865, 0x7806, 0x6827, 0x18c0, 0x08e1, 0x3882,
            0x28a3, 0xcb7d, 0xdb5c, 0xeb3f, 0xfb1e, 0x8bf9, 0x9bd8, 0xabbb, 0xbb9a,
            0x4a75, 0x5a54, 0x6a37, 0x7a16, 0x0af1, 0x1ad0, 0x2ab3, 0x3a92, 0xfd2e,
            0xed0f, 0xdd6c, 0xcd4d, 0xbdaa, 0xad8b, 0x9de8, 0x8dc9, 0x7c26, 0x6c07,
            0x5c64, 0x4c45, 0x3ca2, 0x2c83, 0x1ce0, 0x0cc1, 0xef1f, 0xff3e, 0xcf5d,
            0xdf7c, 0xaf9b, 0xbfba, 0x8fd9, 0x9ff8, 0x6e17, 0x7e36, 0x4e55, 0x5e74,
            0x2e93, 0x3eb2, 0x0ed1, 0x1ef0,
        ];

        $decimal = 0xffff;
        for ($i = 0, $iMax = strlen($str); $i < $iMax; $i++) {
            $c = ord($str[$i]); // Get character code in PHP
            if ($c > 255) {
                throw new \RangeException("Character code exceeds 255");
            }
            $j       = ($c ^ ($decimal >> 8)) & 0xff;
            $decimal = $decimalTable[$j] ^ ($decimal << 8);
        }

        return $decimal & 0xffff;
    }

    public function parse($qrString)
    {
        $payloadFormatIndicator = $this->calcFirstParamQrString($qrString, 'payload_format_indicator',
            0, 'point_of_initiation_method');

        $pointOfInitiationMethod = $this->calcFirstParamQrString($qrString, 'point_of_initiation_method',
            $payloadFormatIndicator['offsetToNextEntityCode'], 'consumer_account_information');

        $consumerAccountInformation = $this->calcFirstParamQrString($qrString, 'consumer_account_information',
            $pointOfInitiationMethod['offsetToNextEntityCode'], 'transaction_currency');

        $guid = $this->calcFirstParamQrString($consumerAccountInformation['entityVal'], 'guid', 0, 'beneficiary_bank');

        $beneficiaryBank = $this->calcFirstParamQrString($consumerAccountInformation['entityVal'], 'beneficiary_bank',
            $guid['offsetToNextEntityCode'], 'service');

        $acquier = $this->calcFirstParamQrString($beneficiaryBank['entityVal'], 'acquier', 0, 'merchant');

        $merchant = $this->calcFirstParamQrString($beneficiaryBank['entityVal'], 'merchant',
            $acquier['offsetToNextEntityCode']);

        $service = $this->calcFirstParamQrString($consumerAccountInformation['entityVal'], 'service',
            $beneficiaryBank['offsetToNextEntityCode']);

        $transactionCurrency = $this->calcFirstParamQrString($qrString, 'transaction_currency',
            $consumerAccountInformation['offsetToNextEntityCode'], 'transaction_amount');

        $transactionAmount = $this->calcFirstParamQrString($qrString, 'transaction_amount',
            $transactionCurrency['offsetToNextEntityCode'], 'country_code');

        $countryCode = $this->calcFirstParamQrString($qrString, 'country_code',
            $transactionAmount['offsetToNextEntityCode'], 'additional_data_field_template');


        $additionalDataFieldTemplate = $this->calcFirstParamQrString($qrString, 'country_code',
            $countryCode['offsetToNextEntityCode'], 'cyclic_redundancy_check');

        $content = $this->calcFirstParamQrString($additionalDataFieldTemplate['entityVal'], 'content');

        $crc = $this->calcFirstParamQrString($qrString, 'cyclic_redundancy_check',
            $additionalDataFieldTemplate['offsetToNextEntityCode']);

        $bank = $this->thirdParty->getBankBy('bin', $acquier['entityVal']);

        $qrCode = new BankQr();
        $qrCode->setAmount($transactionAmount['entityVal']);
        $qrCode->setBankCode($bank['code']);
        $qrCode->setAccountNumber($merchant['entityVal']);
        $qrCode->setContent($content['entityVal']);

        return $qrCode;
    }

    protected function calcFirstParamQrString($qrString, $entity, $offsetToEntityCode = 0, $nextEntity = '')
    {
        $qrLen       = strlen($qrString);
        $qrId        = self::convertNum($this->config['qr_id'][$entity]);
        $qrIdLen     = strlen($qrId);
        $nextQrId    = $nextEntity ? self::convertNum($this->config['qr_id'][$nextEntity]) : '';
        $nextQrIdLen = strlen($nextQrId);

        for ($i = 2; $i < $qrLen; $i++) {
            $entityLen    = substr($qrString, $qrIdLen + $offsetToEntityCode, $i);
            $entityLenVal = (int) $entityLen;
            $offsetOfVal  = $qrIdLen + strlen($entityLen);
            $entityVal    = substr($qrString, $offsetOfVal + $offsetToEntityCode, $entityLenVal);
            $paramLen     = $offsetOfVal + strlen($entityVal);

            $offsetToNextEntityCode = $paramLen + $offsetToEntityCode;

            $nextQrIdVal = substr($qrString, $offsetToNextEntityCode, $nextQrIdLen);
            if ($nextQrId === $nextQrIdVal) {
                return compact('qrId', 'entityLen', 'entityVal', 'paramLen', 'offsetToNextEntityCode');
            }
        }

        return [];
    }

    public function getBankList()
    {
        return $this->thirdParty->getBanks();
    }
}