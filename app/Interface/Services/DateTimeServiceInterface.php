<?php

namespace App\Interface\Services;

use DateTime;
use Exception;

interface DateTimeServiceInterface
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
    public function convertDateTimesToUTC(
        array $data,
        string $timezone,
        string $format = 'Y-m-d H:i:s'
    ): array;


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
    public function convertFromLocalToUtc(
        string $datetime,
        string $timezone,
        bool $returnObject = false,
        string $inputFormat = 'Y-m-d H:i:s',
        string $outputFormat = 'Y-m-d H:i:s'
    ): string|DateTime|false;


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
    public function convertFromUtcToLocal(
        string $datetime,
        string $timezone,
        bool $returnObject = false,
        string $inputFormat = 'Y-m-d H:i:s',
        string $outputFormat = 'Y-m-d H:i:s'
    ): string|DateTime|bool;
}
