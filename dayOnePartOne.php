<?php
require 'vendor/autoload.php';

$filename = './dayOneInput.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

if ($handle = fopen($filename, 'rb')) {
    $previousMeasurement = null;
    $largerThanPreviousMeasurementCount = 0;

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


    dd($largerThanPreviousMeasurementCount);
}



