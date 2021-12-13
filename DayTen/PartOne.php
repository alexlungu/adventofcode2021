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
    ')' => 3,
    ']' => 57,
    '}' => 1197,
    '>' => 25137
];

$score = 0;
if ($handle = fopen($filename, 'rb')) {
    while (($line = fgets($handle)) !== false) {
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

        $remainingTags = str_split($line);

        // No closing tags found => incomplete line thus skip
        if (count(array_intersect($remainingTags, array_keys($closingTags))) === 0) {
            continue;
        }

        foreach ($remainingTags as $pos => $tag) {
            if (array_key_exists($tag, $closingTags)) {
                $score += $closingTags[$tag];
                continue 2;
            }
        }
    }
}

$executionTime = calcExecutionTime();
dump("Answer " . $score);
dump("Execution time: $executionTime");