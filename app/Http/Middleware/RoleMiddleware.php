<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle( Request $request, Closure $next, $roles, $permission = null ) {

        // multiple roles from middleware arguments
        if ( str_contains($roles, '-') ) {
            $rolesArray = explode('-', $roles);
            $roles = [];

            foreach($rolesArray as $role) {
                $roles[] = $role;
            }
        } else {
            // only one role supplied through middleware
            $roleSlug = $roles;
            $roles = [];
            $roles[] = $roleSlug;
        }

        if ( ! auth()->user()->hasRoles( $roles ) ) {
            abort( 403 );
        }

        if ( $permission !== null && ! auth()->user()->can( $permission ) ) {
            abort( 403 );
        }

        return $next( $request );
    }
}
