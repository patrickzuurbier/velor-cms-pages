<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Integration;

use Tests\Integration\AbstractDatabaseIntegrationTestCase;
use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
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

    public function test_it_returns_active_link_target_pages_with_active_paragraphs(): void
    {
        $activePage = Page::factory()->create([
            'is_active' => true,
            'name'      => 'About',
        ]);
        Page::factory()->create([
            'is_active' => false,
            'name'      => 'Hidden',
        ]);
        $activeParagraph = Paragraph::factory()->for($activePage)->create([
            'is_active' => true,
            'name'      => 'Team',
        ]);
        Paragraph::factory()->for($activePage)->create([
            'is_active' => false,
            'name'      => 'Hidden paragraph',
        ]);

        $pages = $this->pageRepository->linkTargetPages();
        $resultPage = $pages->first();

        $this->assertCount(1, $pages);
        $this->assertInstanceOf(Page::class, $resultPage);
        $this->assertTrue($resultPage->is($activePage));
        $this->assertCount(1, $resultPage->paragraphs);

        $resultParagraph = $resultPage->paragraphs->first();

        $this->assertInstanceOf(Paragraph::class, $resultParagraph);
        $this->assertTrue($resultParagraph->is($activeParagraph));
    }
}
