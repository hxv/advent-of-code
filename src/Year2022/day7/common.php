<?php

class Dir {
    private ?Dir $parent;

    /** @var array<string, int> */
    private array $files = [];

    /** @var array<string, Dir> */
    private array $dirs = [];

    public function __construct(?Dir $parent)
    {
        $this->parent = $parent;
    }

    public function addFile(string $name, int $size): void
    {
        $this->files[$name] = $size;
    }

    public function addDir(string $name): void
    {
        $this->dirs[$name] = new Dir($this);
    }

    public function getSize(): int
    {
        return array_sum($this->files) + array_reduce($this->dirs, fn (int $carry, Dir $dir): int => $carry + $dir->getSize(), 0);
    }

    public function getParent(): Dir
    {
        if ($this->parent === null) {
            throw new RuntimeException('No parent directory.');
        }

        return $this->parent;
    }

    public function getDir(string $name): Dir
    {
        if (!isset($this->dirs[$name])) {
            throw new RuntimeException(sprintf('No dir "%s".', $name));
        }

        return $this->dirs[$name];
    }

    /**
     * @return Dir[]
     */
    public function getDirs(): array
    {
        return $this->dirs;
    }
}

class Filesystem
{
    private ?string $cwd = null;

    private Dir $rootDir;

    private ?Dir $curDir = null;

    public function __construct()
    {
        $this->rootDir = new Dir(null);
    }

    public function cd(string $path): void
    {
        if ($path === '/') {
            $this->cwd = '';
            $this->curDir = $this->rootDir;
        } elseif ($path === '..') {
            if ($this->cwd === null || $this->curDir === null) {
                throw new RuntimeException('Current working directory unknown.');
            }

            $this->cwd = substr($this->cwd, 0, strrpos($this->cwd, '/'));
            $this->curDir = $this->curDir->getParent();
        } else {
            if ($this->cwd === null || $this->curDir === null) {
                throw new RuntimeException('Current working directory unknown.');
            }

            $this->cwd .= '/' . $path;
            $this->curDir = $this->curDir->getDir($path);
        }
    }

    public function getCurDir(): Dir
    {
        if ($this->curDir === null) {
            throw new RuntimeException('Current directory unknown.');
        }

        return $this->curDir;
    }

    /**
     * @return Dir[]
     */
    public function getAllDirs(?Dir $dir = null): array
    {
        if ($dir === null) {
            return $this->getAllDirs($this->rootDir);
        }

        $dirs = [];
        foreach ($dir->getDirs() as $subDir) {
            $dirs[] = $subDir;
            $dirs = array_merge($dirs, $this->getAllDirs($subDir));
        }

        return $dirs;
    }

    public function getRootDir(): Dir
    {
        return $this->rootDir;
    }
}
