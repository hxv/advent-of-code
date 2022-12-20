<?php

namespace hxv\AoC\Year2022\Day17\Model\Shape;

use hxv\AoC\Year2022\Day17\Model\Point;

class InvertedLShape extends AbstractShape
{
    public function __construct()
    {
        $this->points = [
            new Point(2, 0),
            new Point(2, 1),
            new Point(0, 2),
            new Point(1, 2),
            new Point(2, 2),
        ];
    }
}
