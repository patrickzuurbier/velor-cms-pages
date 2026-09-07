<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Integration;

use Tests\Integration\AbstractDatabaseIntegrationTestCase;
use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Velor\Pages\Repositories\Contracts\ParagraphRepositoryInterface;

class ParagraphRepositoryTest extends AbstractDatabaseIntegrationTestCase
{
    protected ParagraphRepositoryInterface $paragraphRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paragraphRepository = $this->app->make(ParagraphRepositoryInterface::class);
    }

    public function test_it_can_create_update_and_delete_paragraphs_for_pages(): void
    {
        $page = Page::factory()->create();

        $paragraph = $this->paragraphRepository->createForPage($page, [
            'is_active' => true,
            'name'      => 'Intro',
            'title'     => ['en' => 'Intro', 'nl' => 'Intro'],
        ]);

        $this->assertInstanceOf(Paragraph::class, $paragraph);
        $this->assertSame($page->getKey(), $paragraph->getAttribute('page_id'));

        $this->paragraphRepository->update($paragraph, [
            'name' => 'Introduction',
        ]);

        $this->assertDatabaseHas('paragraphs', [
            'id'   => $paragraph->getKey(),
            'name' => 'Introduction',
        ]);

        $this->paragraphRepository->delete($paragraph);

        $this->assertDatabaseMissing('paragraphs', [
            'id' => $paragraph->getKey(),
        ]);
    }
}
