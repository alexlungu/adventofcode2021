<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$input = file($filename);

$map = array_map('str_split', array_map('trim', $input));

$riskLevel = 0;
$rowCount = count($map);

for ($row = 0; $row < $rowCount; ++$row) {
    $colCount = count($map[$row]);
    for ($col = 0; $col < $colCount; ++$col) {
        $isLowPoint = true;

        // Compare against up value
        if (isset($map[$row - 1][$col]) && $map[$row - 1][$col] <= $map[$row][$col]) {
            $isLowPoint = false;
        }

        // Compare against down value
        if (isset($map[$row + 1][$col]) && $map[$row + 1][$col] <= $map[$row][$col]) {
            $isLowPoint = false;
        }

        // Compare against previous value
        if (isset($map[$row][$col - 1]) && $map[$row][$col - 1] <= $map[$row][$col]) {
            $isLowPoint = false;
        }

        // Compare against next value
        if (isset($map[$row][$col + 1]) && $map[$row][$col + 1] <= $map[$row][$col]) {
            $isLowPoint = false;
        }

        if ($isLowPoint === true) {
            $riskLevel += (1 + $map[$row][$col]);
        }
    }
}

$executionTime = calcExecutionTime();
dump("Answer " . $riskLevel);
dump("Execution time: $executionTime");