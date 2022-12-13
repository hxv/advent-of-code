<?php

namespace hxv\AoC\Year2022\Day12;

use hxv\AoC\Grid\Color;
use hxv\AoC\Grid\Formatting;
use hxv\AoC\Grid\Grid;
use hxv\AoC\Grid\Point;

class Solution
{
    public function __invoke(): void
    {
        $input = $this->getInput(__DIR__ . '/input');
        $input = array_map('str_split', $input);

        $startPoint = $endPoint = null;
        $points = [];
        foreach ($input as $y => $line) {
            foreach ($line as $x => $label) {
                $point = new Point($y, $x, $label);

                $elevationChar = match ($label) {
                    'S' => 'a',
                    'E' => 'z',
                    default => $label,
                };
                $point->meta['elevation'] = ord($elevationChar) - ord('a');

                $points[] = $point;

                if (in_array($label, ['S', 'E'], true)) {
                    $point->formatting = Formatting::Blinking;
                }

                if ($label === 'S') {
                    $startPoint = $point;
                } elseif ($label === 'E') {
                    $endPoint = $point;
                }
            }
        }

        if ($startPoint === null) {
            throw new \RuntimeException('No start point.');
        }

        if ($endPoint === null) {
            throw new \RuntimeException('No end point.');
        }

        $grid = new Grid($points);

        echo $grid->export();
        usleep(0.5 * 1_000_000);

        $endPoint->meta['distance'] = 0;

        $lastPoints = [$endPoint];
        $distance = 1;
        while ($lastPoints !== []) {
            $newPoints = [];
            foreach ($lastPoints as $lastPoint) {
                foreach ($grid->getNeighbours($lastPoint) as $neighbour) {
                    if (isset($neighbour->meta['distance'])) {
                        continue;
                    }

                    if (!$neighbour->canTravelTo($lastPoint)) {
                        continue;
                    }

                    $neighbour->meta['distance'] = $distance;
                    $neighbour->color = Color::Blue;

                    /* part 1
                    if ($neighbour === $startPoint) {
                        break 3;
                    }
                    // /part 1 */

                    //* part 2
                    if ($neighbour->label === 'a' || $neighbour === $startPoint) {
                        $startPoint = $neighbour;
                        break 3;
                    }
                    // /part 2 */

                    $newPoints[] = $neighbour;
                }
            }

            $lastPoints = $newPoints;

            $distance++;

            echo $grid->export();
            usleep(0.1 * 1_000_000);
        }

        echo $grid->export();

        $steps = 0;
        $point = $startPoint;
        do {
            $closestNeighbour = null;
            foreach ($grid->getNeighbours($point) as $neighbour) {
                if (!isset($neighbour->meta['distance'])) {
                    continue;
                }

                if (!$point->canTravelTo($neighbour)) {
                    continue;
                }

                $closestNeighbour ??= $neighbour;

                if ($neighbour->meta['distance'] < $closestNeighbour->meta['distance']) {
                    $closestNeighbour = $neighbour;
                }
            }

            $point = $closestNeighbour;
            $point->color = Color::Red;

            $steps++;

            usleep(0.1 * 1_000_000);
            echo $grid->export();
        } while ($closestNeighbour !== null && $closestNeighbour !== $endPoint);

        echo $grid->export();

        echo "{$steps}\n";
    }

    private function getInput(string $filename): array
    {
        return array_map('rtrim', file($filename));
    }
}
