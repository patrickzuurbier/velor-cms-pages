<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Integration;

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Illuminate\Contracts\Translation\Translator;
use Tests\Integration\AbstractIntegrationTestCase;
use Velor\Pages\Policies\PagePolicy;
use Velor\Pages\Resources\PageResource;
use Velor\Pages\Policies\ParagraphPolicy;
use Velor\Pages\Resources\ParagraphResource;
use App\Services\CmsNavigation\Contracts\SidebarItemRegistryInterface;
use App\Services\Resources\Contracts\ResourceRegistryInterface;
use App\Services\Authorization\Contracts\PolicyRegistryInterface;

class PagesPackageTest extends AbstractIntegrationTestCase
{
    public function test_it_registers_page_and_paragraph_resources(): void
    {
        $registry = $this->app->make(ResourceRegistryInterface::class);

        $this->assertSame(PageResource::class, $registry->resourceFor(Page::class));
        $this->assertSame(ParagraphResource::class, $registry->resourceFor(Paragraph::class));
    }

    public function test_it_registers_page_and_paragraph_policies_for_privileges(): void
    {
        $registry = $this->app->make(PolicyRegistryInterface::class);

        $this->assertSame(PagePolicy::class, $registry->privilegePolicies()[Page::class]);
        $this->assertSame(ParagraphPolicy::class, $registry->privilegePolicies()[Paragraph::class]);
    }

    public function test_it_registers_pages_sidebar_item_before_images(): void
    {
        $registry = $this->app->make(SidebarItemRegistryInterface::class);
        $routeNames = array_map(
            static fn ($item): string => $item->routeName,
            $registry->items(),
        );

        $this->assertSame('pages.index', $routeNames[0]);
        $this->assertSame('images.index', $routeNames[1]);
    }

    public function test_it_loads_page_and_paragraph_cms_routes(): void
    {
        $this->app['router']->getRoutes()->refreshNameLookups();

        $pageRoute = $this->app['router']
            ->getRoutes()
            ->getByName('pages.index');
        $paragraphRoute = $this->app['router']
            ->getRoutes()
            ->getByName('pages.paragraphs.index');

        $this->assertNotNull($pageRoute);
        $this->assertNotNull($paragraphRoute);
        $this->assertSame('cms/pages', $pageRoute->uri());
        $this->assertSame('cms/pages/{page}/paragraphs', $paragraphRoute->uri());
        $this->assertContains('web', $pageRoute->middleware());
        $this->assertContains('auth', $pageRoute->middleware());
    }

    public function test_it_loads_page_resource_translations_from_package_namespace(): void
    {
        $translator = $this->app->make(Translator::class);

        $translator->setLocale('en');

        $this->assertSame('Pages', $translator->get('velor-pages::resources.pages.plural'));
        $this->assertSame('Content', $translator->get('velor-pages::resources.paragraphs.fields.content'));

        $translator->setLocale('nl');

        $this->assertSame('Pagina\'s', $translator->get('velor-pages::resources.pages.plural'));
        $this->assertSame('Content', $translator->get('velor-pages::resources.paragraphs.fields.content'));
    }
}
