<?php

namespace Brofist\ValueObjects;

use PHPUnit_Framework_TestCase;

class PingTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ping
     */
    protected $ping;

    /**
     * @before
     */
    public function initialize()
    {
        $this->ping = Ping::fromString('1-2-3-4');
    }

    /**
     * @test
     */
    public function canBeConvertedToString()
    {
        $this->assertEquals('1-2-3-4', (string) $this->ping);
    }

    /**
     * @test
     */
    public function canGetPlayerId()
    {
        $this->assertSame(1, $this->ping->getPlayerId());
    }

    /**
     * @test
     */
    public function canGetInstanceId()
    {
        $this->assertSame(2, $this->ping->getInstanceId());
    }

    /**
     * @test
     */
    public function canGetNetworkId()
    {
        $this->assertSame(3, $this->ping->getNetworkId());
    }

    /**
     * @test
     */
    public function canGetGameId()
    {
        $this->assertSame(4, $this->ping->getGameId());
    }
}
