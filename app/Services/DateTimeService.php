<?php

namespace App\Services;

use App\Interface\Services\DateTimeServiceInterface;

class DateTimeService implements DateTimeServiceInterface
{


    /**
     * Need to convert datetime to UTC for the database to store, because
     * database should not deal with timezones (start and end dates)
     *
     * @param  array  $data
     * @return array
     * @throws \Exception
     */
    public static function convertDateTimesToUTC(array $data): array
    {
        $userTz = $data['timezone'] ?? 'Europe/Budapest';

        $localTz = new \DateTimeZone($userTz);
        $utcTz = new \DateTimeZone('UTC');

        $startDate = $data['start'];
        $startDate = new \DateTime($startDate, $localTz);
        $startDate->setTimeZone($utcTz);
        $data['start'] = $startDate->format('Y-m-d H:i:s');

        $endDate = $data['end'];
        $endDate = new \DateTime($endDate, $localTz);
        $endDate->setTimeZone($utcTz);
        $data['end'] = $endDate->format('Y-m-d H:i:s');

        return $data;
    }

}
