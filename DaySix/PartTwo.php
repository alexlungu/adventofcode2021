<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$input = file($filename);
$initialFishCount = array_map('intval', explode(',', reset($input)));
$initialFishCount = array_count_values($initialFishCount);
krsort($initialFishCount);

$noOfDays = 256;

for ($day = 1; $day <= $noOfDays; ++$day) {
    $newFishCount = [];
    foreach ($initialFishCount as $state => $count) {
        if ($state === 0) {
            if (isset($newFishCount[6])) {
                $newFishCount[6] += $count;
            } else {
                $newFishCount[6] = $count;
            }

            if (isset($newFishCount[8])) {
                $newFishCount[8] += $count;
            } else {
                $newFishCount[8] = $count;
            }
            continue;
        }

        $newFishCount[$state - 1] = $count;
    }

    krsort($newFishCount);
    $initialFishCount = $newFishCount;
}

$executionTime = calcExecutionTime();
dump("Answer " . array_sum($initialFishCount));
dump("Execution time: $executionTime");