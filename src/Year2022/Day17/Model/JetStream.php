<?php

namespace hxv\AoC\Year2022\Day17\Model;

class JetStream
{
    /** @var list<Direction> */
    private array $directions = [];

    private int $offset = 0;

    /**
     * @param string[] $directions
     */
    public function __construct(array $directions)
    {
        $this->directions = array_map(
            fn (string $direction): Direction => match ($direction) {
                '<' => Direction::Left,
                '>' => Direction::Right,
                default => throw new \LogicException(sprintf('Unknown direction %s.', $direction))
            },
            $directions,
        );
    }

    public function getNextPush(): Direction
    {
        $direction = $this->directions[$this->offset];

        $this->offset = ($this->offset + 1) % count($this->directions);

        return $direction;
    }
}
