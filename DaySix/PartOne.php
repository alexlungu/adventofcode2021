<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

use Phpml\Math\Matrix;
use function Spatie\array_flatten;

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$input = file($filename);
$noOfDays = 80;
$initialState = reset($input);

for ($day = 1; $day <= $noOfDays; ++$day) {
    // check for any 0 day fish
    $newFishToBeAdded = substr_count($initialState, '0');
    $initialState .= str_repeat(',9', $newFishToBeAdded);
    $initialState = str_replace('0', '7', $initialState);

    preg_match_all('/\d/', $initialState, $fishState);
    $fishState = array_unique($fishState[0]);
    sort($fishState);

    foreach ($fishState as $state) {
        $initialState = str_replace($state, ((int)$state-1), $initialState);
    }
}

$executionTime = calcExecutionTime();
dump("Answer " . count(explode(',', $initialState)));
dump("Execution time: $executionTime");