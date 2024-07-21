<?php

use Illuminate\Support\Carbon;

if (!function_exists('to_date')) {
    /**
     * Convert a date string to date object
     * @param $string
     * @param string|null $format
     * @param bool $is_date
     * @return Carbon|string
     */
    function to_date($string, string $format = null, bool $is_date = true): Carbon|string
    {
        if ($format === null) {
            $format = config('app.format.date');
        }

        try {
            $date = Carbon::createFromFormat($format, $string);
            if ($is_date) {
                $date->startOfDay();
            }
        } catch (Exception) {
            $date = $string;
        }

        return $date;
    }
}

if (!function_exists('format_date')) {
    /**
     * Format date object to string
     * @param $date
     * @param string|null $format
     * @return mixed
     */
    function format_date($date, string $format = null): mixed
    {
        if (!($date instanceof DateTimeInterface)) {
            return $date;
        }

        if ($format === null) {
            $format = config('app.format.date');
        }

        return $date->format($format);
    }
}

if (!function_exists('tomorrow')) {
    /**
     * Create a new Carbon instance for the day after today
     * @param DateTimeZone|string|null $tz
     * @return Carbon
     */
    function tomorrow(DateTimeZone|string $tz = null): Carbon
    {
        return today($tz)->addDay();
    }
}
