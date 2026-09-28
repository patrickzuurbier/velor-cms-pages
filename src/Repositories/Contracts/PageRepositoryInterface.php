<?php

declare(strict_types=1);

namespace Velor\Pages\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Velor\Pages\Models\Page;

interface PageRepositoryInterface
{
    /**
     * @return EloquentCollection<int, Page>
     */
    public function linkTargetPages(): EloquentCollection;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Page;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Page $page, array $attributes): Page;

    public function delete(Page $page): void;
}
