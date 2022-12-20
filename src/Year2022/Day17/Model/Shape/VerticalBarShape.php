<?php

namespace hxv\AoC\Year2022\Day17\Model\Shape;

use hxv\AoC\Year2022\Day17\Model\Point;

class VerticalBarShape extends AbstractShape
{
    public function __construct()
    {
        $this->points = [
            new Point(0, 0),
            new Point(0, 1),
            new Point(0, 2),
            new Point(0, 3),
        ];
    }
}
