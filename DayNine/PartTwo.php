<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$input = file($filename);

$map = array_map('str_split', array_map('trim', $input));

$rowCount = count($map);
$lowestPositions = [];

for ($row = 0; $row < $rowCount; ++$row) {
    $colCount = count($map[$row]);
    for ($col = 0; $col < $colCount; ++$col) {
        $isLowPoint = true;

        // Compare against up value
        if (isset($map[$row - 1][$col]) && $map[$row - 1][$col] <= $map[$row][$col]) {
            $isLowPoint = false;
        }

        // Compare against down value
        if (isset($map[$row + 1][$col]) && $map[$row + 1][$col] <= $map[$row][$col]) {
            $isLowPoint = false;
        }

        // Compare against previous value
        if (isset($map[$row][$col - 1]) && $map[$row][$col - 1] <= $map[$row][$col]) {
            $isLowPoint = false;
        }

        // Compare against next value
        if (isset($map[$row][$col + 1]) && $map[$row][$col + 1] <= $map[$row][$col]) {
            $isLowPoint = false;
        }

        if ($isLowPoint === true) {
            $lowestPositions[] = [
                'row' => $row,
                'col' => $col
            ];
        }
    }
}

$basinCount = [];
foreach ($lowestPositions as $index => $positions) {
    $basinCount[$index] = checkAdjacent($map, [$positions], [$positions], 0);
}

rsort($basinCount);
$total = $basinCount[0] * $basinCount[1] * $basinCount[2];

$executionTime = calcExecutionTime();
dump("Answer " . $total);
dump("Execution time: $executionTime");

function checkAdjacent($data, $positions, $visitedPositions = [], $adjacentCount = 0) {
    $recursive = false;
    $newAdjacent = [];

    foreach ($positions as $pos) {
        ['row' => $row, 'col' => $col] = $pos;

        if (isset($data[$row - 1][$col]) && $data[$row - 1][$col] !== '9' && !in_array(['row' => $row - 1, 'col' => $col], $visitedPositions)) {
            $recursive = true;
            ++$adjacentCount;
            $visitedPositions[] = ['row' => $row - 1, 'col' => $col];
            $newAdjacent[] = ['row' => $row - 1 , 'col' => $col];
        }

        if (isset($data[$row + 1][$col]) && $data[$row + 1][$col] !== '9' && !in_array(['row' => $row + 1, 'col' => $col], $visitedPositions)) {
            $recursive = true;
            ++$adjacentCount;
            $visitedPositions[] = ['row' => $row + 1, 'col' => $col];
            $newAdjacent[] = ['row' => $row + 1 , 'col' => $col];
        }

        if (isset($data[$row][$col - 1]) && $data[$row][$col - 1] !== '9' && !in_array(['row' => $row, 'col' => $col - 1], $visitedPositions)) {
            $recursive = true;
            ++$adjacentCount;
            $visitedPositions[] = ['row' => $row, 'col' => $col - 1];
            $newAdjacent[] = ['row' => $row, 'col' => $col - 1];
        }

        if (isset($data[$row][$col + 1]) && $data[$row][$col + 1] !== '9' && !in_array(['row' => $row, 'col' => $col + 1], $visitedPositions)) {
            $recursive = true;
            ++$adjacentCount;
            $visitedPositions[] = ['row' => $row, 'col' => $col + 1];
            $newAdjacent[] = ['row' => $row, 'col' => $col + 1];
        }
    }

    if ($recursive) {
        return checkAdjacent($data, $newAdjacent, $visitedPositions, $adjacentCount);
    }

    return ++$adjacentCount;
}