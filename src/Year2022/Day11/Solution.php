<?php

namespace hxv\AoC\Year2022\Day11;

class Solution
{
    public function __invoke(): void
    {
        $monkeyCollection = $this->createMonkeyCollection();

        for ($i=0; $i<20; ++$i) {
            $monkeyCollection->runRound();
        }

        $results = [];
        foreach ($monkeyCollection->monkeys as $monkey) {
            echo "Monkey {$monkey->number} inspected items {$monkey->itemInspectionsCount} times.\n";
            $results[] = $monkey->itemInspectionsCount;
        }
        rsort($results);

        echo "{$results[0]} * {$results[1]} = " . gmp_mul($results[0], $results[1]) . "\n";

        // part 2

        $monkeyCollection = $this->createMonkeyCollection();

        for ($i=0; $i<10000; ++$i) {
            $monkeyCollection->runRound(false);

            echo '== After round ' . ($i + 1) . " ==\n";
            foreach ($monkeyCollection->monkeys as $monkey) {
                echo "Monkey {$monkey->number} inspected items {$monkey->itemInspectionsCount} times.\n";
            }
        }

        $results = [];
        foreach ($monkeyCollection->monkeys as $monkey) {
            echo "Monkey {$monkey->number} inspected items {$monkey->itemInspectionsCount} times.\n";
            $results[] = $monkey->itemInspectionsCount;
        }
        rsort($results);

        echo "{$results[0]} * {$results[1]} = " . gmp_mul($results[0], $results[1]) . "\n";
    }

    private function createMonkeyCollection(): MonkeyCollection
    {
        $input = file(__DIR__ . '/input');
        $input = array_map('rtrim', $input);

        $input = array_filter($input, fn (string $line): bool => $line !== '');
        $input = array_chunk($input, 6);

        $monkeyCollection = new MonkeyCollection();
        foreach ($input as $monkeyData) {
            $this->createMonkey($monkeyCollection, $monkeyData);
        }

        return $monkeyCollection;
    }

    private function createMonkey(MonkeyCollection $monkeyCollection, array $monkeyData): void
    {
        if (sscanf($monkeyData[0], 'Monkey %d:', $number) !== 1) {
            throw new \RuntimeException(sprintf('Invalid monkey definition "%s".', $monkeyData[0]));
        }

        if (sscanf($monkeyData[1], '  Starting items: %[^\\n]', $items) !== 1) {
            throw new \RuntimeException(sprintf('Invalid starting items "%s".', $monkeyData[1]));
        }
        $items = explode(', ', $items);

        if (sscanf($monkeyData[2], '  Operation: new = old %s %s', $operator, $value) !== 2) {
            throw new \RuntimeException(sprintf('Invalid operation "%s"', $monkeyData[2]));
        }

        if (sscanf($monkeyData[3], '  Test: divisible by %d', $divider) !== 1) {
            throw new \RuntimeException(sprintf('Invalid test "%s".', $monkeyData[3]));
        }

        if (sscanf($monkeyData[4], '    If true: throw to monkey %d', $ifTrue) !== 1) {
            throw new \RuntimeException(sprintf('Invalid "if true" behaviour "%s"', $monkeyData[4]));
        }

        if (sscanf($monkeyData[5], '    If false: throw to monkey %d', $ifFalse) !== 1) {
            throw new \RuntimeException(sprintf('Invalid "if false" behaviour "%s"', $monkeyData[5]));
        }

        $items = array_map([$this, 'createItem'], $items);

        $monkey = new Monkey($monkeyCollection, $number, $items, new Inspection($operator, $value), gmp_init($divider), $ifTrue, $ifFalse);

        $monkeyCollection->addMonkey($monkey);
    }

    private function createItem(string $worryLevel): Item
    {
        return new Item(gmp_init($worryLevel));
    }
}
