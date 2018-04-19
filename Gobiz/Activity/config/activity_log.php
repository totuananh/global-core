<?php

return [
    /*
     * Tên topic chứa activity log trên kafka
     */
    'kafka_topic' => 'ACTIVITY_LOG',

    /*
     * Tên index lưu activity log trên elastic search
     */
    'elastic_index' => 'activity_log',

    /*
     * Tên type lưu activity log trên elastic search
     */
    'elastic_type' => 'activity_logs',
];
