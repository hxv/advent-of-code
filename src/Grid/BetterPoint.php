<?php

namespace hxv\AoC\Grid;

class BetterPoint
{
    public ?string $label = null;

    public ?Color $color = null; // TODO – should this be here?

    public function __construct(
        public readonly BetterGrid $grid,
        public readonly int $x,
        public readonly int $y,
    ) {
        $this->grid->addPoint($this);
    }

    public function getNeighbour(Direction $direction, ?Direction $secondDirection = null): ?BetterPoint
    {
        $x = $this->x;
        $y = $this->y;

        foreach ([$direction, $secondDirection] as $dir) {
            match ($dir) {
                Direction::Up => $y--,
                Direction::Down => $y++,
                Direction::Left => $x--,
                Direction::Right => $x++,
                null => null,
                default => throw new \LogicException(sprintf('Unknown direction %s.', $dir)),
            };
        }

        return $this->grid->getPointAt($x, $y);
    }

    public function moveTo(int $x, int $y): BetterPoint
    {
        $this->grid->removePoint($this);

        return $this->duplicate($x, $y);
    }

    public function moveInDirection(Direction $direction, ?Direction $secondDirection = null): BetterPoint
    {
        $x = $this->x;
        $y = $this->y;

        foreach ([$direction, $secondDirection] as $dir) {
            match ($dir) {
                Direction::Up => $y--,
                Direction::Down => $y++,
                Direction::Left => $x--,
                Direction::Right => $x++,
                null => null,
                default => throw new \LogicException(sprintf('Unknown direction %s.', $dir?->value)),
            };
        }

        return $this->moveTo($x, $y);
    }

    public function duplicate(int $x, int $y): BetterPoint
    {
        $point = new BetterPoint($this->grid, $x, $y);
        $point->label = $this->label;
        $point->color = $this->color;

        return $point;
    }

    public function __toString(): string
    {
        return "({$this->x},{$this->y})";
    }
}
