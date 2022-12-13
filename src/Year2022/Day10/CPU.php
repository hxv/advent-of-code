<?php

namespace hxv\AoC\Year2022\Day10;

class CPU
{
    private int $register = 1;

    private int $cycle = 0;

    /** @var array<callable(CPU $cpu):void> */
    private array $onTick = [];

    /**
     * @param callable(CPU $cpu):void ...$callback
     */
    public function onTick(callable ...$callback): void
    {
        $this->onTick = $callback;
    }

    public function tick(): void
    {
        $this->cycle++;

        foreach ($this->onTick as $callback) {
            $callback($this);
        }
    }

    public function getCycle(): int
    {
        return $this->cycle;
    }

    public function addX(int $value): void
    {
        $this->register += $value;
    }

    public function getX(): int
    {
        return $this->register;
    }
}
