<?php
require 'vendor/autoload.php';

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}
$horizontalPos = $depth = 0;
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
        }

        if ($instructions['direction'] === 'down') {
            $depth += (int)$instructions['value'];
        }

        if ($instructions['direction'] === 'up') {
            $depth -= (int)$instructions['value'];
        }
    }
    fclose($handle);
}
dump("Horizontal position $horizontalPos");
dump("Depth $depth");
dd($horizontalPos * $depth);


