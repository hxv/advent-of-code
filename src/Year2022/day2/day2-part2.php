<?php

require __DIR__ . '/common.php';

enum Strategies {
    case Win;
    case Lose;
    case Draw;

    public function getShapeFor(Shapes $opponent): Shapes
    {
        return match ($this) {
            self::Win => $opponent->getWinner(),
            self::Lose => $opponent->getLoser(),
            self::Draw => $opponent->getDrawer(),
        };
    }
}

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

    $strategy = match ($me) {
        'X' => Strategies::Lose,
        'Y' => Strategies::Draw,
        'Z' => Strategies::Win,
        default => throw new LogicException(sprintf('Unknown strategy %s for me.', $me)),
    };

    //

    $me = $strategy->getShapeFor($opponent);

    $score += $me->getValue() + $me->getScore($opponent);
}

printf("My score is %d.\n", $score);
