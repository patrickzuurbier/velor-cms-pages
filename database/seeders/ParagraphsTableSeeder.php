<?php

declare(strict_types=1);

namespace Velor\Pages\Database\Seeders;

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Illuminate\Database\Seeder;

class ParagraphsTableSeeder extends Seeder
{
    public function run(): void
    {
        $pages = Page::all();

        foreach ($pages as $page) {
            Paragraph::factory()->for($page)
                ->count(rand(1, 3))
                ->create();
        }
    }
}
