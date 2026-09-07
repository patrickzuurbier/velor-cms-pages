<?php

declare(strict_types=1);

namespace Velor\Pages\Repositories;

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
