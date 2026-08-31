<?php

declare(strict_types=1);

namespace Velor\Pages\Providers;

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use App\Services\CmsMenu\Data\CmsMenuItemData;
use Illuminate\Support\ServiceProvider;
use Velor\Pages\Policies\PagePolicy;
use Velor\Pages\Resources\PageResource;
use Velor\Pages\Policies\ParagraphPolicy;
use Velor\Pages\Resources\ParagraphResource;
use App\Services\Resources\Contracts\ResourceRegistryInterface;
use App\Services\CmsRouting\Contracts\CmsRouteRegistrarInterface;
use App\Services\Authorization\Contracts\PolicyRegistryInterface;
use App\Services\CmsMenu\Contracts\CmsMenuItemRegistryInterface;

class PagesServiceProvider extends ServiceProvider
{
    public function boot(
        CmsRouteRegistrarInterface $cmsRoutes,
        ResourceRegistryInterface $resources,
        PolicyRegistryInterface $policies,
        CmsMenuItemRegistryInterface $cmsMenuItems,
    ): void {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'velor-pages');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $resources->register($this->app->make(PageResource::class));
        $resources->register($this->app->make(ParagraphResource::class));

        $policies->register(Page::class, PagePolicy::class);
        $policies->register(Paragraph::class, ParagraphPolicy::class);

        $cmsMenuItems->registerBefore(
            'images.index',
            new CmsMenuItemData(Page::class, 'pages.index', 'velor-pages::resources.pages.plural', 'bi-files'),
        );

        $cmsRoutes->loadAuthenticated(__DIR__ . '/../../routes/cms.php');

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
