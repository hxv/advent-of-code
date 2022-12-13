<?php

require __DIR__ . '/common.php';

$input = file(__DIR__ . '/input');
$input = array_map('rtrim', $input);
$input = array_map('str_split', $input);

$forest = new Forest();
foreach ($input as $y => $line) {
    foreach ($line as $x => $height) {
        $tree = new Tree($y, $x, $height);

        $forest->addTree($tree);
    }
}

$result = 0;
$result2 = 0;
foreach ($forest->getTrees() as $tree) {
    if ($forest->isVisible($tree)) {
        ++$result;
    }

    $result2 = max($result2, $forest->getScenicScore($tree));
}

echo "{$result}\n";
echo "{$result2}\n";
