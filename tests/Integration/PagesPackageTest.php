<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Integration;

use App\Services\Authorization\Contracts\PolicyRegistryInterface;
use App\Services\CmsMenu\Contracts\CmsMenuItemRegistryInterface;
use App\Services\CmsMenu\Data\CmsMenuItemData;
use App\Services\Resources\Contracts\ResourceRegistryInterface;
use Illuminate\Contracts\Translation\Translator;
use Tests\Integration\AbstractIntegrationTestCase;
use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Velor\Pages\Policies\PagePolicy;
use Velor\Pages\Policies\ParagraphPolicy;
use Velor\Pages\Resources\PageResource;
use Velor\Pages\Resources\ParagraphResource;

class PagesPackageTest extends AbstractIntegrationTestCase
{
    public function test_it_registers_page_and_paragraph_resources(): void
    {
        $registry = $this->app->make(ResourceRegistryInterface::class);

        $this->assertInstanceOf(PageResource::class, $registry->resourceFor(Page::class));
        $this->assertInstanceOf(ParagraphResource::class, $registry->resourceFor(Paragraph::class));
    }

    public function test_it_registers_page_and_paragraph_policies_for_privileges(): void
    {
        $registry = $this->app->make(PolicyRegistryInterface::class);

        $this->assertSame(PagePolicy::class, $registry->privilegePolicies()[Page::class]);
        $this->assertSame(ParagraphPolicy::class, $registry->privilegePolicies()[Paragraph::class]);
    }

    public function test_it_registers_pages_cms_menu_item(): void
    {
        $registry = $this->app->make(CmsMenuItemRegistryInterface::class);
        $item = collect($registry->items())->firstWhere('routeName', 'pages.index');

        $this->assertInstanceOf(CmsMenuItemData::class, $item);
        $this->assertSame(Page::class, $item->modelClass);
        $this->assertSame('velor-pages::resources.pages.plural', $item->label);
        $this->assertSame('bi-files', $item->icon);
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
