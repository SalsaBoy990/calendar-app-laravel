<?php

namespace App\Interface\Repository;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EventRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getEvents(): Collection;


    /**
     * @param  int  $eventId
     * @return Event
     */
    public function getEventById(int $eventId): Model;


    /**
     * @param  Event  $event
     * @param  array  $data
     * @param  array  $workerIds
     * @return Event
     */
    public function updateEvent(Event $event, array $data, array $workerIds): Event;


    /**
     * @param  array  $data
     * @param  array  $workerIds
     * @return Event
     */
    public function createEvent(array $data, array $workerIds): Event;


    /**
     * @param  Event  $event
     * @return void
     */
    public function deleteEvent(Event $event): void;

}
