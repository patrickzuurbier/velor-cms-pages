<?php

declare(strict_types=1);

return [
    'link_targets' => [
        'pages'             => 'Pagina\'s',
        'paragraph_anchors' => 'Paragraafankers',
    ],
    'pages' => [
        'singular' => 'Pagina',
        'plural'   => 'Pagina\'s',
        'fields'   => [
            'active'           => 'Actief',
            'intro'            => 'Intro',
            'meta_description' => 'Meta omschrijving',
            'meta_keywords'    => 'Meta zoekwoorden',
            'meta_title'       => 'Meta titel',
            'name'             => 'Naam',
            'slug'             => 'Slug',
            'title'            => 'Titel',
        ],
        'tabs' => [
            'paragraphs' => 'Paragrafen',
        ],
    ],
    'paragraphs' => [
        'singular' => 'Paragraaf',
        'plural'   => 'Paragrafen',
        'fields'   => [
            'active'     => 'Actief',
            'anchor'     => 'Anchor',
            'content'    => 'Content',
            'image'      => 'Afbeelding',
            'image_position' => 'Positie afbeelding',
            'intro'      => 'Intro',
            'name'       => 'Naam',
            'sort_order' => 'Volgorde',
            'title'      => 'Titel',
        ],
        'image_positions' => [
            'above' => 'Boven de tekst',
            'left'  => 'Links van de tekst',
            'right' => 'Rechts van de tekst',
        ],
        'tabs' => [
            'page' => 'Pagina',
        ],
    ],
];
