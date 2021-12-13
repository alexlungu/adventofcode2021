<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$input = file($filename);
$initialCrabPositions = array_map('intval', explode(',', reset($input)));
$allPossiblePositionFuelRequirement = array_fill_keys(range(min($initialCrabPositions), max($initialCrabPositions)), 0);

foreach ($allPossiblePositionFuelRequirement as $pos => &$fuel) {
    foreach ($initialCrabPositions as $currentPos) {
        $fuel += abs($currentPos - $pos);
    }
}
unset($fuel);

$leastFuel = min($allPossiblePositionFuelRequirement);
$executionTime = calcExecutionTime();
dump("Answer " . $leastFuel);
dump("Execution time: $executionTime");