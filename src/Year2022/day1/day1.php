<?php

$input = file(__DIR__ . '/input');

$elves = [];
$currentElf = 0;

foreach ($input as $line) {
    if ('' === $line = trim($line)) {
        $elves[] = $currentElf;

        $currentElf = 0;
    } else {
        $currentElf += (int) $line;
    }
}

rsort($elves);

printf("Elf carrying most calories is carrying %d calories.\n", $elves[0]);

$top3Elves = 0;
for ($i=0; $i<3; ++$i) {
    $top3Elves += $elves[$i];
}

printf("Top 3 elves are carrying %d calories in total.\n", $top3Elves);
