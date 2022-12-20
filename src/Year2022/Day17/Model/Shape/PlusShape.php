<?php

namespace hxv\AoC\Year2022\Day17\Model\Shape;

use hxv\AoC\Year2022\Day17\Model\Point;

class PlusShape extends AbstractShape
{
    public function __construct()
    {
        $this->points = [
            new Point(1, 0),
            new Point(0, 1),
            new Point(1, 1),
            new Point(2, 1),
            new Point(1, 2),
        ];
    }
}
