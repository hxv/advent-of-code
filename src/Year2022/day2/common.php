<?php

enum Shapes {
    case Rock;
    case Paper;
    case Scissors;

    public function getValue(): int
    {
        return match ($this) {
            self::Rock => 1,
            self::Paper => 2,
            self::Scissors => 3,
        };
    }

    public function getScore(self $opponent): int
    {
        if ($this->getDrawer() === $opponent) {
            return 3;
        }

        return $this->getLoser() === $opponent ? 6 : 0;
    }

    public function getDrawer(): self
    {
        return $this;
    }

    public function getWinner(): self
    {
        return match ($this) {
            self::Rock => self::Paper,
            self::Paper => self::Scissors,
            self::Scissors => self::Rock,
        };
    }

    public function getLoser(): self
    {
        return match ($this) {
            self::Rock => self::Scissors,
            self::Paper => self::Rock,
            self::Scissors => self::Paper,
        };
    }
}
