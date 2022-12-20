<?php

namespace hxv\AoC\Year2022\Day17\Model;

use hxv\AoC\Year2022\Day17\Model\Shape\AbstractShape;
use hxv\AoC\Year2022\Day17\Model\Shape\HorizontalBarShape;

class Chamber
{
    /** @var array<int, array<int, string>> */
    public array $points = [];

    private ?AbstractShape $currentShape = null;

    public int $height;

    public function __construct(public int $width = 7)
    {
        for ($x=0; $x<$this->width; ++$x) {
            $this->points[$x][0] = '#';
        }

        $this->height = 1;
    }

    public function setCurrentShape(?AbstractShape $shape): void
    {
        if ($this->currentShape !== null) {
            $this->freezeCurrentShape();
        }

        $this->currentShape = $shape;

        if ($this->currentShape !== null) {
            $this->fixSpaceOnTop($shape->getHeight() + 3);
        }
    }

    /**
     * @return array<int, array<int, Point>>
     */
    public function getCurrentShapePoints(): array
    {
        if ($this->currentShape === null) {
            return [];
        }

        $points = [];
        foreach ($this->currentShape->points as $point) {
            $points[$point->x][$point->y] = $point;
        }

        return $points;
    }

    private function fixSpaceOnTop(int $requestedSpace): void
    {
        $minY = PHP_INT_MAX;
        for ($x=0; $x<$this->width; ++$x) {
            if (!isset($this->points[$x])) {
                continue;
            }

            $minY = min($minY, min(array_keys($this->points[$x])));
        }

        $missingSpace = $requestedSpace - $minY;

        $this->points = array_map(
            fn (array $line): array => array_combine(
                array_map(fn (int $y): int => $y + $missingSpace, array_keys($line)),
                array_values($line),
            ),
            $this->points,
        );

        $this->height += $missingSpace;
    }

    public function moveCurrentShape(Direction $direction): bool
    {
        if ($this->currentShape === null) {
            throw new \LogicException('There is no current shape.');
        }

        $newShape = $this->currentShape->move($direction);

        $result = true;
        $wallHit = false;

        foreach ($newShape->points as $point) {
            if (isset($this->points[$point->x][$point->y])) {
                $result = false;
            }

            if ($point->x < 0 || $point->x >= $this->width) {
                $wallHit = true;
            }
        }

        if ($result && !$wallHit) {
            $this->currentShape = $newShape;
        }

        return $result;
    }

    private function freezeCurrentShape(): void
    {
        if ($this->currentShape === null) {
            throw new \LogicException('There is no current shape.');
        }

        foreach ($this->currentShape->points as $point) {
            $this->points[$point->x][$point->y] = '#';
        }

        $this->currentShape = null;
    }

    /**
     * @return Point[]
     */
    private function getShiftedPoints(int $x, int $y): array
    {
        return array_map(fn (Point $point): Point => new Point($point->x + $x, $point->y + $y), $this->points);
    }
}
