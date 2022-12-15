<?php

namespace hxv\AoC\Grid;

use LogicException;

class BetterGrid
{
    /** @var array<int, array<int, BetterPoint>> */
    private array $points = [];

    /** @var null|array{x: array{int, int}, y: array{int, int}} */
    private ?array $boundaries = null;

    public function createPoint(int $x, int $y): BetterPoint
    {
        return new BetterPoint($this, $x, $y);
    }

    public function getOrCreatePoint(int $x, int $y): BetterPoint
    {
        return $this->points[$x][$y] ?? $this->createPoint($x, $y);
    }

    public function addPoint(BetterPoint $point): self
    {
        if (isset($this->points[$point->x][$point->y])) {
            throw new \RuntimeException(sprintf('Point at coordinates %s already exists.', $point));
        }

        $this->points[$point->x][$point->y] = $point;

        $this->boundaries ??= ['x' => [$point->x, $point->x], 'y' => [$point->y, $point->y]];

        $this->boundaries = [
            'x' => [min($this->boundaries['x'][0], $point->x), max($this->boundaries['x'][1], $point->x)],
            'y' => [min($this->boundaries['y'][0], $point->y), max($this->boundaries['y'][1], $point->y)],
        ];

        return $this;
    }

    public function removePoint(BetterPoint $point): self
    {
        if ($point !== $this->points[$point->x][$point->y] ?? null) {
            throw new LogicException(sprintf('Point %s is not in the grid.', $point));
        }

        unset($this->points[$point->x][$point->y]);

        // TODO – recalculate boundaries
        // TODO – mark point as removed?

        return $this;
    }

    public function getPointAt(int $x, int $y): ?BetterPoint
    {
        return $this->points[$x][$y] ?? null;
    }

    public function getBoundaries(): array
    {
        return $this->boundaries ?? throw new LogicException("Can't get boundaries of empty grid.");
    }
}
