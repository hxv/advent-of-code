<?php

namespace hxv\AoC\Grid;

class BetterGridExporter
{
    public function export(BetterGrid $grid): string
    {
        $ret = "\e[H\e[J";

        $boundaries = $grid->getBoundaries();

        $leftPadLength = max(strlen($boundaries['y']['min']), strlen($boundaries['y']['max']));

        for ($y=$boundaries['y']['min']; $y<=$boundaries['y']['max']; ++$y) {
            if (($y - $boundaries['y']['min']) % 2 === 0 || $y === $boundaries['y']['min'] || $y === $boundaries['y']['max']) {
                $ret .= sprintf(" %{$leftPadLength}d ", $y);
            } else {
                $ret .= str_repeat(' ', $leftPadLength + 2);
            }

            for ($x=$boundaries['x']['min']; $x<=$boundaries['x']['max']; ++$x) {
                if (null === $point = $grid->getPointAt($x, $y)) {
                    $ret .= ' ';

                    continue;
                }

                $ret .= $this->format($point);
            }
            $ret .= "\n";
        }

        $downLegendLength = max(strlen($boundaries['x']['min']), strlen($boundaries['x']['max']));

        $xs = range($boundaries['x']['min'], $boundaries['x']['max']);
        for ($c=0; $c<$downLegendLength; ++$c) {
            $ret .= str_repeat(' ', $leftPadLength + 2);

            for ($i=0; $i<count($xs); ++$i) {
                $x = $xs[$i];

                if (($x - $boundaries['x']['min']) % 5 === 0 || $x === $boundaries['x']['min'] || $x === $boundaries['x']['max']) {
                    $ret .= ((string) $xs[$i])[$c] ?? ' ';
                } else {
                    $ret .= ' ';
                }
            }

            $ret .= "\n";
        }

        $ret .= "\n";

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
