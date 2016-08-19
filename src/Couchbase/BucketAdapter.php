<?php

namespace Brofist\Couchbase;

use CouchbaseBucket;
use CouchbaseN1qlQuery;

class BucketAdapter
{
    /**
     * @var CouchbaseBucket
     */
    private $bucket;

    /**
     * @var string
     */
    private $bucketName;

    public function __construct(CouchbaseBucket $bucket, $bucketName)
    {
        $this->bucket = $bucket;
        $this->bucketName = $bucketName;
    }

    public function findAll()
    {
        return $this->query($this->getSelectAll());
    }

    public function findAllBy(array $conditions, array $options = [])
    {
        $append = ['WHERE'];
        $conditionParts = [];

        foreach (array_keys($conditions) as $key) {
            $conditionParts[] = "$key = $$key";
        }

        $append[] = implode(' AND ', $conditionParts);

        if (array_key_exists('limit', $options)) {
            $append[] = 'LIMIT ' . (int) $options['limit'];
        }

        return $this->query($this->getSelectAll($append), $conditions);
    }

    /**
     * @return QueryResultSet
     */
    private function query($string, $namedParams = [])
    {
        return $this->fetchRows($string, $namedParams);
    }

    /**
     * @return QueryResultSet
     */
    protected function fetchRows($string, $namedParams = [])
    {
        $result = $this->bucket->query($this->createFromString($string, $namedParams), true);
        return new QueryResultSet($result);
    }

    /**
     * @return CouchbaseN1qlQuery
     */
    protected function createFromString($string, array $namedParams = [])
    {
        $query = CouchbaseN1qlQuery::fromString($string);

        if ($namedParams) {
            $query->namedParams($namedParams);
        }

        return $query;
    }

    private function getSelectAll(array $append = [])
    {
        $select = 'SELECT * FROM `' . $this->bucketName . '`';

        if ($append) {
            $select .= ' ' . implode(' ', $append);
        }

        return $select;
    }
}
