<?php

namespace App\Providers;

use App\Listeners\SendPasswordChangedEmail;
use App\Listeners\SendWelcomeEmail;
use App\Models\PageVisit;
use App\Support\WebRoot;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        if ($origin = WebRoot::assetOrigin()) {
            URL::useAssetOrigin($origin);

            Vite::createAssetPathsUsing(
                fn (string $path) => $origin.'/'.ltrim($path, '/')
            );
        }

        Event::listen(Registered::class, SendWelcomeEmail::class);
        Event::listen(PasswordReset::class, SendPasswordChangedEmail::class);

        View::composer(['welcome', 'layouts.marketing'], function ($view): void {
            try {
                $view->with('pageVisits', PageVisit::total());
            } catch (Throwable) {
                $view->with('pageVisits', 0);
            }
        });
    }
}
