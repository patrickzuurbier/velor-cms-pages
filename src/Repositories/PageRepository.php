<?php

declare(strict_types=1);

namespace Velor\Pages\Repositories;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Velor\Pages\Models\Page;
use Velor\Pages\Repositories\Contracts\PageRepositoryInterface;

/**
 * @extends AbstractRepository<Page>
 */
class PageRepository extends AbstractRepository implements PageRepositoryInterface
{
    /**
     * @var class-string<Page>
     */
    protected string $model = Page::class;

    /**
     * @return EloquentCollection<int, Page>
     */
    public function linkTargetPages(): EloquentCollection
    {
        return $this->query()
            ->where('is_active', true)
            ->with([
                'paragraphs' => static function (Relation $relation): void {
                    $relation->getQuery()
                        ->where('is_active', true)
                        ->orderBy('sort_order');
                },
            ])
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Page
    {
        return $this->query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Page $page, array $attributes): Page
    {
        $page->fill($attributes);
        $page->save();

        return $page;
    }

    public function delete(Page $page): void
    {
        $page->delete();
    }
}
