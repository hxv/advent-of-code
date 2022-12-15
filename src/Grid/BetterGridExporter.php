<?php

namespace hxv\AoC\Grid;

class BetterGridExporter
{
    public function export(BetterGrid $grid): string
    {
        $ret = "\e[H\e[J";

        $boundaries = $grid->getBoundaries();

        for ($y=$boundaries['y'][0]; $y<=$boundaries['y'][1]; ++$y) {
            for ($x=$boundaries['x'][0]; $x<=$boundaries['x'][1]; ++$x) {
                if (null === $point = $grid->getPointAt($x, $y)) {
                    $ret .= ' ';

                    continue;
                }

                $ret .= $this->format($point);
            }
            $ret .= "\n";
        }

        return $ret;
    }

    private function format(BetterPoint $point): string
    {
        $label = $point->label ?? '▒';

        $colorEscape = match ($point->color) {
            Color::Black => "\e[30m",
            Color::Red => "\e[31m",
            Color::Green => "\e[32m",
            Color::Yellow => "\e[33m",
            Color::Blue => "\e[34m",
            Color::Magenta => "\e[35m",
            Color::Cyan => "\e[36m",
            Color::White => "\e[37m",
            null => null,
        };

        if ($colorEscape !== null) {
            $label = "{$colorEscape}{$label}\e[0m";
        }

        return $label;
    }
}
