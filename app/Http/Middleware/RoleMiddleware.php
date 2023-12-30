<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @param $roles
     * @param  null  $permission
     * @return Response|RedirectResponse
     */
    public function handle( Request $request, Closure $next, $roles, $permission = null ) {

        if ( ! auth()->user()->hasRoles( $roles ) ) {
            abort( 403 );
        }

        if ( $permission !== null && ! auth()->user()->can( $permission ) ) {
            abort( 403 );
        }

        return $next( $request );
    }
}
