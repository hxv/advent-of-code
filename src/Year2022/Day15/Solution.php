<?php

namespace hxv\AoC\Year2022\Day15;

use hxv\AoC\Grid\BetterGrid;
use hxv\AoC\Grid\BetterPoint;
use hxv\AoC\Grid\PreviewGridDecorator;

class Solution
{
    public function __invoke(bool $test = false, bool $part2 = false): void
    {
        $input = $this->getInput(__DIR__ . ($test ? '/test-input' : '/input'));

        $grid = new BetterGrid();
//        $grid = new PreviewGridDecorator($grid, ['delay' => 0.1]);

        $targetY = $test ? 10 : 2_000_000;

        foreach ($input as $line) {
            $sensor = $grid->createPoint($line['sensor']['x'], $line['sensor']['y']);
            $sensor->label = 'S';

            $beacon = $grid->getOrCreatePoint($line['beacon']['x'], $line['beacon']['y']);
            $beacon->label = 'B';

            $distance = $this->getDistance($sensor, $beacon);
            printf("Distance from a sensor %s to a beacon %s is %d.\n", $sensor, $beacon, $distance);

            if (null === $xBoundaries = $this->getXBoundariesWithinDistanceOnX($sensor, $distance, $targetY)) {
                printf("No boundary points at y=%d.\n\n", $targetY);

                continue;
            }

            printf("Boundary points at y=%d are %d and %d.\n\n", $targetY, $xBoundaries[0], $xBoundaries[1]);

            $boundaryStart = $grid->getOrCreatePoint($xBoundaries[0], $targetY, '#');
            $boundaryEnd = $grid->getOrCreatePoint($xBoundaries[1], $targetY, '#');

            $grid->drawLine($boundaryStart, $boundaryEnd, '#');
        }

        $boundariesX = $grid->getBoundaries()['x'];

        $result = 0;
        for ($x=$boundariesX['min']; $x<=$boundariesX['max']; ++$x) {
            if (null === $point = $grid->getPointAt($x, $targetY)) {
                continue;
            }

            $result += $point->label === '#';
        }

        echo "{$result}\n";

        // part 1 test result = 26
        // part 1 result = 5525990

        // part 2 test result = 56000011
        // part 2 result = ???
    }

    private function getDistance(BetterPoint $pointA, BetterPoint $pointB): int
    {
        return abs($pointA->x - $pointB->x) + abs($pointA->y - $pointB->y);
    }

    /**
     * @param BetterPoint $point
     *
     * @return null|array{int, int}
     */
    private function getXBoundariesWithinDistanceOnX(BetterPoint $point, int $distance, int $targetY): ?array
    {
        $yD = abs($point->y - $targetY);
        if ($yD >= $distance) {
            return null;
        }

        return [
            -$distance + $yD + $point->x,
            $distance - $yD + $point->x,
        ];
    }

    /**
     * @return array<array{sensor: array{x: int, y:int}, beacon: array{x: int, y:int}>
     */
    private function getInput(string $filename): array
    {
        $input = file($filename);
        $input = array_map('trim', $input);

        $positions = [];
        foreach ($input as $line) {
            if (sscanf($line, 'Sensor at x=%d, y=%d: closest beacon is at x=%d, y=%d', $sensorX, $sensorY, $beaconX, $beaconY) !== 4) {
                throw new \RuntimeException(sprintf("Can't parse a line %s.", $line));
            }

            $positions[] = [
                'sensor' => ['x' => $sensorX, 'y' => $sensorY],
                'beacon' => ['x' => $beaconX, 'y' => $beaconY],
            ];
        }

        return $positions;
    }
}
