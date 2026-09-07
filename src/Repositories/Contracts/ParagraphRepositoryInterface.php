<?php

declare(strict_types=1);

namespace Velor\Pages\Repositories\Contracts;

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;

interface ParagraphRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createForPage(Page $page, array $attributes): Paragraph;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Paragraph $paragraph, array $attributes): Paragraph;

    public function delete(Paragraph $paragraph): void;
}
