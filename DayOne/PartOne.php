<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$largerThanPreviousMeasurementCount = 0;

if ($handle = fopen($filename, 'rb')) {
    $previousMeasurement = null;
    while (($currValue = fgets($handle)) !== false) {
        $currValue = trim($currValue);
        if ($previousMeasurement === null) {
            $previousMeasurement = (int)$currValue;
            continue;
        }

        if ($currValue > $previousMeasurement) {
            ++$largerThanPreviousMeasurementCount;
        }

        $previousMeasurement = $currValue;
    }
    fclose($handle);
}

$executionTime = calcExecutionTime();
dump("Answer: $largerThanPreviousMeasurementCount");
dump("Execution time: $executionTime");



