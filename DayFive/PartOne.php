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
$coordPoints = [];
if ($handle = fopen($filename, 'rb')) {
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);

        preg_match('/(?\'x1\'\d+),(?\'y1\'\d+)\s+\-\>\s+(?\'x2\'\d+),(?\'y2\'\d+)/', $line, $coords);

        // We need at least a matching pair of coords to continue
        if ($coords['x1'] !== $coords['x2'] && $coords['y1'] !== $coords['y2']) {
            continue;
        }

        // generate the range of values for setting inside the array
        $colRange = range(min([$coords['x1'], $coords['x2']]), max([$coords['x1'], $coords['x2']]));
        $rowRange = range(min([$coords['y1'], $coords['y2']]), max([$coords['y1'], $coords['y2']]));

        foreach ($colRange as $col) {
            foreach ($rowRange as $row) {
                if (!isset($coordPoints["{$col}_{$row}"])) {
                    $coordPoints["{$col}_{$row}"] = 0;
                }
                ++$coordPoints["{$col}_{$row}"];
            }
        }
    }
}
$totalIntersectingPoints = array_count_values($coordPoints);
// We only need intersecting points where for two or more lines
unset($totalIntersectingPoints['1']);

$executionTime = calcExecutionTime();
dump("Answer " . array_sum($totalIntersectingPoints));
dump("Execution time: $executionTime");