<?php

namespace hxv\AoC\Year2022\Day11;

class MonkeyCollection
{
    /** @var array<int, Monkey> */
    public array $monkeys = [];

    private \GMP $commonNumber;

    public function __construct()
    {
        $this->commonNumber = gmp_init(1);
    }

    public function addMonkey(Monkey $monkey): void
    {
        if (isset($this->monkeys[$monkey->number])) {
            throw new \RuntimeException(sprintf('Monkey with number %d already exists', $monkey->number));
        }

        $this->monkeys[$monkey->number] = $monkey;

        $this->commonNumber = gmp_mul($this->commonNumber, $monkey->divider);
    }

    public function runRound(bool $keepCalm = true): void
    {
        foreach ($this->monkeys as $monkey) {
            foreach ($monkey->items as $item) {
                $item->worryLevel = gmp_mod($item->worryLevel, $this->commonNumber);
            }

            $monkey->inspect($keepCalm);
        }
    }
}
