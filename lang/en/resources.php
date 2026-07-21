<?php

declare(strict_types=1);

return [
    'pages' => [
        'singular' => 'Page',
        'plural'   => 'Pages',
        'fields'   => [
            'active'           => 'Active',
            'intro'            => 'Intro',
            'meta_description' => 'Meta description',
            'meta_keywords'    => 'Meta keywords',
            'meta_title'       => 'Meta title',
            'name'             => 'Name',
            'slug'             => 'Slug',
            'title'            => 'Title',
        ],
        'tabs' => [
            'paragraphs' => 'Paragraphs',
        ],
    ],
    'paragraphs' => [
        'singular' => 'Paragraph',
        'plural'   => 'Paragraphs',
        'fields'   => [
            'active'  => 'Active',
            'anchor'  => 'Anchor',
            'content' => 'Content',
            'intro'   => 'Intro',
            'name'    => 'Name',
            'order'   => 'Order',
            'title'   => 'Title',
        ],
        'tabs' => [
            'page' => 'Page',
        ],
    ],
];
