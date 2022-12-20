<?php

namespace hxv\AoC\Year2022\Day17\Model\Shape;

use hxv\AoC\Year2022\Day17\Model\Point;

class SquareShape extends AbstractShape
{
    public function __construct()
    {
        $this->points = [
            new Point(0, 0),
            new Point(1, 0),
            new Point(0, 1),
            new Point(1, 1),
        ];
    }
}
