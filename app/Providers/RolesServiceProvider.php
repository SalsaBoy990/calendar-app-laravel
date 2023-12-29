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
    public function boot(): void
    {
        // php artisan cache:clear
        // php artisan config:clear
        // php artisan view:clear
        Blade::directive( 'role', function ( $roles ) {
            return "<?php if ( auth()->check() && auth()->user()->hasRoles( $roles )  ) : ?>";
        } );

        Blade::directive( 'endrole', function ( ) {
            return '<?php endif; ?>';
        } );

    }
}
