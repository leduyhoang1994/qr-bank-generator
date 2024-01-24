<?php

return [
    'config'                     => [
        'point_of_initiation_method' => 'static',
        'service'                    => 'napas247',
        'guid'                       => 'value',
    ],
    'cast_country_code'          => [
        'VNM' => 704,
    ],
    'bank_list'                  => [
        'vietinbank' => 'vietinbank',
    ],
    'transaction_currency'       => [
        ''
    ],
    'guid'                       => [
        'value' => 'A000000727'
    ],
    'point_of_initiation_method' => [
        'static'  => 11,
        'dynamic' => 12,
    ],
    'service'                    => [
        'napas247' => 'QRIBFTTA',
    ],
    'qr_id'                      => [
        'payload_format_indicator'       => 00,
        'point_of_initiation_method'     => 01,
        'consumer_account_information'   => 38,
        'transaction_currency'           => 53,
        'transaction_amount'             => 54,
        'country_code'                   => 58,
        'additional_data_field_template' => 62,
        'cyclic_redundancy_check'        => 63,
        'guid'                           => 00,
        'acquier'                        => 00,
        'merchant'                       => 01,
        'beneficiary_bank'               => 01,
        'content'                        => 8,
        'service'                        => 07,
    ],
];