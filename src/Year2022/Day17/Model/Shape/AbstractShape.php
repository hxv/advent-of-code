<?php

namespace hxv\AoC\Year2022\Day17\Model\Shape;

use hxv\AoC\Year2022\Day17\Model\Direction;
use hxv\AoC\Year2022\Day17\Model\Point;

abstract class AbstractShape
{
    /** @var Point[] */
    public array $points;

    public function move(Direction $direction): static
    {
        $x = match ($direction) {
            Direction::Left => -1,
            Direction::Right => 1,
            default => 0,
        };
        $y = match ($direction) {
            Direction::Down => 1,
            default => 0,
        };

        $newShape = clone $this;
        $newShape->points = array_map(fn (Point $point): Point => new Point($point->x + $x, $point->y + $y), $this->points);

        return $newShape;
    }

    public function getHeight(): int
    {
        $minY = array_reduce($this->points, fn (int $min, Point $point): int => min($min, $point->y), PHP_INT_MAX);
        $maxY = array_reduce($this->points, fn (int $max, Point $point): int => max($max, $point->y), PHP_INT_MIN);

        return $maxY - $minY + 1;
    }
}
