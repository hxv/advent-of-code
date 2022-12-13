<?php

require __DIR__ . '/common.php';

$input = file(__DIR__ . '/input');
$input = array_map('rtrim', $input);

$fs = new Filesystem();
foreach ($input as $line) {
    if (str_starts_with($line, '$')) {
        $cmd = $arg = null;

        if (sscanf($line, '$ %s %s', $cmd, $arg) === -1) {
            throw new RuntimeException('Error parsing command.');
        }

        if ($cmd === 'cd') {
            $fs->cd($arg);
        }
    } else { // must be a listing
        $size = $name = null;

        if (sscanf($line, '%s %s', $size, $name) === -1) {
            throw new RuntimeException('Error parsing listing.');
        }

        if ($size === 'dir') {
            $fs->getCurDir()->addDir($name);
        } else {
            $fs->getCurDir()->addFile($name, $size);
        }
    }
}

$fsSize = 70000000;
$requiredSpace = 30000000;

$missingSpace = $requiredSpace - ($fsSize - $fs->getRootDir()->getSize());

$sum = 0;
$minimal = PHP_INT_MAX;
foreach ($fs->getAllDirs() as $dir) {
    $size = $dir->getSize();

    if ($size <= 100000) {
        $sum += $size;
    }

    if ($size >= $missingSpace) {
        $minimal = min($minimal, $size);
    }
}

echo "{$sum}\n";
echo "{$minimal}\n";
