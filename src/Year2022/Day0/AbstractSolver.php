<?php

namespace hxv\AoC\Year2022\Day0;

abstract class AbstractSolver
{
    protected abstract function solve(bool $test, bool $part1): string;

    public function __invoke(bool $test, bool $part1): void
    {
        $output = $this->solve($test, $part1);

        $expectedOutput = $this->getExpectedOutput($test, $part1);

        if ($expectedOutput === '' || $output === '') {
            echo "\e[33m" . "Solver output: " . "\e[1m" . $output . "\e[0m\e[33m" . ", valid output: " . "\e[1m" . $expectedOutput . "\e[0m" . "\n";

            exit(1);
        }

        if ($output === $expectedOutput) {
            echo "\e[32m" . "Solver output: " . "\e[1m" . $output . "\e[0m" . "\n";

            exit(0);
        }

        echo "\e[31m" . "Solver output: " . "\e[1m" . $output . "\e[0m\e[31n" . ", valid output: " . "\e[1m" . $expectedOutput . "\e[0m" . "\n";

        exit(2);
    }

    abstract protected function getInput(bool $test): mixed;

    abstract protected function getExpectedOutput(bool $test, bool $part1): string;
}
