<?php

declare(strict_types=1);

namespace Velor\Pages\Database\Factories;

use App\Concerns\Database\Factories\FakesTranslatables;
use Illuminate\Database\Eloquent\Factories\Factory;
use Velor\Pages\Models\Paragraph;

/**
 * @extends Factory<Paragraph>
 */
class ParagraphFactory extends Factory
{
    use FakesTranslatables;

    protected $model = Paragraph::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'      => $this->faker->word(),
            'title'     => $this->fakeTranslations(fn () => $this->faker->sentence()),
            'intro'     => $this->fakeTranslations(fn () => $this->faker->paragraph()),
            'content'   => $this->fakeTranslations(fn () => $this->faker->paragraph()),
            'anchor'    => $this->fakeTranslations(fn () => $this->faker->url()),
            'is_active' => $this->faker->boolean(),
            'order'     => $this->faker->unique()->numberBetween(1, 100),
        ];
    }
}
