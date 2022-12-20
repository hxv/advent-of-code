<?php

namespace hxv\AoC\Year2022\Day17\Model;

use hxv\AoC\Year2022\Day17\Model\Shape\AbstractShape;
use hxv\AoC\Year2022\Day17\Model\Shape\HorizontalBarShape;
use hxv\AoC\Year2022\Day17\Model\Shape\InvertedLShape;
use hxv\AoC\Year2022\Day17\Model\Shape\PlusShape;
use hxv\AoC\Year2022\Day17\Model\Shape\SquareShape;
use hxv\AoC\Year2022\Day17\Model\Shape\VerticalBarShape;

class ShapeQueue
{
    /** @var list<AbstractShape> */
    private array $shapes;

    private int $pointer = 0;

    public function __construct()
    {
        $this->shapes = [
            new HorizontalBarShape(),
            new PlusShape(),
            new InvertedLShape(),
            new VerticalBarShape(),
            new SquareShape(),
        ];
    }

    public function getNextShape(): AbstractShape
    {
        $shape = $this->shapes[$this->pointer];

        $this->pointer = ($this->pointer + 1) % count($this->shapes);

        return $shape->move(Direction::Right)->move(Direction::Right);
    }
}
