<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/../utils.php';

calcExecutionTime();

$filename = __DIR__ . '/input.txt';

if (!file_exists($filename)) {
    dd("$filename cannot be found");
}

$tags = [
    '()' => '',
    '[]' => '',
    '{}' => '',
    '<>' => ''
];

$closingTags = [
    ')',
    ']',
    '}',
    '>',
];

$openTags = [
    '(' => 1,
    '[' => 2,
    '{' => 3,
    '<' => 4,
];

$scores = [];
if ($handle = fopen($filename, 'rb')) {
    while (($line = fgets($handle)) !== false) {
        $lineScore = 0;
        $line = trim($line);

        $prevReplacement = '';
        $stop = false;

        // First weed out any complete pairs
        while (!$stop) {
            $line = strtr($line,$tags);

            if ($line === $prevReplacement) {
                $stop = true;
                continue;
            }

            $prevReplacement = $line;
        }

        // Reverse the order so that we work from the middle outward
        $remainingTags = array_reverse(str_split($line));

        // Discard any incorrect closed tags
        if (count(array_intersect($remainingTags, $closingTags)) !== 0) {
            continue;
        }

        foreach ($remainingTags as $tag) {
            $lineScore = ($lineScore * 5) + $openTags[$tag];
        }

        $scores[] = $lineScore;
    }
}
sort($scores);
$middleIndex = round(count($scores)/2);
$middleScore = $scores[$middleIndex - 1];

$executionTime = calcExecutionTime();
dump("Answer " . $middleScore);
dump("Execution time: $executionTime");