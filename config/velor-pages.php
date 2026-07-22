<?php

declare(strict_types=1);

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Velor\Pages\Policies\PagePolicy;
use Velor\Pages\Resources\PageResource;
use Velor\Pages\Policies\ParagraphPolicy;
use Velor\Pages\Resources\ParagraphResource;

return [
    'enabled' => true,

    'resources' => [
        'page'      => PageResource::class,
        'paragraph' => ParagraphResource::class,
    ],

    'policies' => [
        Page::class      => PagePolicy::class,
        Paragraph::class => ParagraphPolicy::class,
    ],
];
