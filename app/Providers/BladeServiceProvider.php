<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Blade money directive
        Blade::directive('money', function ($amount) {
            return "<?php echo 'Rp' . number_format($amount, 0, ',', '.'); ?>";
        });
    }
}
