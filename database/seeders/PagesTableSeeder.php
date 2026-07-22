<?php

declare(strict_types=1);

namespace Velor\Pages\Database\Seeders;

use Velor\Pages\Models\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    public function run(): void
    {
        Page::factory()->count(10)->create();
    }
}
