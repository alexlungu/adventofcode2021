<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$mappings = [
    0 => ['a','b','c','d','e','g'],
    1 => ['a','b'], // unique
    2 => ['a','c','d','f','g'],
    3 => ['a','b','c','d','f'],
    4 => ['a','b','f','e'], // unique
    5 => ['b','c','d','e','f'],
    6 => ['b','c','d','f','e','g'],
    7 => ['a','b','d'], // unique
    8 => ['a','b','c','d','e','f','g'], // unique
    9 => ['a','b','c','d','e','f'],
];

$totalSum = 0;

$mappingCounts = array_fill_keys(range(0,9), 0);

if ($handle = fopen($filename, 'rb')) {
    while (($line = fgets($handle)) !== false) {
        $outputNumber = '';
        [$signals, $output] = explode(' | ', trim($line));

        $signalValues = array_map('str_split', explode(' ', $signals));
        $reducedSignalValues = [
            2 => [],
            4 => []
        ];
        foreach ($signalValues as $letters) {
            if (count($letters) === 2) {
                $reducedSignalValues[2] = $letters;
            }

            if (count($letters) === 4) {
                $reducedSignalValues[4] = $letters;
            }
        }

        $outputValues = explode(' ', $output);

        foreach ($outputValues as $output) {
            $length = strlen($output);
            $chars = str_split($output);

            if ($length === 2) {
                $outputNumber .= '1';
            } elseif ($length === 3) {
                $outputNumber .= '7';
            } elseif ($length === 4) {
                $outputNumber .= '4';
            } elseif ($length === 7) {
                $outputNumber .= '8';
            } elseif ($length === 5) {
                /**
                 * This is where it gets a bit crap
                 * in order to decode the number we also need to know how many characters of the output value
                 * are found in the 2 or 4 character count signal values.
                 */
                if (count(array_intersect($chars, $reducedSignalValues[2])) === 2) {
                    $outputNumber .= '3';
                } elseif (count(array_intersect($chars, $reducedSignalValues[4])) === 2) {
                    $outputNumber .= '2';
                } else {
                    $outputNumber .= '5';
                }
            } elseif ($length === 6) {
                if (count(array_intersect($chars, $reducedSignalValues[2])) === 1) {
                    $outputNumber .= '6';
                } elseif (count(array_intersect($chars, $reducedSignalValues[4])) === 4) {
                    $outputNumber .= '9';
                } else {
                    $outputNumber .= '0';
                }
            }
        }

        $totalSum += (int)$outputNumber;
    }
}

$executionTime = calcExecutionTime();
dump("Answer " . $totalSum);
dump("Execution time: $executionTime");