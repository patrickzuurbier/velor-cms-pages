<?php

declare(strict_types=1);

namespace Velor\Pages\Tests\Integration;

use App\Contracts\Factories\Validation\ResourceValidationRulesFactoryInterface;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Tests\Integration\AbstractIntegrationTestCase;
use Velor\Pages\Resources\ParagraphResource;

class ParagraphValidationTest extends AbstractIntegrationTestCase
{
    public function test_optional_translations_may_be_empty(): void
    {
        $rules = $this->app
            ->make(ResourceValidationRulesFactoryInterface::class)
            ->make($this->app->make(ParagraphResource::class));

        $validator = $this->app->make(ValidationFactory::class)->make(
            [
                'is_active'  => false,
                'name'       => 'Text paragraph',
                'title'      => ['en' => null, 'nl' => null],
                'intro'      => ['en' => null, 'nl' => null],
                'content'    => ['en' => null, 'nl' => null],
                'anchor'     => ['en' => null, 'nl' => null],
                'sort_order' => 1,
            ],
            $rules,
        );

        $this->assertFalse(
            $validator->fails(),
            $validator->errors()->toJson(),
        );
    }
}
