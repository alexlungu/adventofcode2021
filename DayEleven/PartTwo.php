<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$initialState = array_map('str_split', array_map('trim', file($filename)));

$step = 0;
$flash = [];
while (count($flash) < 100) {
    $flash = [];
    $initialState = array_map(static fn($line) => array_map(static fn($value) => $value + 1, $line), $initialState);
    foreach ($initialState as $row => $line) {
        foreach ($line as $col => $value) {
            if ($value > 9) {
                $flash["$row$col"] = true;
            }
        }
    }
    foreach (array_keys($flash) as $pos) {
        spread($initialState, $flash, ...str_split((string) $pos));
    }
    foreach (array_keys($flash) as $pos) {
        [$row, $col] = str_split((string) $pos);
        $initialState[$row][$col] = 0;
    }
    $step++;
}

$executionTime = calcExecutionTime();
dump("Answer " . $step);
dump("Execution time: $executionTime");

function spread(array &$inputs, array &$flash, $row, $col) {
    for ($i = max(0, $row - 1); $i <= min(9, $row + 1); ++$i) {
        for ($j = max(0, $col - 1); $j <= min(9, $col + 1); ++$j) {
            if ($i === $row && $j === $col) {
                continue;
            }
            if (++$inputs[$i][$j] > 9 && !isset($flash["$i$j"])) {
                $flash["$i$j"] = true;
                spread($inputs, $flash, $i, $j);
            }
        }
    }
}