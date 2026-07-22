<?php

declare(strict_types=1);

namespace Velor\Pages\Providers;

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use App\Data\Cms\SidebarItemData;
use Illuminate\Support\ServiceProvider;
use Velor\Pages\Policies\PagePolicy;
use Velor\Pages\Resources\PageResource;
use Velor\Pages\Policies\ParagraphPolicy;
use Velor\Pages\Resources\ParagraphResource;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use App\Services\Resources\Contracts\ResourceRegistryInterface;
use App\Services\CmsRouting\Contracts\CmsRouteRegistrarInterface;
use App\Services\Authorization\Contracts\PolicyRegistryInterface;
use App\Services\CmsNavigation\Contracts\SidebarItemRegistryInterface;

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
        SidebarItemRegistryInterface $sidebarItems,
        ConfigRepository $config,
    ): void {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'velor-pages');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if ($config->get('velor-pages.enabled') === true) {
            $resources->register($this->configuredClass($config, 'velor-pages.resources.page', PageResource::class));
            $resources->register($this->configuredClass($config, 'velor-pages.resources.paragraph', ParagraphResource::class));

            $policies->register(Page::class, $this->configuredClass($config, 'velor-pages.policies.' . Page::class, PagePolicy::class));
            $policies->register(Paragraph::class, $this->configuredClass($config, 'velor-pages.policies.' . Paragraph::class, ParagraphPolicy::class));

            $sidebarItems->registerBefore(
                'images.index',
                new SidebarItemData(Page::class, 'pages.index', 'velor-pages::resources.pages.plural', 'bi-files'),
            );

            $cmsRoutes->loadAuthenticated(__DIR__ . '/../../routes/cms.php');
        }

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

    /**
     * @param class-string $default
     *
     * @return class-string
     */
    protected function configuredClass(ConfigRepository $config, string $key, string $default): string
    {
        $value = $config->get($key);

        if (! is_string($value) || ! class_exists($value)) {
            return $default;
        }

        return $value;
    }
}
