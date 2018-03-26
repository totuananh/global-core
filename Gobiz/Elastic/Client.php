<?php

namespace App\Library\Gobiz\Elastic;

use Elasticsearch\ClientBuilder;

class Client
{
    private $client;

    public function __construct($config)
    {
        $this->client = ClientBuilder::create()
        ->setHosts($config['hosts'])
        ->build();
    }

    /**
     * @desc index a document
     * @param array $params
     * @return array
     */
    public function indexd(Array $params)
    {
        return $this->client->index($params);
    }

    public function get(Array $params)
    {
        return $this->client->get($params);
    }

    public function search(Array $params)
    {
        return $this->client->search($params);
    }

    /**
     * @desc Delete a document
     * @param array $params
     * @return array
     */
    public function deld(Array $params)
    {
        return $this->client->delete($params);
    }

    /**
     * @desc Delete an index
     * @param array $params
     * @return array
     */
    public function deli(Array $params)
    {
        return $this->client->indices()->delete($params);
    }

    /**
     * @desc create an index
     */
    public function index()
    {

    }
}