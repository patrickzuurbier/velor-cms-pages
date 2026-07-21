<?php

declare(strict_types=1);

namespace Velor\Pages\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CmsRouting\Contracts\CmsRouteRegistrarInterface;

class PagesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/velor-pages.php', 'velor-pages');
    }

    public function boot(CmsRouteRegistrarInterface $cmsRouteRegistrar): void
    {
        $cmsRouteRegistrar->loadAuthenticated(__DIR__ . '/../../routes/cms.php');

        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'velor-pages');

        $this->publishes([
            __DIR__ . '/../../config/velor-pages.php' => $this->app->configPath('velor-pages.php'),
        ], 'velor-pages-config');

        $this->publishes([
            __DIR__ . '/../../database/migrations' => $this->app->databasePath('migrations'),
        ], 'velor-pages-migrations');

        $this->publishes([
            __DIR__ . '/../../database/seeders' => $this->app->databasePath('seeders'),
        ], 'velor-pages-seeders');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/velor-pages'),
        ], 'velor-pages-lang');
    }
}
