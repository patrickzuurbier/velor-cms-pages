<?php

declare(strict_types=1);

namespace Velor\Pages\Repositories;

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Velor\Pages\Repositories\Contracts\ParagraphRepositoryInterface;

/**
 * @extends AbstractRepository<Paragraph>
 */
class ParagraphRepository extends AbstractRepository implements ParagraphRepositoryInterface
{
    /**
     * @var class-string<Paragraph>
     */
    protected string $model = Paragraph::class;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createForPage(Page $page, array $attributes): Paragraph
    {
        return $page->paragraphs()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Paragraph $paragraph, array $attributes): Paragraph
    {
        $paragraph->fill($attributes);
        $paragraph->save();

        return $paragraph;
    }

    public function delete(Paragraph $paragraph): void
    {
        $paragraph->delete();
    }
}
