<?php


namespace App\Traits;

use Carbon\Carbon;

trait DateRangeTrait
{
    /**
     * Generate the range for dates.
     *
     * @param $from
     * @param $to
     * @return array
     */
    private function range($from = null, $to = null)
    {
        try {
            $to = request()->input('to') ? Carbon::createFromFormat('Y-m-d', request()->input('to')) : ($to ?? Carbon::now());
        } catch (\Exception $e) {
            $to = Carbon::now();
        }

        try {
            $from = request()->input('from') ? Carbon::createFromFormat('Y-m-d', request()->input('from')) : ($from ?? $to);
        } catch (\Exception $exception) {
            $from = $to;
        }

        if ($from->diffInDays($to) < 1) {
            $unit = 'hour';
            $format = '';
        } elseif($from->diffInMonths($to) < 3) {
            $unit = 'day';
            $format = 'Y-m-d';
        } elseif ($from->diffInYears($to) < 2) {
            $unit = 'month';
            $format = 'Y-m';
        } else {
            $unit = 'year';
            $format = 'Y';
        }

        // Reset the date range if it exceeds the limits
        if ($from->diffInYears($to) >= 100) {
            $to = Carbon::now();
            $from = $to;
        }

        // Get the old period date range
        $to_old = (clone $from)->subDays(1);
        $from_old = (clone $to_old)->subDays($from->diffInDays($to));

        return ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'), 'from_old' => $from_old->format('Y-m-d'), 'to_old' => $to_old->format('Y-m-d'), 'unit' => $unit, 'format' => $format];
    }

    /**
     * Calculate all the possible dates between two time frames.
     *
     * @param $to
     * @param $from
     * @param $unit
     * @param $format
     * @param mixed $output
     * @return mixed
     */
    private function calcAllDates($from, $to, $unit, $format, $output = 0)
    {
        $from = Carbon::createFromFormat($format, $from);
        $to = Carbon::createFromFormat($format, $to);

        $possibleDateResults[$from->copy()->format($format)] = $output;

        while ($from->lt($to)) {
            if ($unit == 'year') {
                $from = $from->addYears(1);
            } elseif ($unit == 'month') {
                $from = $from->addMonths(1);
            } elseif ($unit == 'day') {
                $from = $from->addDays(1);
            } elseif ($unit == 'hour') {
                $from = $from->addHour();
            } elseif ($unit == 'second') {
                $from = $from->addSecond();
            }

            if ($from->lte($to)) {
                $possibleDateResults[$from->copy()->format($format)] = $output;
            }
        }

        return $possibleDateResults;
    }
}
