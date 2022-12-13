<?php

enum Direction: string {
    case Up = 'U';
    case Down = 'D';
    case Left = 'L';
    case Right = 'R';
}

class Position
{
    public function __construct(
        public string $name,
        public int $y = 0,
        public int $x = 0,
    ) {
    }

    public function move(Direction $direction, ?Direction $additionalDirection = null): void
    {
        match ($direction) {
            Direction::Up => $this->y++,
            Direction::Down => $this->y--,
            Direction::Right => $this->x++,
            Direction::Left => $this->x--,
        };

        if ($additionalDirection !== null) {
            $this->move($additionalDirection);
        }
    }

    public function follow(Position $head): void
    {
        $vector = [$head->y - $this->y, $head->x - $this->x];

//        fputs(STDERR, "vector for {$this->name} = [{$vector[0]},{$vector[1]}]\n");

        match ($vector) {
            [0, 2] => $this->move(Direction::Right),
            [0, -2] => $this->move(Direction::Left),
            [2, 0] => $this->move(Direction::Up),
            [-2, 0] => $this->move(Direction::Down),
            [2, 1], [1, 2], [2, 2] => $this->move(Direction::Up, Direction::Right),
            [2, -1], [1, -2], [2, -2] => $this->move(Direction::Up, Direction::Left),
            [-1, 2], [-2, 1], [-2, 2] => $this->move(Direction::Down, Direction::Right),
            [-1, -2], [-2, -1], [-2, -2] => $this->move(Direction::Down, Direction::Left),
            default => null,
        };
    }
}

class Rope
{
    /** @var list<Position> */
    public array $positions;

    public function __construct(Position ...$positions)
    {
        $this->positions = $positions;
    }

    public function applyMotion(Direction $direction, int $steps, ?callable $callback = null): void
    {
        for ($i=0; $i<$steps; ++$i) {
            $this->positions[0]->move($direction);

            for ($p=1; $p<count($this->positions); ++$p) {
                $this->positions[$p]->follow($this->positions[$p - 1]);
            }

            if ($callback !== null) {
                $callback($this);
            }
        }
    }

    public function getHead(): Position
    {
        return $this->positions[0];
    }

    public function getTail(): Position
    {
        return $this->positions[count($this->positions) - 1];
    }
}

class Printer
{
    public function print(Rope $rope): void
    {
        echo "\e[H\e[J\e[3J";

        for ($y=9; $y>-10; --$y) { // we start drawing Y from the top, so higher values are presented first
            for ($x=-10; $x<10; ++$x) { // we start drawing X from the left, so lower values are presented first
                $point = '.';

                if ($y === 0 && $x === 0) {
                    $point = 's';
                }

                for ($i=count($rope->positions) - 1; $i>=0; --$i) {
                    if ($rope->positions[$i]->y === $y && $rope->positions[$i]->x === $x) {
                        $point = $rope->positions[$i]->name;
                    }
                }

                echo $point;
            }
            echo "\n";
        }
    }
}
