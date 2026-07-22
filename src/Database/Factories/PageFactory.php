<?php

declare(strict_types=1);

namespace Velor\Pages\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Velor\Pages\Models\Page;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_active' => fake()->boolean(),
            'name'      => fake()->unique()->word,
            'title'     => [
                'en' => fake()->sentence(),
                'nl' => fake()->sentence(),
            ],
            'slug' => [
                'en' => fake()->slug(),
                'nl' => fake()->slug(),
            ],
            'intro' => [
                'en' => fake()->paragraph(),
                'nl' => fake()->paragraph(),
            ],
            'meta_title' => [
                'en' => fake()->sentence(),
                'nl' => fake()->sentence(),
            ],
            'meta_description' => [
                'en' => fake()->sentence(),
                'nl' => fake()->sentence(),
            ],
            'meta_keywords' => [
                'en' => fake()->sentence(),
                'nl' => fake()->sentence(),
            ],
        ];
    }
}
