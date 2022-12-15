<?php

namespace hxv\AoC\Year2022\Day14;

use hxv\AoC\Grid\BetterGrid;
use hxv\AoC\Grid\BetterGridExporter;
use hxv\AoC\Grid\BetterPoint;
use hxv\AoC\Grid\Color;
use hxv\AoC\Grid\Direction;

class Solution
{
    private const SAND_MOVEMENT_SLEEP = 0.0001 * 1_000_000;

    private BetterGridExporter $gridExporter;

    public function __construct()
    {
        $this->gridExporter = new BetterGridExporter();
    }

    public function __invoke(): void
    {
        $input = $this->getInput(__DIR__ . '/input');

        $grid = new BetterGrid();

        $opening = $grid->createPoint(500, -1); // I can't have duplicate points on the grid, so create opening one point up – results should be the same
        $opening->label = '+';

        foreach ($input as $line) {
            $startPoint = $grid->getOrCreatePoint($line[0][0], $line[0][1]);
            $startPoint->label = '#';

            for ($i=1; $i<count($line); ++$i) {
                $endPoint = $grid->getOrCreatePoint($line[$i][0], $line[$i][1]);
                $endPoint->label = '#';

                $this->drawLine($grid, $startPoint, $endPoint);

                $startPoint = $endPoint;
            }
        }

        /* // part 1
        $voidY = $grid->getBoundaries()['y'][1] + 1;
        //*/// / part 1

        //* // part 2
        $boundaries = $grid->getBoundaries();
        $startPoint = $grid->createPoint($boundaries['x'][0] - 250, $boundaries['y'][1] + 2);
        $startPoint->label = '#';
        $endPoint = $grid->createPoint($boundaries['x'][1] + 250, $boundaries['y'][1] + 2);
        $endPoint->label = '#';
        $this->drawLine($grid, $startPoint, $endPoint);
        $voidY = $boundaries['y'][1] + 3;
        //*/// / part 2

//        usleep(self::SAND_MOVEMENT_SLEEP);
//        echo $this->gridExporter->export($grid);
//        die;

        $unitsOfSand = 0;
        while (true) {
            if ($grid->getPointAt($opening->x, $opening->y + 1) !== null) {
                break;
            }

            $sand = $grid->createPoint($opening->x, $opening->y + 1);
            $sand->label = 'o';
            $sand->color = Color::Yellow;

//            usleep(self::SAND_MOVEMENT_SLEEP);
//            echo $this->gridExporter->export($grid);

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

//                usleep(self::SAND_MOVEMENT_SLEEP);
//                echo $this->gridExporter->export($grid);
            }

//            usleep(self::SAND_MOVEMENT_SLEEP);
            if ($unitsOfSand % 100 === 0) {
                echo $this->gridExporter->export($grid);
            }

            ++$unitsOfSand;
        }

        echo $this->gridExporter->export($grid);

        echo "{$unitsOfSand}\n";
    }

    private function drawLine(BetterGrid $grid, BetterPoint $startPoint, BetterPoint $endPoint): void
    {
        if ($startPoint->x === $endPoint->x) {
            $startX = $endX = $startPoint->x;
            $startY = min($startPoint->y, $endPoint->y) + 1;
            $endY = max($startPoint->y, $endPoint->y) - 1;
        } elseif ($startPoint->y === $endPoint->y) {
            $startX = min($startPoint->x, $endPoint->x) + 1;
            $endX = max($startPoint->x, $endPoint->x) - 1;
            $startY = $endY = $startPoint->y;
        } else {
            throw new \RuntimeException(sprintf("Can't draw line from point %s to point %s.", $startPoint, $endPoint));
        }

        for ($x=$startX; $x<=$endX; ++$x) {
            for ($y=$startY; $y<=$endY; ++$y) {
                $point = $grid->getOrCreatePoint($x, $y);
                $point->label = '#';
            }
        }
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
