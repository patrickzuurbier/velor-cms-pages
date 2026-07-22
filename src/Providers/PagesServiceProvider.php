<?php

declare(strict_types=1);

namespace Velor\Pages\Providers;

use App\Models\Page;
use App\Models\Paragraph;
use App\Policies\PagePolicy;
use App\Policies\ParagraphPolicy;
use Illuminate\Support\ServiceProvider;
use Velor\Pages\Resources\PageResource;
use Velor\Pages\Resources\ParagraphResource;
use App\Services\Resources\Contracts\ResourceRegistryInterface;
use App\Services\CmsRouting\Contracts\CmsRouteRegistrarInterface;
use App\Services\Authorization\Contracts\PolicyRegistryInterface;

class PagesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/velor-pages.php', 'velor-pages');
    }

    public function boot(
        CmsRouteRegistrarInterface $cmsRoutes,
        ResourceRegistryInterface $resources,
        PolicyRegistryInterface $policies,
    ): void {
        $resources->register(PageResource::class);
        $resources->register(ParagraphResource::class);

        $policies->register(Page::class, PagePolicy::class);
        $policies->register(Paragraph::class, ParagraphPolicy::class);

        $cmsRoutes->loadAuthenticated(__DIR__ . '/../../routes/cms.php');

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
