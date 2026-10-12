<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Integration;

use App\Models\Image;
use App\Models\ImageUsage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\UsesAuthorization;
use Tests\Integration\AbstractDatabaseIntegrationTestCase;
use Velor\Pages\Enums\ParagraphImagePositionEnum;
use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;

class ParagraphImageTest extends AbstractDatabaseIntegrationTestCase
{
    use UsesAuthorization;

    protected const DISK = 'paragraph-image-test';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(self::DISK);
        $this->app['config']->set('images.disk', self::DISK);
        $this->app['config']->set('images.formats', []);
    }

    public function test_it_stores_a_contextual_image_separately_from_paragraph_content(): void
    {
        $this->actingAsAdmin();
        $page = Page::factory()->create();

        $response = $this->post(route('pages.paragraphs.store', ['page' => $page]), [
            'is_active'      => '1',
            'name'           => 'Image paragraph',
            'title'          => ['en' => null, 'nl' => null],
            'intro'          => ['en' => null, 'nl' => null],
            'content'        => ['en' => '<p>English content</p>', 'nl' => '<p>Nederlandse inhoud</p>'],
            'image_position' => ParagraphImagePositionEnum::LEFT->value,
            'anchor'         => ['en' => null, 'nl' => null],
            'sort_order'     => 1,
            'image'          => [
                'present'  => '1',
                'order'    => ['upload:0'],
                'uploads'  => [UploadedFile::fake()->image('paragraph.jpg', 640, 480)],
                'alt_text' => [[
                    'en' => 'Paragraph illustration',
                    'nl' => 'Paragraafillustratie',
                ]],
                'description' => [[
                    'en' => 'Visible caption',
                    'nl' => 'Zichtbaar bijschrift',
                ]],
            ],
        ]);

        $paragraph = Paragraph::query()->sole();
        $image = Image::query()->sole();
        $usage = ImageUsage::query()->sole();

        $response->assertRedirect(route('pages.paragraphs.show', [
            'page'      => $page->getKey(),
            'paragraph' => $paragraph->getKey(),
        ]));
        $this->assertSame(ParagraphImagePositionEnum::LEFT, $paragraph->image_position);
        $this->assertSame('<p>Nederlandse inhoud</p>', $paragraph->getTranslation('content', 'nl'));
        $this->assertSame($image->getKey(), $usage->image_id);
        $this->assertSame('paragraphs', $usage->resource_type);
        $this->assertSame($paragraph->getKey(), $usage->resource_id);
        $this->assertSame('image', $usage->field);
        $this->assertSame('Paragraafillustratie', $usage->getTranslation('alt_text', 'nl'));
        $this->assertSame('Visible caption', $usage->getTranslation('description', 'en'));
    }

    public function test_deleting_a_paragraph_removes_its_usage_but_retains_the_image(): void
    {
        $this->actingAsAdmin();
        $page = Page::factory()->create();
        $paragraph = Paragraph::factory()->for($page)->create();
        $usage = ImageUsage::factory()->create([
            'resource_type' => $paragraph->getTable(),
            'resource_id'   => $paragraph->getKey(),
            'field'         => 'image',
        ]);

        $this->delete(route('pages.paragraphs.destroy', [
            'page'      => $page,
            'paragraph' => $paragraph,
        ]))->assertRedirect(route('pages.paragraphs.index', ['page' => $page]));

        $this->assertDatabaseMissing('paragraphs', ['id' => $paragraph->getKey()]);
        $this->assertDatabaseMissing('image_usages', ['id' => $usage->getKey()]);
        $this->assertDatabaseHas('images', ['id' => $usage->image_id]);
    }
}
