<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('limit', function ($expression) {
            list($string, $limit, $end) = explode(',', $expression);
            return "<?php echo str_limit($string, $limit, $end); ?>";
        });
    }


}
