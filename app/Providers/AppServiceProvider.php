<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

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
        Paginator::useTailwind();

        Gate::define('admin', function ($user) {
            return $user->is_admin == true;
        });

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Scramble::configure()->routes(function (Route $route) {
            return Str::startsWith($route->uri, 'api/');
        })->withDocumentTransformers(function (OpenApi $openApi){
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}
