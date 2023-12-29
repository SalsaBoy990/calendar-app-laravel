<?php

namespace App\Interface\Services;

interface DateTimeServiceInterface
{
    /**
     * Need to convert datetime to UTC for the database to store, because
     * database should not deal with timezones
     *
     * @param  array  $data
     * @return array
     * @throws \Exception
     */
    public static function convertDateTimesToUTC(array $data): array;
}
