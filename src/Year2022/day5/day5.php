<?php

$input = file(__DIR__ . '/input');
$input = array_map('rtrim', $input);

$stacks = [];
$stacks2 = [];
foreach ($input as $line) {
    if (str_contains($line, '[')) {
        $rawCrates = str_split($line, 4);

        foreach ($rawCrates as $stack => $rawCrate) {
            $crate = null;
            sscanf($rawCrate, '[%1s]', $crate);

            if ($crate !== null) {
                $stacks[$stack + 1][] = $crate;
            }
        }
    } elseif ($line === '') {
        ksort($stacks);
        $stacks2 = $stacks;
    } elseif (str_starts_with($line, 'move')) {
        if (sscanf($line, 'move %d from %d to %d', $count, $from, $to) === -1) {
            throw new RuntimeException(sprintf('Invalid move command "%s".', $line));
        }

        for ($i=1; $i<=$count; ++$i) {
            $crate = array_shift($stacks[$from]);
            array_unshift($stacks[$to], $crate);
        }

        $crates = array_splice($stacks2[$from], 0, $count);
        array_splice($stacks2[$to], 0, 0, $crates);
    }
}

foreach ($stacks as $stack) {
    echo $stack[0];
}
echo "\n";

foreach ($stacks2 as $stack) {
    echo $stack[0];
}
echo "\n";
