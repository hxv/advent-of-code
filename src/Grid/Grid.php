<?php

namespace hxv\AoC\Grid;

class Grid
{
    /** @var array<int, array<int, Point>> */
    private array $points = [];

    private int $minY;

    private int $maxY;

    private int $minX;

    private int $maxX;

    /**
     * @param non-empty-array<Point> $points
     */
    public function __construct(array $points)
    {
        foreach ($points as $point) {
            $this->addPoint($point);
        }
    }

    public function addPoint(Point $point): void
    {
        if (isset($this->points[$point->y][$point->x])) {
            throw new \RuntimeException(sprintf('Point already exists at coordinates (%d,%d).', $point->y, $point->x));
        }

        $this->points[$point->y][$point->x] = $point;

        ksort($this->points[$point->y]);
        ksort($this->points);

        $this->minY = isset($this->minY) ? min($this->minY, $point->y) : $point->y;
        $this->maxY = isset($this->maxY) ? max($this->maxY, $point->y) : $point->y;
        $this->minX = isset($this->minX) ? min($this->minX, $point->x) : $point->x;
        $this->maxX = isset($this->maxX) ? max($this->maxX, $point->x) : $point->x;
    }

    public function getPointAt(int $y, int $x): Point
    {
        return $this->points[$y][$x];
    }

    /**
     * @return Point[]
     */
    public function getNeighbours(Point $point): array
    {
        return array_filter([
            $this->points[$point->y - 1][$point->x] ?? null,
            $this->points[$point->y + 1][$point->x] ?? null,
            $this->points[$point->y][$point->x - 1] ?? null,
            $this->points[$point->y][$point->x + 1] ?? null,
        ]);
    }

    public function export(): string
    {
        $ret = "\e[H\e[J";

        for ($y=$this->minY; $y<=$this->maxY; ++$y) {
            if ($y === $this->minY) {
                $ret .= "y=" . str_pad($this->minY, max(strlen($this->minY), strlen($this->maxY))) . " ↓ ";
            } elseif($y === $this->maxY) {
                $ret .= "  {$this->maxY} ↓ ";
            } else {
                $ret .= '  ' . str_repeat(' ', max(strlen($this->minY), strlen($this->maxY))) . ' ↓ ';
            }

            for ($x=$this->minX; $x<=$this->maxX; ++$x) {
                if (null === $point = $this->points[$y][$x]) {
                    $ret .= ' ';

                    continue;
                }

                $color = match ($point->color) {
                    Color::Black => "\e[30m",
                    Color::Red => "\e[31m",
                    Color::Green => "\e[32m",
                    Color::Yellow => "\e[33m",
                    Color::Blue => "\e[34m",
                    Color::Magenta => "\e[35m",
                    Color::Cyan => "\e[36m",
                    Color::White => "\e[37m",
                    null => '',
                };

                $formatting = match ($point->formatting) {
                    Formatting::Bold => "\e[1m",
                    Formatting::Dim => "\e[2m",
                    Formatting::Italic => "\e[3m",
                    Formatting::Underline => "\e[4m",
                    Formatting::Blinking => "\e[5m",
                    null => '',
                };

                $ret .= "{$color}{$formatting}{$point->label}\e[0m";
            }
            $ret .= "\n";
        }

        $ret .= '  ' . str_repeat(' ', max(strlen($this->minY), strlen($this->maxY))) . "   " . str_repeat('→', $this->maxX - $this->minX + 1) . " \n";
        $ret .= "    x={$this->minX}      {$this->maxX}\n";

        return $ret;
    }
}
