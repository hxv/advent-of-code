<?php

namespace hxv\AoC\Grid;

use LogicException;

class BetterGrid extends AbstractGrid
{
    /** @var array<int, array<int, BetterPoint>> */
    private array $points = [];

    /** @var null|array{x: array{min: int, max: int}, y: array{min: int, max: int}} */
    private ?array $boundaries = null;

    public function addPoint(BetterPoint $point): self
    {
        if (isset($this->points[$point->x][$point->y])) {
            throw new \RuntimeException(sprintf('Point at coordinates %s already exists.', $point));
        }

        $this->points[$point->x][$point->y] = $point;

        $this->boundaries ??= ['x' => ['min' => $point->x, 'max' => $point->x], 'y' => ['min' => $point->y, 'max' => $point->y]];

        $this->boundaries = [
            'x' => ['min' => min($this->boundaries['x']['min'], $point->x), 'max' => max($this->boundaries['x']['max'], $point->x)],
            'y' => ['min' => min($this->boundaries['y']['min'], $point->y), 'max' => max($this->boundaries['y']['max'], $point->y)],
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
