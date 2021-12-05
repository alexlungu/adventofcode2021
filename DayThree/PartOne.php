<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();
$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$gammaRateBinary = $epsilonRateBinary = '';
$occurrences = [];
if ($handle = fopen($filename, 'rb')) {
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        $characters = mb_str_split($line);
        foreach ($characters as $index => $val) {
            if (!isset($occurrences[$index + 1])) {
                $occurrences[$index + 1] = [
                    0 => 0,
                    1 => 0
                ];
            }

            ++$occurrences[$index + 1][$val];
        }
    }
    fclose($handle);
}

foreach ($occurrences as $values) {
    $gammaRateBinary .= array_search(max($values),$values);
    $epsilonRateBinary .= array_search(min($values),$values);
}

$executionTime = calcExecutionTime();
dump("Answer " . (bindec($gammaRateBinary) * bindec($epsilonRateBinary)));
dump("Execution time: $executionTime");


