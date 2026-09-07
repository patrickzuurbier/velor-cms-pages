<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Integration;

use Tests\Integration\AbstractDatabaseIntegrationTestCase;
use Velor\Pages\Models\Page;
use Velor\Pages\Repositories\Contracts\PageRepositoryInterface;

class PageRepositoryTest extends AbstractDatabaseIntegrationTestCase
{
    protected PageRepositoryInterface $pageRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pageRepository = $this->app->make(PageRepositoryInterface::class);
    }

    public function test_it_can_create_update_and_delete_pages(): void
    {
        $page = $this->pageRepository->create([
            'is_active' => true,
            'name'      => 'Home',
            'title'     => ['en' => 'Home', 'nl' => 'Home'],
            'slug'      => ['en' => 'home', 'nl' => 'home'],
        ]);

        $this->assertInstanceOf(Page::class, $page);

        $this->pageRepository->update($page, [
            'name' => 'Homepage',
        ]);

        $this->assertDatabaseHas('pages', [
            'id'   => $page->getKey(),
            'name' => 'Homepage',
        ]);

        $this->pageRepository->delete($page);

        $this->assertDatabaseMissing('pages', [
            'id' => $page->getKey(),
        ]);
    }
}
