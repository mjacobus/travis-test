<?php

namespace Brofist\ValueObjects;

use DateTimeZone;

class Date extends \DateTimeImmutable
{
    /**
     * @return Date
     */
    public function toUtc()
    {
        return $this->setTimezone(new DateTimeZone('UTC'));
    }
}
