<?php

namespace hxv\AoC\Year2022\Day10;

class Solution
{
    public function __invoke(): void
    {
        $input = file(__DIR__ . '/input');
        $input = array_map('rtrim', $input);

        $result = 0;

        $display = new Display();
        $cpu = new CPU();
        $cpu->onTick(function (CPU $cpu) use (&$result): void {
            if ($cpu->getCycle() === 20 || ($cpu->getCycle() > 20 && ($cpu->getCycle()-20) % 40 === 0)) {
                $result += $cpu->getCycle() * $cpu->getX();
            }
        }, $display);

        foreach ($input as $line) {
            if ($line === 'noop') {
                $cpu->tick();
            } elseif (sscanf($line, 'addx %d', $value) === 1) {
                $cpu->tick();
                $cpu->tick();
                $cpu->addX($value);
            } else {
                throw new \RuntimeException(sprintf('Unknown instruction "%s".', $line));
            }
        }

        echo "{$result}\n";
        echo implode("\n", $display->getLines()) . "\n";
    }
}
