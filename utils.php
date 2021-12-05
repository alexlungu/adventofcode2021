<?php

function calcExecutionTime(): ?string
{
    static $startTime = null;
    if (null === $startTime) {
        $startTime = microtime(true);

        return null;
    }

    $result = (microtime(true) - $startTime) * 1000;
    $startTime = null;

    return sprintf('%.4f', $result) . 'ms';
}