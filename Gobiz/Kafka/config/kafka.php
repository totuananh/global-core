<?php

return [
    /*
     * Kết nối mặc định đến kafka
     */
    'default' => 'main',

    /*
     * Danh sách các kết nối đến kafka
     */
    'connections' => [
        'main' => [
            'brokers' => env('KAFKA_BROKERS', getenv('MYIP') . ':9092'),
        ],
    ],
];
