<?php

namespace App\Trait;

use DateTime;
use Exception;

/**
 * Used for datetime conversions in models (converting datetime from local timezone to UTC)
 *
 */
trait DateTimeConverter
{

    /**
     * Need to convert datetime to UTC for the database to store, because
     * database should not deal with timezones
     *
     * @param  array  $data
     * @param  string  $timezone
     * @param  string  $format
     * @return array
     * @throws Exception
     */
    public static function convertDateTimesToUTC(
        array $data,
        string $timezone,
        string $format = 'Y-m-d H:i:s'
    ): array {
        $localTz = new \DateTimeZone($timezone);
        $utcTz = new \DateTimeZone('UTC');

        $startDate = $data['start'];
        $startDate = new \DateTime($startDate, $localTz);
        $startDate->setTimeZone($utcTz);
        $data['start'] = $startDate->format($format);

        $endDate = $data['end'];
        $endDate = new \DateTime($endDate, $localTz);
        $endDate->setTimeZone($utcTz);
        $data['end'] = $endDate->format($format);

        return $data;
    }


    /**
     * Need to convert datetime to UTC for the database to store, because
     * database should not deal with timezones
     *
     * @param  string  $datetime
     * @param  string  $timezone
     * @param  bool  $returnObject
     * @param  string  $inputFormat
     * @param  string  $outputFormat
     * @return string|DateTime|false
     * @throws Exception
     */
    public static function convertFromLocalToUtc(
        string $datetime,
        string $timezone,
        bool $returnObject = false,
        string $inputFormat = 'Y-m-d H:i:s',
        string $outputFormat = 'Y-m-d H:i:s'
    ): string|DateTime|false {

        $localTz = new \DateTimeZone($timezone);
        $utcTz = new \DateTimeZone('UTC');

        $datetimeObject = DateTime::createFromFormat($inputFormat, $datetime, $localTz);
        if ($datetimeObject === false) {
            // unsuccessful creation
            return false;
        }

        $datetimeObject->setTimeZone($utcTz);

        return $returnObject ? $datetimeObject : $datetimeObject->format($outputFormat);
    }


    /**
     * Need to convert datetime to UTC for the database to store, because
     * database should not deal with timezones
     *
     * @param  string  $datetime
     * @param  string  $timezone
     * @param  bool  $returnObject
     * @param  string  $inputFormat
     * @param  string  $outputFormat
     * @return string|DateTime|bool
     * @throws Exception
     */
    public static function convertFromUtcToLocal(
        string $datetime,
        string $timezone,
        bool $returnObject = false,
        string $inputFormat = 'Y-m-d H:i:s',
        string $outputFormat = 'Y-m-d H:i:s'
    ): string|DateTime|bool {
        $localTz = new \DateTimeZone($timezone);
        $utcTz = new \DateTimeZone('UTC');

        $datetimeObject = DateTime::createFromFormat($inputFormat, $datetime, $utcTz);
        if ($datetimeObject === false) {
            // unsuccessful creation
            return false;
        }

        $datetimeObject->setTimeZone($localTz);

        return $returnObject ? $datetimeObject : $datetimeObject->format($outputFormat);
    }
}
