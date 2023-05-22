<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ClientPolicy {
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     *
     * @return Response|bool
     */
    public function viewAny( User $user ): Response|bool {
        return $user->hasRoles( 'super-administrator|administrator' );
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Client  $client
     *
     * @return Response|bool
     */
    public function view( User $user, Client $client ): Response|bool {
        return $user->hasRoles( 'super-administrator|administrator' );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     *
     * @return Response|bool
     */
    public function create( User $user ): Response|bool {
        return $user->hasRoles( 'super-administrator|administrator' );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Client  $client
     *
     * @return Response|bool
     */
    public function update( User $user, Client $client ): Response|bool {
        return $user->hasRoles( 'super-administrator|administrator' );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Client  $client
     *
     * @return Response|bool
     */
    public function delete( User $user, Client $client ): Response|bool {
        return $user->hasRoles( 'super-administrator|administrator' );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Event  $event
     *
     * @return Response|bool
     */
    public function restore( User $user, Event $event ) {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Event  $event
     *
     * @return Response|bool
     */
    public function forceDelete( User $user, Event $event ) {
        //
    }
}
