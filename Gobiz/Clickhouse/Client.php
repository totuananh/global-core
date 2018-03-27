<?php

namespace Gobiz\Clickhouse;

use ClickHouse;

class Client
{
    private $config;
    private $client;
    protected $tableName;
    protected $columns = [];
    /**
     * Client constructor.
     * https://packagist.org/packages/8bitov/clickhouse-php-client?q=&p=0&hFR[type][0]=application
     * php composer require 8bitov/clickhouse-php-client
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new ClickHouse\Client($this->config['host'], $this->config['port']);
        $this->checkConnection();
    }

    protected function checkConnection() {
        if (!$this->client->ping()) {
            throw new \Exception('Kết nối thất bại!');
        };
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function hydrate($data) {
        return $this->client->insert($this->tableName, $this->columns, $data);
    }

    public function fetchAll()
    {
        return $this->client->select('SELECT * FROM :table', ['table' => $this->tableName])->fetchAll();
    }

    public function fetchOne() {
        return $this->client->select('SELECT * FROM :table', ['table' => $this->tableName])->fetchOne();
    }
}