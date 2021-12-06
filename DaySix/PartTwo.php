<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}
$coordPoints = [];
if ($handle = fopen($filename, 'rb')) {
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        preg_match('/(?\'x1\'\d+),(?\'y1\'\d+)\s+\-\>\s+(?\'x2\'\d+),(?\'y2\'\d+)/', $line, $coords);

        // Is it a diagonal line?
        if (abs($coords['x1'] - $coords['x2']) === abs($coords['y1'] - $coords['y2'])) {
            $colRange = getRange($coords['x1'], $coords['x2']);
            $rowRange = getRange($coords['y1'], $coords['y2']);

            foreach ($colRange as $colPos => $col) {
                foreach ($rowRange as $rosPos => $row) {
                    if ($colPos === $rosPos) {
                        if (!isset($coordPoints["{$col}_{$row}"])) {
                            $coordPoints["{$col}_{$row}"] = 0;
                        }
                        ++$coordPoints["{$col}_{$row}"];
                    }
                }
            }
            continue;
        }

        // Add generate coords for same row or same column
        if ($coords['x1'] === $coords['x2'] || $coords['y1'] === $coords['y2']) {
            // generate the range of values for setting inside the array
            $colRange = getRange($coords['x1'], $coords['x2']);
            $rowRange = getRange($coords['y1'], $coords['y2']);

            foreach ($colRange as $colPos => $col) {
                foreach ($rowRange as $rosPos => $row) {
                    if (!isset($coordPoints["{$col}_{$row}"])) {
                        $coordPoints["{$col}_{$row}"] = 0;
                    }
                    ++$coordPoints["{$col}_{$row}"];
                }
            }
        }

    }
}

/**
 * @param mixed ...$values
 * @return array
 */
function getRange(...$values): array
{
    return range(...$values);
}

$totalIntersectingPoints = array_count_values($coordPoints);
// We only need intersecting points where for two or more lines
unset($totalIntersectingPoints['1']);

$executionTime = calcExecutionTime();
dump("Answer " . array_sum($totalIntersectingPoints));
dump("Execution time: $executionTime");