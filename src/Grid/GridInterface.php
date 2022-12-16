<?php

namespace hxv\AoC\Grid;

interface GridInterface
{
    public function createPoint(int $x, int $y): BetterPoint;

    public function getOrCreatePoint(int $x, int $y, ?string $newPointLabel = null): BetterPoint;

    public function addPoint(BetterPoint $point): self;

    public function removePoint(BetterPoint $point): self;

    public function getPointAt(int $x, int $y): ?BetterPoint;

    /**
     * @return array{x: array{min: int, max: int}, y: array{min: int, max: int}}
     */
    public function getBoundaries(): array;

    public function drawLine(BetterPoint $startPoint, BetterPoint $endPoint, ?string $label = null): self;
}
