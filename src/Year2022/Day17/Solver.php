<?php

namespace hxv\AoC\Year2022\Day17;

use hxv\AoC\Year2022\Day0\AbstractSolver;
use hxv\AoC\Year2022\Day17\Model\Chamber;
use hxv\AoC\Year2022\Day17\Model\Direction;
use hxv\AoC\Year2022\Day17\Model\JetStream;
use hxv\AoC\Year2022\Day17\Model\ShapeQueue;

class Solver extends AbstractSolver
{
    protected function solve(bool $test, bool $part1): string
    {
        $shapeQueue = new ShapeQueue();
        $jetStream = new JetStream($this->getInput($test));

        $chamber = new Chamber();

        for ($i=0; $i<($part1 ? 2022 : 1_000_000_000_000); ++$i) {
            $chamber->setCurrentShape($shapeQueue->getNextShape());

//            $this->dumpChamber($chamber, $i);

            do {
                $shiftDirection = $jetStream->getNextPush();

                $chamber->moveCurrentShape($shiftDirection);

//                $this->dumpChamber($chamber, $i);

                $moved = $chamber->moveCurrentShape(Direction::Down);

//                $this->dumpChamber($chamber, $i);
            } while ($moved);
        }

        $chamber->setCurrentShape(null);

//        $this->dumpChamber($chamber, $i);

        $ys = array_merge(...array_map(fn (array $line): array => array_keys($line), $chamber->points));

        return max($ys) - min($ys);
    }

    private function dumpChamber(Chamber $chamber, int $rockNumber): void
    {
//        if ($rockNumber % 10 !== 0) {
//            return;
//        }

        usleep(1 * 1_000_000);

        echo "\e[H\e[J"; // clear screen

        $currentShapePoints = $chamber->getCurrentShapePoints();

        for ($y=0; $y<50; ++$y) {
            for ($x=0; $x<$chamber->width; ++$x) {
                if ($x === 0) {
                    echo '|';
                }

                if (isset($currentShapePoints[$x][$y])) {
                    echo '@';
                } elseif (isset($chamber->points[$x][$y])) {
                    echo '#';
                } elseif ($y < $chamber->height) {
                    echo '.';
                } else {
                    echo ' ';
                }

                if ($x === $chamber->width - 1) {
                    echo '|';
                }
            }

            if ($y === 0) {
                echo "  {$rockNumber}";
            }

            echo "\n";
        }

        echo "\n";
    }

    protected function getInput(bool $test): array
    {
        $filename = __DIR__ . '/data/' . ($test ? 'test-input' : 'input');

        $input = file($filename);
        $input = array_map('rtrim', $input);

        return str_split($input[0]);
    }

    protected function getExpectedOutput(bool $test, bool $part1): string
    {
        $filename = __DIR__ . '/data/' . ($test ? 'test-output' : 'output');

        $lines = file($filename);

        return rtrim($lines[0] ?? '');
    }
}
