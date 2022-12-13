<?php

namespace hxv\AoC\Year2022\Day10;

class Display
{
    /** @var string[] */
    private array $lines = [];

    private string $currentLine = '';

    public function __invoke(CPU $cpu): void
    {
        $column = (($cpu->getCycle()-1) % 40);

        if ($column >= $cpu->getX() - 1 && $column <= $cpu->getX() + 1) {
            $symbol = '#';
        } else {
            $symbol = '.';
        }

//        $symbol = "{$column},{$cpu->getX()}|";

        $this->currentLine .= $symbol;

        if ($cpu->getCycle() % 40 === 0) {
            $this->lines[] = $this->currentLine;
            $this->currentLine = '';
        }
    }

    /**
     * @return string[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }
}
