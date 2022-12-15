<?php

namespace hxv\AoC\Grid;

class PreviewGridDecorator extends AbstractGridDecorator
{
    private float $lastExport;

    private int $tick = 0;

    public function __construct(
        GridInterface $inner,
        /** @var array{delay?: int|float, fps?: int|float, every?: int} */
        private array $options = [],
        private BetterGridExporter $gridExporter = new BetterGridExporter(),
    ) {
        parent::__construct($inner);

        $this->lastExport = microtime(true);
    }

    public function addPoint(BetterPoint $point): self
    {
        parent::addPoint($point);

        $this->export();

        return $this;
    }

    public function removePoint(BetterPoint $point): self
    {
        parent::removePoint($point);

        $this->export();

        return $this;
    }

    private function export(): void
    {
        if (isset($this->options['delay'])) {
            usleep($this->options['delay'] * 1_000_000);
        }

        $display = true;

        if (isset($this->options['every'])) {
            $display = $this->tick++ % $this->options['every'] === 0;
        }

        if (isset($this->options['fps'])) {
            $display = microtime(true) - $this->lastExport >= 1 / $this->options['fps'];
        }

        if ($display) {
            $this->lastExport = microtime(true);

            echo $this->gridExporter->export($this);
        }
    }
}
