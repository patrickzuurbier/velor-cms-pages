<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Integration;

use App\Models\Role;
use App\Models\User;
use Tests\Integration\AbstractDatabaseIntegrationTestCase;
use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;

class PageAuthorizationTest extends AbstractDatabaseIntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::factory()->create([
            'name'       => 'Unauthorized',
            'slug'       => 'unauthorized',
            'privileges' => [],
        ]);

        $this->actingAs(User::factory()->create([
            'role_id' => $role->getKey(),
        ]));
    }

    public function test_page_read_routes_require_their_policy_privileges(): void
    {
        $page = Page::factory()->create();

        $this->getJson(route('pages.index'))->assertForbidden();
        $this->getJson(route('pages.create'))->assertForbidden();
        $this->getJson(route('pages.show', ['page' => $page]))->assertForbidden();
    }

    public function test_page_write_routes_require_their_policy_privileges(): void
    {
        $page = Page::factory()->create(['name' => 'Original page']);

        $this->postJson(route('pages.store'))->assertForbidden();
        $this->putJson(route('pages.update', ['page' => $page]))->assertForbidden();
        $this->deleteJson(route('pages.destroy', ['page' => $page]))->assertForbidden();

        $this->assertSame('Original page', $page->refresh()->getAttribute('name'));
        $this->assertSame(1, Page::query()->count());
    }

    public function test_paragraph_read_routes_require_their_policy_privileges(): void
    {
        $page = Page::factory()->create();
        $paragraph = Paragraph::factory()->for($page)->create();

        $this->getJson(route('pages.paragraphs.index', ['page' => $page]))->assertForbidden();
        $this->getJson(route('pages.paragraphs.create', ['page' => $page]))->assertForbidden();
        $this->getJson(route('pages.paragraphs.show', [
            'page'      => $page,
            'paragraph' => $paragraph,
        ]))->assertForbidden();
    }

    public function test_paragraph_write_routes_require_their_policy_privileges(): void
    {
        $page = Page::factory()->create();
        $paragraph = Paragraph::factory()->for($page)->create(['name' => 'Original paragraph']);

        $this->postJson(route('pages.paragraphs.store', ['page' => $page]))->assertForbidden();
        $this->putJson(route('pages.paragraphs.update', [
            'page'      => $page,
            'paragraph' => $paragraph,
        ]))->assertForbidden();
        $this->deleteJson(route('pages.paragraphs.destroy', [
            'page'      => $page,
            'paragraph' => $paragraph,
        ]))->assertForbidden();

        $this->assertSame('Original paragraph', $paragraph->refresh()->getAttribute('name'));
        $this->assertSame(1, Paragraph::query()->count());
    }
}
