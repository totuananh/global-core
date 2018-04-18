<?php

namespace Gobiz\ElasticSearch;

use Elasticsearch\Client;

class ElasticSearchService
{
    const CLIENT = 'elastic_search.client';

    /**
     * @return Client
     */
    public static function client()
    {
        return app(static::CLIENT);
    }
}