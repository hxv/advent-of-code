<?php

namespace hxv\AoC\Grid;

use RuntimeException;

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

    public function drawLine(BetterPoint $startPoint, BetterPoint $endPoint, ?string $label = null): self
    {
        if ($startPoint->x === $endPoint->x) {
            $x = ['start' => $startPoint->x, 'end' => $endPoint->x];

            $y = [
                'start' => min($startPoint->y, $endPoint->y) + 1,
                'end' => max($startPoint->y, $endPoint->y) - 1,
            ];
        } elseif ($startPoint->y === $endPoint->y) {
            $x = [
                'start' => min($startPoint->x, $endPoint->x) + 1,
                'end' => max($startPoint->x, $endPoint->x) - 1,
            ];

            $y = ['start' => $startPoint->y, 'end' => $endPoint->y];
        } else {
            throw new RuntimeException(sprintf("Can't draw line from point %s to point %s.", $startPoint, $endPoint));
        }

        for ($_x=$x['start']; $_x<=$x['end']; ++$_x) {
            for ($_y=$y['start']; $_y<=$y['end']; ++$_y) {
                if ($this->getPointAt($_x, $_y) !== null) {
                    continue;
                }

                $newPoint = $startPoint->duplicate($_x, $_y);
                $newPoint->label = $label;
            }
        }

        return $this;
    }
}
