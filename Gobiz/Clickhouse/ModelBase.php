<?php

namespace Gobiz\Clickhouse;

abstract class ModelBase
{
    private $client;
    protected $tableName;
    protected $columns = [];

    /**
     * ModelBase constructor.
     */
    public function __construct()
    {
        $this->client = app('clickhouse');
        $this->checkConnection();
    }

    protected function checkConnection() {
        if (!$this->client->ping()) {
            throw new \Exception('Kết nối thất bại!');
        };
    }

    public function findAndBy($condition, $limit = 0, $select = '*')
    {
        $where = implode(' AND ', array_map(function ($key, $value) {
            return $key . ' = ' . $value;
        }, array_keys($condition), $condition));

        return $this->findBy($where, $limit, $select);
    }

    public function findOrBy($condition, $limit = 0, $select = '*')
    {
        $where = implode(' OR ', array_map(function ($key, $value) {
            return $key . ' = ' . $value;
        }, array_keys($condition), $condition));

        return $this->findBy($where, $limit, $select);
    }

    private function findBy($where, $limit, $select)
    {
        $sub_limit = '';
        if (0 != $limit) {
            $sub_limit = 'LIMIT ' . $limit;
        }

        if ('*' != $select) {

            return $this->client->select('SELECT * FROM ' . $this->tableName . ' WHERE ' . $where . $sub_limit.' ')->fetchColumn($select);
        }

        return $this->client->select('SELECT * FROM ' . $this->tableName . ' WHERE ' . $where . $sub_limit.' ')->fetchAll();
    }


    /**
     * @param array $data
     * @return mixed|void
     */
    public function hydrate($data) {

        return $this->client->insert($this->tableName, $this->columns, [$data]);
    }
}