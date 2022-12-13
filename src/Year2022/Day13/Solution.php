<?php

namespace hxv\AoC\Year2022\Day13;

class Solution
{
    public function __invoke(): void
    {
        $input = $this->getInput(__DIR__ . '/input');

        $sum = 0;
        for ($i=0; $i<count($input); $i+=2) {
            $left = $input[$i];
            $right = $input[$i+1];

            $pair = ($i / 2) + 1;

            printf("== Pair %d ==\n", $pair);

            if (null === $result = $this->compare($left, $right)) {
                throw new \RuntimeException(sprintf('Undefined result for %s vs %s.', json_encode($left), json_encode($right)));
            }

            if ($result) {
                $sum += ($i / 2) + 1;
            }

            echo "\n";
        }

        echo "{$sum}\n";
        echo "\n";

        // part 2

        $input[] = [[2]];
        $input[] = [[6]];

        usort($input, fn (mixed $packetA, mixed $packetB): int => $this->compare($packetB, $packetA) ? 1 : -1);

        for ($i=0; $i<count($input); $i++) {
            echo json_encode($input[$i]) . "\n";
        }

        echo ((array_search([[2]], $input) + 1) * (array_search([[6]], $input) + 1)) . "\n";
    }

    private function compare(mixed $left, mixed $right, string $pad = '- '): ?bool
    {
        printf("%sCompare %s vs %s\n", $pad, json_encode($left), json_encode($right));

        if (is_int($left) && is_array($right)) {
            printf("  %sMixed types; convert left to [%d] and retry comparison\n", $pad, $left);

            $left = [$left];
        }

        if (is_array($left) && is_int($right)) {
            printf("%sMixed types; convert right to [%d] and retry comparison\n", $pad, $right);

            $right = [$right];
        }

        if (is_int($left) && is_int($right)) {
            if ($left === $right) {
                return null;
            }

            if ($left < $right) {
                printf("  %sLeft side is smaller, so inputs are \e[1min the right order\e[0m\n", $pad);

                return true;
            } else {
                printf("  %sRight side is smaller, so inputs are \e[1mnot\e[0m in the right order\n", $pad);

                return false;
            }
        }

        if (is_array($left) && is_array($right)) {
            foreach ($left as $offset => $value) {
                if (!isset($right[$offset])) {
                    printf("  %sRight side run out = false\n", $pad);

                    return false;
                }

                $result = $this->compare($value, $right[$offset], '  ' . $pad);
                if (is_bool($result)) {
                    return $result;
                }
            }

            if (count($left) === count($right)) {
                return null;
            }

            printf("  %sLeft side run out of items, so inputs are \e[1min the right order\e[0m\n", $pad);

            return true;
        }

        throw new \RuntimeException("Can\'t compare values.");
    }

    private function getInput(string $file): array
    {
        $input = file($file);
        $input = array_map('rtrim', $input);
        $input = array_filter($input, fn (int $offset): bool => $offset % 3 !== 2, ARRAY_FILTER_USE_KEY);
        $input = array_map('json_decode', $input);
        $input = array_values($input);

        return $input;
    }
}
