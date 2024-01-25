<?php

return [
    'config'                     => [
        'point_of_initiation_method' => 'dynamic',
        'service'                    => 'napas247_account',
        'guid'                       => 'value',
    ],
    'cast_country_code'          => [
        'VNM' => 704,
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
        'napas247_card'    => 'QRIBFTTC',
        'napas247_account' => 'QRIBFTTA',
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
        'beneficiary_bank'               => 01,
        'acquier'                        => 00,
        'merchant'                       => 01,
        'service'                        => 02,
        'content'                        => 8,
    ],
];