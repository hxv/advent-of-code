<?php

namespace hxv\AoC\Grid;

class AbstractGridDecorator extends AbstractGrid
{
    public function __construct(private GridInterface $inner)
    {
    }

    public function addPoint(BetterPoint $point): self
    {
        $this->inner->addPoint($point);

        return $this;
    }

    public function removePoint(BetterPoint $point): self
    {
        $this->inner->removePoint($point);

        return $this;
    }

    public function getPointAt(int $x, int $y): ?BetterPoint
    {
        return $this->inner->getPointAt($x, $y);
    }

    public function getBoundaries(): array
    {
        return $this->inner->getBoundaries();
    }
}
