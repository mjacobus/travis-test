<?php

namespace Brofist\Couchbase;

use CouchbaseBucket;
use CouchbaseException;
use CouchbaseN1qlQuery;

class BucketAdapter
{
    const OPTION_LIMIT = 'limit';

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var CouchbaseBucket
     */
    private $bucket;

    /**
     * @var string
     */
    private $bucketName;

    /**
     * @param CouchbaseBucket $bucket
     * @param string $bucketName
     */
    public function __construct(CouchbaseBucket $bucket, $bucketName)
    {
        $this->queryBuilder = new QueryBuilder();
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
        return $this->fetchAll($this->select());
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
        $query = $this->select();
        $query = $this->applyOptions($query, $options);

        return $this->fetchAll($query, $conditions);
    }

    /**
     * @return QueryResultSet
     */
    protected function fetchAll(QueryBuilder $query, array $conditions = [])
    {
        $n1ql = CouchbaseN1qlQuery::fromString((string) $query->where($conditions));

        if ($conditions) {
            $n1ql->namedParams($conditions);
        }

        return new QueryResultSet($this->bucket->query($n1ql, true));
    }

    /**
     * @param QueryBuilder $query
     * @param array $options
     *
     * @return QueryBuilder
     */
    private function applyOptions(QueryBuilder $query, array $options)
    {
        if (array_key_exists(self::OPTION_LIMIT, $options)) {
            $query = $query->limit($options[self::OPTION_LIMIT]);
        }

        return $query;
    }

    /**
     * @return QueryBuilder
     */
    private function select()
    {
        return $this->queryBuilder->from($this->bucketName);
    }
}
