<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$mappings = [
    0 => ['a','b','c','e','f','g'],
    1 => ['c','f'],
    2 => ['a','c','d','e','g'],
    3 => ['a','c','d','f','g'],
    4 => ['b','c','d','f'],
    5 => ['a','b','d','f','g'],
    6 => ['a','b','d','e','f','g'],
    7 => ['a','c','f'],
    8 => ['a','b','c','d','e','f','g'],
    9 => ['a','b','c','d','f','g'],
];

$mappingCounts = array_fill_keys(range(0,9), 0);

if ($handle = fopen($filename, 'rb')) {
    while (($line = fgets($handle)) !== false) {
        $line = explode(' | ', trim($line));
        $outputValues = array_map('str_split', explode(' ', $line[1]));

        foreach ($outputValues as $pairs) {
            foreach ($mappings as $k => $map) {
                if (count($pairs) === count($map)) {
                    ++$mappingCounts[$k];
                }
            }
        }
    }
}
$sum = $mappingCounts[1] + $mappingCounts[4] + $mappingCounts[7] + $mappingCounts[8];

$executionTime = calcExecutionTime();
dump("Answer " . $sum);
dump("Execution time: $executionTime");