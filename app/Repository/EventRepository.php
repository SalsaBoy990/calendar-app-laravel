<?php

namespace App\Repository;

use App\Interface\Repository\EventRepositoryInterface;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EventRepository implements EventRepositoryInterface
{

    /**
     * @return Collection
     */
    public function getEvents(): Collection
    {
        return Event::with(['workers'])->with([
            'client' => function ($query) {
                $query->withTrashed();
            }
        ])->get();
    }


    /**
     * @param  int  $eventId
     * @return Model
     */
    public function getEventById(int $eventId): Model
    {
        return Event::with(['workers'])->with([
            'client' => function ($query) {
                $query->withTrashed();
            }
        ])->where('id', '=', $eventId)->first();
    }


    /**
     * @param  Event  $event
     * @param  array  $data
     * @param  array  $workerIds
     * @return Event
     */
    public function updateEvent(Event $event, array $data, array $workerIds): Event
    {
        $event->update($data);
        $event->workers()->sync($workerIds);
        return $event;
    }


    /**
     * @param  array  $data
     * @param  array  $workerIds
     * @return Event
     */
    public function createEvent(array $data, array $workerIds): Event
    {
        $event = Event::create($data);
        $event->workers()->sync($workerIds);
        return $event;
    }


    /**
     * @param  Event  $event
     * @return void
     */
    public function deleteEvent(Event $event): void
    {
        $event->delete();
    }

}
