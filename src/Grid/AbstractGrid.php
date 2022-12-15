<?php

namespace hxv\AoC\Grid;

abstract class AbstractGrid implements GridInterface
{
    public function createPoint(int $x, int $y): BetterPoint
    {
        return new BetterPoint($this, $x, $y);
    }

    public function getOrCreatePoint(int $x, int $y): BetterPoint
    {
        return $this->getPointAt($x, $y) ?? $this->createPoint($x, $y);
    }
}
