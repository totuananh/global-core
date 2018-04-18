<?php

return [
    /**
     * MYIP là global env của OS được set từ docker trên môi trường dev
     */
    'broker_default' => 'QUEUE',
    'brokers' => [
        'QUEUE' => [
            'zk' => [
                'HOST'      => env('KAFKA_QUEUE_HOST', getenv('MYIP')),
                'PORT'      => env('KAFKA_QUEUE_PORT', '2181')
            ],
            'kk' => [
                'HOST'      => env('KAFKA_QUEUE_HOST', getenv('MYIP')),
                'PORT'      => env('KAFKA_QUEUE_PORT', '9092')
            ]
        ],
        'METRIC' => [],
        'LOG' => [],
        'TRACKING' => [],
    ],
    'default_consumer_group' => 'ALL'
];
