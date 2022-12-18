<?php

namespace hxv\AoC\Year2022\Day16;

class Solution
{
    public function __invoke(bool $test = false, bool $part2 = false): void
    {
        $valves = $this->getInput(__DIR__ . '/' . ($test ? 'test-input' : 'input'));

//        $this->dumpDistances($input);

        $startingValve = $valves['AA'];
        $startingValve->opened = true;

        $currentValves = $part2 ? [$startingValve, $startingValve] : [$startingValve];
        $spareTime = $part2 ? [26, 26] : [30];

        $member = -1;

        $paths = [];
        $openedValves = [];
        $finishedMembers = [];
        do {
            $member = ++$member % count($spareTime);

            if (null === $nextValve = $this->getNextBest($valves, $currentValves[$member], $spareTime[$member], $openedValves)[0] ?? null) {
                $finishedMembers[] = $member;
            }

            if (in_array($member, $finishedMembers, true)) {
                continue;
            }

            $openedValves[] = $nextValve;

            $paths[$member][] = $nextValve;

            $spareTime[$member] -= $nextValve->getTravelAndOpeningTime($currentValves[$member]);

            $currentValves[$member] = $nextValve;
        } while (count($finishedMembers) < count($spareTime));

//        $path = [$valves['DD'], $valves['BB'], $valves['JJ'], $valves['HH'], $valves['EE'], $valves['CC']];

        $spareTime = $part2 ? [26, 26] : [30];
        $currentValves = $part2 ? [$startingValve, $startingValve] : [$startingValve];
        $pressure = 0;

        foreach ($paths as $member => $nextValves) {
            foreach ($nextValves as $nextValve) {
                $spareTime[$member] -= $nextValve->getTravelAndOpeningTime($currentValves[$member]);

                $pressureGain = $nextValve->getPressureGain($spareTime[$member]);
                $pressure += $pressureGain;

                printf("%s valve %s, adding %d pressure (%d * %d).\n", $member === 0 ? 'I open' : 'Elephant opens', $nextValve, $pressureGain, $nextValve->flowRate, $spareTime[$member]);

                $currentValves[$member] = $nextValve;
            }
        }

        echo "{$pressure}\n";

        // part 1 test result = 1651
        // part 1 result = 1871

        // part 2 test result = 1707
        // part 2 result = ???
    }

    /**
     * @param Valve[] $valves
     * @param Valve[] $openedValves
     *
     * @return array{Valve, int, int}
     */
    private function getNextBest(array $valves, Valve $currentValve, int $spareTime, array $openedValves = []): array
    {
        $openedValves[] = $currentValve;

        /** @var array<array{Valve, int, int}> $possibilities */
        $possibilities = [];
        foreach ($valves as $targetValve) {
            if ($currentValve === $targetValve || in_array($targetValve, $openedValves, true)) {
                continue;
            }

            $travelTime = $targetValve->getTravelAndOpeningTime($currentValve);
            if ($travelTime > $spareTime) {
                continue;
            }

            if (0 === $pressureGain = $targetValve->getPressureGain($spareTime - $travelTime)) {
                continue;
            }

            $possibilities[] = [$targetValve, $pressureGain, $travelTime];
        }

        $best = null;
        foreach ($possibilities as $possibility) {
            [$valve, $pressureGain, $travelTime] = $possibility;
            $_travelTime = $travelTime;

            $bestForPossibility = $this->getNextBest($valves, $valve, $spareTime - $travelTime, $openedValves);

            $pressureGain += $bestForPossibility[1] ?? 0;
            $travelTime += $bestForPossibility[2] ?? 0;

            if ($best === null || $pressureGain > $best[1]) {
                $best = [$valve, $pressureGain, $travelTime];
            }

//            printf(
//                "%sHaving %d minutes left, and with valves %s opened, we can travel from valve %s to open valve %s. It'll cost us %d minutes, and give us %d pressure.\n",
//                str_repeat(' ', count($openedValves)),
//                $spareTime,
//                implode(', ', $openedValves),
//                $currentValve,
//                $valve,
//                $valve->getTravelAndOpeningTime($currentValve),
//                $valve->getPressureGain($spareTime - $_travelTime),
//            );
//            printf("%sTotal pressure for that path will be %d.\n", str_repeat(' ', count($openedValves)), $pressureGain);
        }

        return $best ?? [];
    }

    /**
     * @param Valve[] $valves
     */
    private function dumpDistances(array $valves): void
    {
        foreach ($valves as $node) {
            printf("Distances for valve %s: ", $node);

            foreach ($node->distances as $target => $distance) {
                printf("%s: %d, ", $target, $distance);
            }

            printf("\n");
        }
    }

    private function dumpOpenedValves(array $valves): void
    {
        $openValves = array_filter($valves, fn (Valve $valve): bool => $valve->name !== 'AA' && $valve->opened);
        if ($openValves === []) {
            printf("No valves are open.\n");
        } else {
            printf("Valves %s are open, releasing \e[1m%d\e[0m pressure.\n", implode(' and ', $openValves), array_reduce($openValves, fn (int $pressure, Valve $valve): int => $pressure + $valve->flowRate, 0));
        }
    }

    /**
     * @param string $file
     *
     * @return array<string, Valve>
     */
    private function getInput(string $file): array
    {
        $input = file($file);
        $input = array_map('rtrim', $input);

        $valves = [];
        $connect = [];
        foreach ($input as $line) {
            if (preg_match('/Valve (?<name>\\w+) has flow rate=(?<flowRate>\\d+); tunnels? leads? to valves? (?<neighbours>.+?)$/', $line, $matches) !== 1) {
                throw new \RuntimeException(sprintf('Invalid node definition "%s".', $line));
            }

            $name = $matches['name'];

            $valves[$name] = new Valve($name, $matches['flowRate']);
            $connect[$name] = explode(', ', $matches['neighbours']);
        }

        foreach ($connect as $valveName => $neighbourNames) {
            foreach ($neighbourNames as $neighbourName) {
                $valves[$valveName]->addNeighbour($valves[$neighbourName]);
            }
        }

        return $valves;
    }
}
