<?php

$input = file(__DIR__ . '/input');
$input = array_map('rtrim', $input);

function getScore(string $char): int
{
    $code = ord($char);

    if ($code >= ord('a') && $code <= ord('z')) {
        return $code - ord('a') + 1;
    }

    if ($code >= ord('A') && $code <= ord('Z')) {
        return $code - ord('A') + 27;
    }

    throw new RuntimeException(sprintf('Invalid character "%s".', $char));
}

$score = 0;
$score2 = 0;

$group = [];
foreach ($input as $line) {
    if (strlen($line) % 2 !== 0) {
        throw new RuntimeException(sprintf('Invalid length of line "%s".', $line));
    }

    $chars = str_split($line);

    $group[] = array_unique($chars);
    if (count($group) >= 3) {
        $commonGroup = array_intersect(...$group);
        $commonGroup = array_unique($commonGroup);
        $commonGroup = array_values($commonGroup);

        if (count($commonGroup) !== 1) {
            throw new RuntimeException('Invalid count of common chars in group.');
        }

        $score2 += getScore($commonGroup[0]);

        $group = [];
    }

    $compB = array_splice($chars, count($chars) / 2);
    $compA = $chars;

    $common = array_intersect($compA, $compB);
    $common = array_unique($common);
    $common = array_values($common);

    if (count($common) !== 1) {
        throw new RuntimeException(sprintf('Invalid count of common chars on line "%s". Common: %s', $line, json_encode($common)));
    }

    $score += getScore($common[0]);
}

echo "{$score}\n";
echo "{$score2}\n";
