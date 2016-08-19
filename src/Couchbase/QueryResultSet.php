<?php

namespace Brofist\Couchbase;

class QueryResultSet
{
    /**
     * @var \stdClass
     */
    private $rawResult;

    public function __construct($rawResult)
    {
        $this->rawResult = $rawResult;
    }

    public function getRows()
    {
        return $this->rawResult->rows;
    }

    public function getData()
    {
        $data = [];

        foreach ($this->getRows() as $rows) {
            $data[] = array_values($rows)[0];
        }

        return $data;
    }

    /**
     * Either the first row or null
     *
     * @return array|null
     */
    public function first()
    {
        $data = $this->getData();
        return array_shift($data);
    }
}
