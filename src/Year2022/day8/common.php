<?php

class Forest
{
    /** @var Tree[][] */
    private array $trees = [];

    private int $maxY = -1;

    private int $maxX = -1;

    public function addTree(Tree $tree): void
    {
        $this->trees[$tree->y][$tree->x] = $tree;

        $this->maxY = max($this->maxY, $tree->y);
        $this->maxX = max($this->maxX, $tree->x);
    }

    public function isVisible(Tree $tree): bool
    {
        if ($this->isOnEdge($tree)) {
            return true;
        }

        $hasHigherUp = array_reduce(
            iterator_to_array($this->getTreesUp($tree)),
            fn (bool $carry, Tree $upTree): bool => $carry || $upTree->height >= $tree->height,
            false
        );

        $hasHigherDown = array_reduce(
            iterator_to_array($this->getTreesDown($tree)),
            fn (bool $carry, Tree $downTree): bool => $carry || $downTree->height >= $tree->height,
            false
        );

        $hasHigherLeft = array_reduce(
            iterator_to_array($this->getTreesLeft($tree)),
            fn (bool $carry, Tree $leftTree): bool => $carry || $leftTree->height >= $tree->height,
            false
        );

        $hasHigherRight = array_reduce(
            iterator_to_array($this->getTreesRight($tree)),
            fn (bool $carry, Tree $rightTree): bool => $carry || $rightTree->height >= $tree->height,
            false
        );

        return !$hasHigherUp || !$hasHigherDown || !$hasHigherLeft || !$hasHigherRight;
    }

    private function isOnEdge(Tree $tree): bool
    {
        return $tree->y === 0 || $tree->y === $this->maxY || $tree->x === 0 || $tree->x === $this->maxX;
    }

    /**
     * @return iterable<Tree>
     */
    private function getTreesUp(Tree $tree): iterable
    {
        for ($y=$tree->y - 1; $y>=0; --$y) {
            yield $this->trees[$y][$tree->x];
        }
    }

    /**
     * @return iterable<Tree>
     */
    private function getTreesDown(Tree $tree): iterable
    {
        for ($y=$tree->y + 1; $y<=$this->maxY; ++$y) {
            yield $this->trees[$y][$tree->x];
        }
    }

    /**
     * @return iterable<Tree>
     */
    private function getTreesLeft(Tree $tree): iterable
    {
        for ($x=$tree->x - 1; $x>=0; --$x) {
            yield $this->trees[$tree->y][$x];
        }
    }

    /**
     * @return iterable<Tree>
     */
    private function getTreesRight(Tree $tree): iterable
    {
        for ($x=$tree->x + 1; $x<=$this->maxX; ++$x) {
            yield $this->trees[$tree->y][$x];
        }
    }

    public function getScenicScore(Tree $tree): int
    {
        if ($this->isOnEdge($tree)) {
            return 0;
        }

        $scoreUp = 0;
        foreach ($this->getTreesUp($tree) as $upTree) {
            ++$scoreUp;
            if ($upTree->height >= $tree->height) {
                break;
            }
        }

        $scoreDown = 0;
        foreach ($this->getTreesDown($tree) as $downTree) {
            ++$scoreDown;
            if ($downTree->height >= $tree->height) {
                break;
            }
        }

        $scoreLeft = 0;
        foreach ($this->getTreesLeft($tree) as $leftTree) {
            ++$scoreLeft;
            if ($leftTree->height >= $tree->height) {
                break;
            }
        }

        $scoreRight = 0;
        foreach ($this->getTreesRight($tree) as $rightTree) {
            ++$scoreRight;
            if ($rightTree->height >= $tree->height) {
                break;
            }
        }

        return $scoreUp * $scoreDown * $scoreLeft * $scoreRight;
    }

    /**
     * @return iterable<Tree>
     */
    public function getTrees(): iterable
    {
        for ($y=0; $y<=$this->maxY; ++$y) {
            for ($x=0; $x<=$this->maxX; ++$x) {
                yield $this->trees[$y][$x];
            }
        }
    }
}

class Tree
{
    public function __construct(
        public readonly int $y,
        public readonly int $x,
        public readonly int $height,
    ) {
    }
}
