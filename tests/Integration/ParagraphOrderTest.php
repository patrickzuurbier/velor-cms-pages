<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Integration;

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Tests\Concerns\UsesAuthorization;
use Tests\Integration\AbstractDatabaseIntegrationTestCase;

class ParagraphOrderTest extends AbstractDatabaseIntegrationTestCase
{
    use UsesAuthorization;

    public function test_paragraph_rows_can_be_reordered_inside_page(): void
    {
        $this->actingAsAdmin();
        $page = Page::factory()->create();
        $first = Paragraph::factory()->for($page)->create([
            'name'       => 'First paragraph',
            'sort_order' => 1,
        ]);
        $second = Paragraph::factory()->for($page)->create([
            'name'       => 'Second paragraph',
            'sort_order' => 2,
        ]);

        $response = $this->postJson(route('resource-row-order.update', ['resource' => 'paragraphs']), [
            'context_key'   => 'page_id',
            'context_value' => (string) $page->getKey(),
            'paragraphs'    => [
                (string) $second->getKey(),
                (string) $first->getKey(),
            ],
        ]);

        $response->assertNoContent();
        $this->assertSame(2, $first->refresh()->sort_order);
        $this->assertSame(1, $second->refresh()->sort_order);
    }

    public function test_paragraph_rows_can_only_be_reordered_inside_their_page(): void
    {
        $this->actingAsAdmin();
        $page = Page::factory()->create();
        $otherPage = Page::factory()->create();
        $first = Paragraph::factory()->for($page)->create([
            'name'       => 'First paragraph',
            'sort_order' => 1,
        ]);
        $second = Paragraph::factory()->for($page)->create([
            'name'       => 'Second paragraph',
            'sort_order' => 2,
        ]);
        $other = Paragraph::factory()->for($otherPage)->create([
            'name'       => 'Other paragraph',
            'sort_order' => 1,
        ]);

        $response = $this->postJson(route('resource-row-order.update', ['resource' => 'paragraphs']), [
            'context_key'   => 'page_id',
            'context_value' => (string) $page->getKey(),
            'paragraphs'    => [
                (string) $other->getKey(),
                (string) $second->getKey(),
                (string) $first->getKey(),
            ],
        ]);

        $response->assertNoContent();
        $this->assertSame(2, $first->refresh()->sort_order);
        $this->assertSame(1, $second->refresh()->sort_order);
        $this->assertSame(1, $other->refresh()->sort_order);
    }

    public function test_paragraph_index_exposes_row_ordering_metadata(): void
    {
        $this->actingAsAdmin();
        $page = Page::factory()->create();

        Paragraph::factory()->for($page)->create([
            'name'       => 'First paragraph',
            'sort_order' => 1,
        ]);

        $response = $this->get(route('pages.paragraphs.index', ['page' => $page]));

        $response->assertOk();
        $response->assertSee('resource-order-table', false);
        $response->assertSee(route('resource-row-order.update', ['resource' => 'paragraphs']), false);
        $response->assertSee('data-items-key="paragraphs"', false);
        $response->assertSee('data-context-key="page_id"', false);
        $response->assertSee('data-context-value="' . $page->getKey() . '"', false);
    }
}
