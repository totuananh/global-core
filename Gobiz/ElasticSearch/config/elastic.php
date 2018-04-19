<?php

return [
    /**
     * MYIP là global env của OS được set từ docker trên môi trường dev
     */
    'hosts' => [
        env('ELASTIC_HOST', getenv('MYIP')).':9200'
    ]
];
