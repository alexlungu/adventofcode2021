<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();
$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}
$horizontalPos = $depth = $aim = 0;
if ($handle = fopen($filename, 'rb')) {
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        preg_match('/(?\'direction\'forward|down|up)\s(?\'value\'\d+)/', $line, $instructions);

        if (!isset($instructions['direction'], $instructions['value'])) {
            // Direction of travel or value missing
            continue;
        }

        if ($instructions['direction'] === 'forward') {
            $horizontalPos += (int)$instructions['value'];
            $depth += $aim * (int)$instructions['value'];
        }

        if ($instructions['direction'] === 'down') {
            $aim += (int)$instructions['value'];
        }

        if ($instructions['direction'] === 'up') {
            $aim -= (int)$instructions['value'];
        }
    }
    fclose($handle);
}
$executionTime = calcExecutionTime();
dump("Answer " . $horizontalPos * $depth);
dump("Execution time: $executionTime");


