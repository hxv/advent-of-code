<?php

namespace hxv\AoC\Grid;

class Point
{
    public function __construct(
        public int $y,
        public int $x,
        public string $label,
        public array $meta = [],
        public ?Color $color = null,
        public ?Formatting $formatting = null,
    ) {
    }

    public function isDirectionTo(Point $point, Direction $direction): bool
    {
        return match ($direction) {
            Direction::Up => $this->x === $point->x && $this->y < $point->y,
            Direction::Down => $this->x === $point->x && $this->y > $point->y,
            Direction::Left => $this->y === $point->y && $this->x < $point->x,
            Direction::Right => $this->y === $point->y && $this->x > $point->x,
        };
    }

    public function isAdjacentTo(Point $point): bool
    {
        $vector = [$this->y - $point->y, $this->x - $point->x];

        return in_array($vector, [[1, 0], [-1, 0], [0, 1], [0, -1]], true);
    }

    public function isAdjacentInDirectionTo(Point $point, Direction $direction): bool
    {
        return $this->isAdjacentTo($point) && $this->isDirectionTo($point, $direction);
    }

    //

    public function canTravelTo(Point $point): bool
    {
        return $this->meta['elevation'] >= $point->meta['elevation'] - 1;
    }
}
