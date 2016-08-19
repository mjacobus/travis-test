<?php

namespace Brofist\Couchbase;

use CouchbaseBucket;
use CouchbaseException;
use CouchbaseN1qlQuery;

class BucketAdapter
{
    const OPTION_LIMIT = 'limit';

    private $availableOptions = [
        self::OPTION_LIMIT,
    ];

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

    /**
     * @see CouchbaseBucket::insert()
     *
     * @param mixed $id
     * @param mixed $data
     * @param array $options
     *
     * @return mixed
     */
    public function insert($id, $data, array $options = [])
    {
        return $this->bucket->insert($id, $data, $options);
    }

    /**
     * @see CouchbaseBucket::upsert()
     *
     * @param mixed $id
     * @param mixed $data
     * @param array $options
     *
     * @return mixed
     */
    public function persist($id, $data, array $options = [])
    {
        return $this->bucket->upsert($id, $data, $options);
    }

    /**
     * @throws CouchbaseException
     *
     * @return array
     */
    public function findAll()
    {
        return $this->query($this->getSelectAll());
    }

    /**
     * @param array $conditions
     * @param array $options 'limit' is an option
     *
     * @throws CouchbaseException
     *
     * @return QueryResultSet
     */
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
     * @throws CouchbaseException
     *
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
