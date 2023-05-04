<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
//        php artisan view:clear
        Blade::directive( 'role', function ( $roleName ) {
            return "<?php if ( auth()->check() && auth()->user()->hasRole( $roleName )  ) : ?>";
        } );

        Blade::directive( 'endrole', function ( ) {
            return '<?php endif; ?>';
        } );

    }
}
