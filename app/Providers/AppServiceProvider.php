<?php

namespace App\Providers;

use App\Repositories\ExecutionRepository;
use App\Repositories\PlanRepository;
use App\Repositories\TaskRepository;
use App\Services\SpotService;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\View;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\UnauthorizedException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        // Repositories
        TaskRepository::class,
        PlanRepository::class,
        ExecutionRepository::class,
        // Services
        SpotService::class,
    ];

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
        Facades\View::composer('layout', function (View $view) {
            $view->with('nav');
            $view->with('username', Facades\Auth::user()->name);
        });

        Blade::directive('date', function (string $expression) {
            return "<?php echo empty($expression) ? '-' : \Illuminate\Support\Str::before($expression, ' '); ?>";
        });

        Blade::directive('datetime', function (string $expression) {
            return "<?php echo empty($expression) ? '-' : nl2br(e(str_replace(' ', PHP_EOL, $expression))); ?>";
        });
    }
}
