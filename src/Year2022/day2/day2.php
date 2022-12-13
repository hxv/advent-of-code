<?php

require __DIR__ . '/common.php';

$input = file(__DIR__ . '/input');

$score = 0;
foreach ($input as $line) {
    $line = trim($line);

    sscanf($line, '%s %s', $opponent, $me);

    $opponent = match ($opponent) {
        'A' => Shapes::Rock,
        'B' => Shapes::Paper,
        'C' => Shapes::Scissors,
        default => throw new LogicException(sprintf('Unknown shape %s for opponent.', $opponent)),
    };

    $me = match ($me) {
        'X' => Shapes::Rock,
        'Y' => Shapes::Paper,
        'Z' => Shapes::Scissors,
        default => throw new LogicException(sprintf('Unknown shape %s for me.', $me)),
    };

    //

    $score += $me->getValue() + $me->getScore($opponent);
}

printf("My score is %d.\n", $score);
