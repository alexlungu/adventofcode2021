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

$bingoNumbers = [];
$allCards = $card = [];
if ($handle = fopen($filename, 'rb')) {
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);

        // Populate Bingo Numbers array
        if (empty($bingoNumbers) && !empty($line)) {
            $bingoNumbers = explode(',', $line);
            continue;
        }

        if (empty($line)) {
            if (!empty($card)) {
                $matrix = new Matrix($card);

                //Let's generate all possible combos
                $allCards[] = array_merge($matrix->toArray(), $matrix->transpose()->toArray());
            }

            $card = [];
        }

        if (!empty($line)) {
            $card[] = preg_split('/\s+/', $line);
        }
    }
}

$loosingCardSum = 0;
$numbersToCheck = [];
$winningCards = [];
// now for the fun part, loop through each bingo number and sequentially add to the numbers to check array
foreach ($bingoNumbers as $selectedNumber) {
    $numbersToCheck[] = (int)$selectedNumber;

    // Next foreach through each of the cards and calculate the array diff
    foreach ($allCards as $index => $card) {
        if (in_array($index, $winningCards)) {
            continue;
        }
        foreach ($card as $combination) {
            if (empty(array_diff($combination, $numbersToCheck))) {
                $unmarkedNumbers = array_diff(array_unique(array_flatten($card)), $numbersToCheck);
                $loosingCardSum = array_sum($unmarkedNumbers) * (int)$selectedNumber;
                $winningCards[] = $index;
                continue 2;
            }
        }
    }
}

$executionTime = calcExecutionTime();
dump("Answer " . $loosingCardSum);
dump("Execution time: $executionTime");