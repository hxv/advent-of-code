<?php

namespace hxv\AoC\Year2022\Day11;

class Monkey
{
    public int $itemInspectionsCount = 0;

    public function __construct(
        public MonkeyCollection $collection,
        public int $number,
        /** @var Item[] */
        public array $items,
        public Inspection $inspection,
        public \GMP $divider,
        public int $ifTrue,
        public int $ifFalse,
    ) {
    }

    public function inspect(bool $keepCalm): void
    {
        foreach ($this->items as $i => $item) {
            $this->inspectItem($item, $keepCalm);

            unset($this->items[$i]);
        }
    }

    private function inspectItem(Item $item, bool $keepCalm): void
    {
        $this->inspection->inspect($item);

        if ($keepCalm) {
            $item->worryLevel = gmp_div($item->worryLevel, 3);
        }

        $targetMonkeyNumber = gmp_mod($item->worryLevel, $this->divider) == gmp_init(0) ? $this->ifTrue : $this->ifFalse;

        $this->collection->monkeys[$targetMonkeyNumber]->items[] = $item;

        $this->itemInspectionsCount++;
    }
}
