<?php

namespace BrofistTest\ValueObjects;

use Brofist\ValueObjects\Date;
use PHPUnit_Framework_TestCase;

class DateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Date
     */
    protected $date;

    /**
     * @before
     */
    public function initialize()
    {
        $this->date = new Date();
    }

    /**
     * @test
     */
    public function extendsPhpDateTimeImmutable()
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->date);
    }

    /**
     * @test
     */
    public function canConvertDateToUtc()
    {
        $actual = $this->date->toUtc();

        $newDate = clone $this->date;
        $expected = $newDate->setTimezone(new \DateTimeZone('UTC'));

        $this->assertEquals($expected, $actual);

        $this->assertNotSame($actual, $this->date->toUtc());
    }
}
