<?php

namespace hxv\AoC\Year2022\Day14;

use hxv\AoC\Grid\BetterGrid;
use hxv\AoC\Grid\BetterPoint;
use hxv\AoC\Grid\Color;
use hxv\AoC\Grid\Direction;
use hxv\AoC\Grid\GridInterface;
use hxv\AoC\Grid\PreviewGridDecorator;

class Solution
{
    public function __invoke(): void
    {
        $input = $this->getInput(__DIR__ . '/input');

        $grid = new BetterGrid();
        $grid = new PreviewGridDecorator($grid, ['fps' => 7]);

        $opening = $grid->createPoint(500, -1); // I can't have duplicate points on the grid, so create opening one point up – results should be the same
        $opening->label = '+';

        foreach ($input as $line) {
            $startPoint = $grid->getOrCreatePoint($line[0][0], $line[0][1]);
            $startPoint->label = '#';

            for ($i=1; $i<count($line); ++$i) {
                $endPoint = $grid->getOrCreatePoint($line[$i][0], $line[$i][1]);
                $endPoint->label = '#';

                $grid->drawLine($startPoint, $endPoint, '#');

                $startPoint = $endPoint;
            }
        }

        /* // part 1
        $voidY = $grid->getBoundaries()['y']['max'] + 1;
        //*/// / part 1

        //* // part 2
        $boundaries = $grid->getBoundaries();
        $startPoint = $grid->createPoint($boundaries['x']['min'] - 250, $boundaries['y']['max'] + 2);
        $startPoint->label = '#';
        $endPoint = $grid->createPoint($boundaries['x']['max'] + 250, $boundaries['y']['max'] + 2);
        $endPoint->label = '#';
        $grid->drawLine($startPoint, $endPoint, '#');
        $voidY = $boundaries['y']['max'] + 3;
        //*/// / part 2

        $unitsOfSand = 0;
        while (true) {
            if ($grid->getPointAt($opening->x, $opening->y + 1) !== null) {
                break;
            }

            $sand = $grid->createPoint($opening->x, $opening->y + 1);
            $sand->label = 'o';
            $sand->color = Color::Yellow;

            while (true) {
                $moveDirections = null;

                foreach ([[Direction::Down], [Direction::Down, Direction::Left], [Direction::Down, Direction::Right]] as $directions) {
                    if ($sand->getNeighbour(...$directions) === null) {
                        $moveDirections = $directions;

                        break;
                    }
                }

                if ($moveDirections === null) {
                    break;
                }

                $sand = $sand->moveInDirection(...$moveDirections);

                if ($sand->y >= $voidY) {
                    $sand->color = Color::Red;

                    break 2;
                }
            }

            ++$unitsOfSand;
        }

        echo "{$unitsOfSand}\n";
    }

    /**
     * @param string $filename
     *
     * @return list<list<array{int, int}>>
     */
    private function getInput(string $filename): array
    {
        $input = file($filename);
        $input = array_map('rtrim', $input);
        $input = array_map(
            fn(string $line): array => array_map(
                fn(string $positions): array => explode(',', $positions),
                explode(' -> ', $line)
            ),
            $input
        );

        return $input;
    }
}
