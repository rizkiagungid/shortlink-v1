<?php

/**
 * Calculate the growth between two values.
 *
 * @param $current
 * @param $previous
 * @return array|int
 */
function calcGrowth($current, $previous)
{
    if ($previous == 0 || $previous == null || $current == 0) {
        return 0;
    }

    return $result = (($current - $previous) / $previous * 100);
}

/**
 * Calculates the percentage change between two numbers.
 *
 * @param $old
 * @param $new
 * @param $precision
 * @return float
 */
function calcPercentageChange($old, $new, $precision = 2): float
{
    if ($old == 0) {
        $old++;
        $new++;
    }

    $change = (($new - $old) / $old) * 100;

    return round($change, 1);
}