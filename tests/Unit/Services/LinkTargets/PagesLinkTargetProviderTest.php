<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Unit\Services\LinkTargets;

use App\Services\LinkTargets\Data\LinkTargetData;
use App\Services\LinkTargets\Data\LinkTargetGroupData;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use PHPUnit\Framework\TestCase;
use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Velor\Pages\Repositories\Contracts\PageRepositoryInterface;
use Velor\Pages\Services\LinkTargets\Providers\PagesLinkTargetProvider;

class PagesLinkTargetProviderTest extends TestCase
{
    public function test_it_provides_translated_page_and_paragraph_anchor_urls(): void
    {
        $page = new Page();
        $page->setRawAttributes([
            'id'   => 'page-id',
            'name' => 'About',
            'slug' => json_encode([
                'en' => 'about',
                'nl' => 'over-ons',
            ], JSON_THROW_ON_ERROR),
        ]);

        $paragraph = new Paragraph();
        $paragraph->setRawAttributes([
            'id'     => 'paragraph-id',
            'name'   => 'Team',
            'anchor' => json_encode([
                'en' => 'team',
                'nl' => 'ons-team',
            ], JSON_THROW_ON_ERROR),
        ]);
        $page->setRelation('paragraphs', new EloquentCollection([$paragraph]));

        $pageRepository = $this->createStub(PageRepositoryInterface::class);
        $pageRepository
            ->method('linkTargetPages')
            ->willReturn(new EloquentCollection([$page]));

        $translator = $this->createStub(Translator::class);
        $translator
            ->method('get')
            ->willReturnCallback(
                static fn (string $key): string => match ($key) {
                    'velor-pages::resources.link_targets.pages'             => 'Pages',
                    'velor-pages::resources.link_targets.paragraph_anchors' => 'Paragraph anchors',
                    default                                                 => $key,
                },
            );

        $provider = new PagesLinkTargetProvider($pageRepository, $translator);

        $this->assertEquals([
            new LinkTargetGroupData('Pages', [
                new LinkTargetData('pages.page-id', 'About', [
                    'en' => '/about',
                    'nl' => '/over-ons',
                ]),
            ]),
            new LinkTargetGroupData('Paragraph anchors', [
                new LinkTargetData('paragraphs.paragraph-id', 'About - Team', [
                    'en' => '/about#team',
                    'nl' => '/over-ons#ons-team',
                ]),
            ]),
        ], $provider->targetGroups());
    }
}
