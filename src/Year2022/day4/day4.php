<?php

$input = file(__DIR__ . '/input');
$input = array_map('rtrim', $input);

$result = 0;
$result2 = 0;
foreach ($input as $line) {
    [$elfA, $elfB] = explode(',', $line);

    [$elfAStart, $elfAEnd] = explode('-', $elfA);
    [$elfBStart, $elfBEnd] = explode('-', $elfB);

    $elfA = array_keys(array_fill($elfAStart, $elfAEnd - $elfAStart + 1, null));
    $elfB = array_keys(array_fill($elfBStart, $elfBEnd - $elfBStart + 1, null));

    $common = array_intersect($elfA, $elfB);

    if (count($common) === count($elfA) || count($common) === count($elfB)) {
        ++$result;
    }

    if (count($common) !== 0) {
        ++$result2;
    }
}

echo "{$result}\n";
echo "{$result2}\n";
