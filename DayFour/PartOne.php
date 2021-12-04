<?php

use Phpml\Math\Matrix;
use function Spatie\array_flatten;

require 'vendor/autoload.php';

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

$winningCardSum = 0;
$numbersToCheck = [];
// now for the fun part, loop through each bingo number and sequentially add to the numbers to check array
foreach ($bingoNumbers as $selectedNumber) {
    $numbersToCheck[] = (int)$selectedNumber;

    // Next foreach through each of the cards and calculate the array diff
    foreach ($allCards as $card) {
        foreach ($card as $combination) {
            if (empty(array_diff($combination, $numbersToCheck))) {
                $unmarkedNumbers = array_diff(array_unique(array_flatten($card)), $numbersToCheck);
                $winningCardSum = array_sum($unmarkedNumbers) * (int)$selectedNumber;
                break 3;
            }
        }
    }
}
dd("BINGO! $winningCardSum");