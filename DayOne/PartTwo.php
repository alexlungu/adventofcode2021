<?php
require 'vendor/autoload.php';

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$previousMeasurement = $nextMeasurement = [];
$largerThanPreviousMeasurementCount = 0;
$count = 3;
$firstValueInSum = 0;
$lastValueInSum = 0;

$values = file($filename);
$valuesCount = count($values);

for ($p1 = 0; $p1 <= $valuesCount; ++$p1) {
    if (count($previousMeasurement) < 3) {
        $previousMeasurement[] = (int)trim($values[$p1]);
    }

    if (isset($values[$p1 + 1]) && count($nextMeasurement) < 3) {
        $nextMeasurement[] = (int)trim($values[$p1 + 1]);
    }

    if (count($nextMeasurement) === 3 ) {
        // check if previous > next
        if (array_sum($nextMeasurement) > array_sum($previousMeasurement)) {
            ++$largerThanPreviousMeasurementCount;
        }


        // set previous measurement to be current
        $previousMeasurement = $nextMeasurement;

        // Pop off the first element to continue looping
        array_shift($nextMeasurement);
    }
}

dd($largerThanPreviousMeasurementCount);




