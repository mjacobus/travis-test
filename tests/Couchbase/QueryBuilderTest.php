<?php

namespace BrofistTest\Couchbase;

use Brofist\Couchbase\QueryBuilder;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class QueryBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var QueryBuilder
     */
    protected $object;

    /**
     * @var QueryBuilder
     */
    protected $lastObject;

    /**
     * @before
     */
    public function initialize()
    {
        $this->object = new QueryBuilder();
        $this->lastObject = $this->object;
    }

    /**
     * @test
     */
    public function canCreateSelectQueryFromBucket()
    {
        $object = $this->object->create();
        $this->assertImmutable($object);

        $from = $object->from('bucketName');
        $this->assertImmutable($from);
        $this->assertString('SELECT * FROM `bucketName`');

        $where = $from->where(['name' => 'John', 'lastname' => 'Doe']);
        $this->assertImmutable($where);
        $this->assertString('SELECT * FROM `bucketName` WHERE name = $name AND lastname = $lastname');

        $limited = $from->limit('1-');
        $this->assertImmutable($limited);
        $this->assertString('SELECT * FROM `bucketName` LIMIT 1');

        $limited = $where->limit(10, 7);
        $this->assertImmutable($limited);
        $this->assertString('SELECT * FROM `bucketName` WHERE name = $name AND lastname = $lastname LIMIT 10 OFFSET 7');
    }

    private function assertImmutable(QueryBuilder $builder)
    {
        $this->assertNotSame($this->lastObject, $builder);
        $this->lastObject = $builder;
    }

    public function assertString($string)
    {
        $this->assertEquals($string, (string) $this->lastObject);
    }
}
