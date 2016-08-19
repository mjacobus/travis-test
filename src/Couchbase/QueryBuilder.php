<?php

namespace Brofist\Couchbase;

class QueryBuilder
{

    /**
     * @var string
     */
    private $from;

    /**
     * @var array
     */
    private $where;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @return QueryBuilder
     */
    public function create()
    {
        return clone $this;
    }

    public function from($from)
    {
        $new = clone $this;
        $new->from = $from;
        return $new;
    }

    public function where(array $conditions)
    {
        $new = clone $this;
        $new->where = $conditions;
        return $new;
    }

    public function limit($limit, $offset = null)
    {
        $new = clone $this;
        $new->limit = (int) $limit;

        if ($offset !== null) {
            $new->offset = (int) $offset;
        }

        return $new;
    }

    public function __toString()
    {
        $query = [];

        if ($this->from) {
            $query[] = sprintf('SELECT * FROM `%s`', $this->from);
        }

        if ($this->where) {
            $query[] = 'WHERE';
            $conditions = [];

            foreach (array_keys($this->where) as $field) {
                $conditions[] = $field . ' = $'. $field;
            }

            $query[] = implode(' AND ', $conditions);
        }

        if ($this->limit) {
            $query[] = 'LIMIT ' . $this->limit;

            if ($this->offset !== null) {
                $query[] = 'OFFSET ' . $this->offset;
            }
        }

        return implode(' ', $query);
    }
}
