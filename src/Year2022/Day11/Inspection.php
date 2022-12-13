<?php

namespace hxv\AoC\Year2022\Day11;

class Inspection
{
    public function __construct(
        public string $operator,
        public string $value,
    ) {
    }

    public function inspect(Item $item): void
    {
        $value = match ($this->value) {
            'old' => $item->worryLevel,
            default => (int) $this->value,
        };

        match ($this->operator) {
            '*' => $item->worryLevel = gmp_mul($item->worryLevel, $value),
            '+' => $item->worryLevel = gmp_add($item->worryLevel, $value),
            default => throw new \LogicException(sprintf('Unknown operator "%s".', $this->operator)),
        };
    }
}
