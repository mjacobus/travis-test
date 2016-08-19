<?php

namespace Brofist\ValueObjects;

class Ping
{
    /**
     * @var int
     */
    private $playerId;
    /**
     * @var int
     */
    private $instanceId;
    /**
     * @var int
     */
    private $networkId;
    /**
     * @var int
     */
    private $gameId;

    public function __construct($playerId, $instanceId, $networkId, $gameId)
    {
        $this->playerId = (int) $playerId;
        $this->instanceId = (int) $instanceId;
        $this->networkId = (int) $networkId;
        $this->gameId = (int) $gameId;
    }

    public static function fromString($pingString, $separtor = '-')
    {
        $parts = explode($separtor, $pingString);
        return new static($parts[0], $parts[1], $parts[2], $parts[3]);
    }

    public function getPlayerId()
    {
        return $this->playerId;
    }

    public function getInstanceId()
    {
        return $this->instanceId;
    }

    public function getNetworkId()
    {
        return $this->networkId;
    }

    public function getGameId()
    {
        return $this->gameId;
    }

    public function __toString()
    {
        return implode('-', [
            $this->playerId,
            $this->instanceId,
            $this->networkId,
            $this->gameId,
        ]);
    }
}
