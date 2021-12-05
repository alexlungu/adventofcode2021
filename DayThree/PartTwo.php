<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();
$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$oxygenGeneratorRatingBinary = $co2ScrubberRatingBinary = '';
$allValues = file($filename);

$oxyValues = $co2Values = $allValues;
$oxyRatePattern = $co2ScrubberPattern = '';
for ($i = 1; $i <= 12; ++$i) {
    // Let's do oxy first
    $oxyOccurrences = getOccurrencesAtPositionCount($oxyValues, $i);
    $oxyMostCommonOccurrence = array_search(max($oxyOccurrences), $oxyOccurrences);
    $oxyLeasCommonOccurrence = array_search(min($oxyOccurrences), $oxyOccurrences);
    $oxySearchValue = $oxyMostCommonOccurrence === $oxyLeasCommonOccurrence ? '1' : $oxyMostCommonOccurrence;
    $oxyRatePattern .= $oxySearchValue;

    $oxyValues = preg_grep("/^$oxyRatePattern.*/", $oxyValues);
    if (count(array_unique($oxyValues)) === 1) {
        $oxygenGeneratorRatingBinary = reset($oxyValues);
    }

    // Let's do co2 as well
    $co2Occurrences = getOccurrencesAtPositionCount($co2Values, $i);
    $co2MostCommonOccurrence = array_search(max($co2Occurrences), $co2Occurrences);
    $co2LeasCommonOccurrence = array_search(min($co2Occurrences), $co2Occurrences);
    $co2SearchValue = $co2MostCommonOccurrence === $co2LeasCommonOccurrence ? '0' : $co2LeasCommonOccurrence;
    $co2ScrubberPattern .= $co2SearchValue;

    $co2Values = preg_grep("/^$co2ScrubberPattern.*/", $co2Values);
    if (count(array_unique($co2Values)) === 1) {
        $co2ScrubberRatingBinary = reset($co2Values);
    }
}

$executionTime = calcExecutionTime();
dump("Answer " . (bindec($oxygenGeneratorRatingBinary) * bindec($co2ScrubberRatingBinary)));
dump("Execution time: $executionTime");

function getOccurrencesAtPositionCount(array $input, $position): array
{
    $occurrences[$position] = [
        0 => 0,
        1 => 0
    ];

    foreach ($input as $line) {
        $line = trim($line);
        $allValues[] = $line;
        $characters = mb_str_split($line);

        foreach ($characters as $index => $val) {
            if ($position !== ($index + 1)) {
                continue;
            }

            ++$occurrences[$position][$val];
        }
    }

    return $occurrences[$position];
}
