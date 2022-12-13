<?php

require __DIR__ . '/common.php';

$input = file(__DIR__ . '/input');
$input = array_map('rtrim', $input);

$tailPositions = [];
$rope = new Rope(new Position('H'), new Position('T'));
foreach ($input as $line) {
    sscanf($line, '%s %d', $direction, $steps);

    $direction = Direction::from($direction);

    $rope->applyMotion($direction, $steps, function (Rope $rope) use (&$tailPositions): void {
        $tailPositions["{$rope->getTail()->y},{$rope->getTail()->x}"] = true;
//        (new Printer())->print($rope);
//        sleep(1);
    });
}

echo count($tailPositions) . "\n";

// part 2

$tailPositions = [];
$rope = new Rope(new Position('H'), new Position('1'), new Position('2'), new Position('3'), new Position('4'), new Position('5'), new Position('6'), new Position('7'), new Position('8'), new Position('9'));
foreach ($input as $line) {
    sscanf($line, '%s %d', $direction, $steps);

    $direction = Direction::from($direction);

    $rope->applyMotion($direction, $steps, function (Rope $rope) use (&$tailPositions): void {
        $tailPositions["{$rope->getTail()->y},{$rope->getTail()->x}"] = true;
//        (new Printer())->print($rope);
//        sleep(1);
    });
}

echo count($tailPositions) . "\n";
