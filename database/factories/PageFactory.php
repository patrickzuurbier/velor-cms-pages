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
            'is_active' => $this->faker->boolean(),
            'name'      => $this->faker->unique()->word,
            'title'     => [
                'en' => $this->faker->sentence(),
                'nl' => $this->faker->sentence(),
            ],
            'slug' => [
                'en' => $this->faker->slug(),
                'nl' => $this->faker->slug(),
            ],
            'intro' => [
                'en' => $this->faker->paragraph(),
                'nl' => $this->faker->paragraph(),
            ],
            'meta_title' => [
                'en' => $this->faker->sentence(),
                'nl' => $this->faker->sentence(),
            ],
            'meta_description' => [
                'en' => $this->faker->sentence(),
                'nl' => $this->faker->sentence(),
            ],
            'meta_keywords' => [
                'en' => $this->faker->sentence(),
                'nl' => $this->faker->sentence(),
            ],
        ];
    }
}
