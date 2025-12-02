<?php

namespace App\Helpers;

class MyHelper
{
    public static function formatDateTime($date, $time)
    {
        // If either part is missing, return an empty string to avoid errors
        if (empty($date) || empty($time)) {
            return '';
        }

        try {
            // Accepts date and time as strings (e.g. '2025-12-02' and '14:30')
            $dateTime = new \DateTime("$date $time");
            // Format: hour.minute, shortMonth day (kept '.' to match original)
            return $dateTime->format('H.i, M d');
        } catch (\Exception $e) {
            // If parsing fails return an empty string — caller should handle this
            return '';
        }
    }
}
