<?php

namespace hxv\AoC\Year2022\Day16;

class Valve
{
    /** @var array<string, Valve> */
    public array $neighbours = [];

    /** @var array<string, int> */
    public array $distances = [];

    public bool $opened = false;

    public function __construct(
        public readonly string $name,
        public readonly int $flowRate,
    ) {
        $this->distances[$this->name] = 0;
    }

    public function addNeighbour(Valve $neighbour): void
    {
        $this->neighbours[(string) $neighbour] = $neighbour;

        $this->setDistance((string) $neighbour, 1);

        foreach ($this->distances as $targetName => $distance) {
            $neighbour->setDistance($targetName, $distance + 1);
        }
    }

    public function setDistance(string $targetName, int $distance): void
    {
        if ($targetName === $this->name) {
            return;
        }

        if (isset($this->distances[$targetName]) && $this->distances[$targetName] <= $distance) {
            return;
        }

        $this->distances[$targetName] = $distance;

        foreach ($this->neighbours as $neighbour) {
            $neighbour->setDistance($targetName, $distance + 1);
        }
    }

    public function getPressureGain(int $spareTime): ?int
    {
        return $this->flowRate * $spareTime;
    }

    public function getTravelAndOpeningTime(Valve $targetValve): ?int
    {
        return $this->distances[(string) $targetValve] + 1;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
