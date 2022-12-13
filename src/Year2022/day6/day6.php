<?php

$input = file_get_contents(__DIR__ . '/input');
$input = rtrim($input);
$input = str_split($input);

$packetBuffer = [];
$hasFirstPacket = false;

$messageBuffer = [];
$hasFirstMessage = false;
foreach ($input as $i => $char) {
    if (count($packetBuffer) >= 4) {
        array_shift($packetBuffer);
    }
    $packetBuffer[] = $char;

    if (count($messageBuffer) >= 14) {
        array_shift($messageBuffer);
    }
    $messageBuffer[] = $char;

    if (!$hasFirstPacket && count($packetBuffer) >= 4) {
        if (count(array_unique($packetBuffer)) === 4) {
            echo "First packet: " . ($i + 1) . "\n";

            $hasFirstPacket = true;
        }
    }

    if (!$hasFirstMessage && count($messageBuffer) >= 14) {
        if (count(array_unique($messageBuffer)) === 14) {
            echo "First message: " . ($i + 1) . "\n";

            $hasFirstMessage = true;
        }
    }
}
