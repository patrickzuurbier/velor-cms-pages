<?php

declare(strict_types=1);

namespace Velor\Pages\Resources;

use Velor\Pages\Models\Paragraph;
use App\Resources\AbstractResource;
use App\Resources\Fields\Checkbox;
use App\Resources\Fields\Field;
use App\Resources\Fields\Number;
use App\Resources\Fields\RichText;
use App\Resources\Fields\Text;
use App\Resources\Fields\Textarea;
use App\Resources\Tabs\ResourceTab;
use App\Resources\Validation\UniqueTranslation;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;

class ParagraphResource extends AbstractResource
{
    public static string $model = Paragraph::class;

    public function __construct(
        protected Request $request,
        protected UrlGenerator $urlGenerator,
    ) {
    }

    public function titleAttribute(): string
    {
        return 'name';
    }

    /**
     * @return array<int, Field>
     */
    public function fields(): array
    {
        return [
            Checkbox::make('is_active')
                ->label(__('velor-pages::resources.paragraphs.fields.active'))
                ->sortable(),
            Text::make('name')
                ->label(__('velor-pages::resources.paragraphs.fields.name'))
                ->sortable()
                ->searchable()
                ->rules([
                    'required',
                    'max:255',
                ]),
            Text::make('title')
                ->label(__('velor-pages::resources.paragraphs.fields.title'))
                ->translatable()
                ->sortable()
                ->searchable()
                ->rules([
                    'max:255',
                ]),
            Textarea::make('intro')
                ->label(__('velor-pages::resources.paragraphs.fields.intro'))
                ->translatable()
                ->sortable()
                ->searchable(),
            RichText::make('content')
                ->label(__('velor-pages::resources.paragraphs.fields.content'))
                ->config('paragraph.content')
                ->translatable()
                ->sortable()
                ->searchable()
                ->rules([
                    'nullable',
                ]),
            Text::make('anchor')
                ->label(__('velor-pages::resources.paragraphs.fields.anchor'))
                ->translatable()
                ->sortable()
                ->searchable()
                ->rules([
                    'nullable',
                    UniqueTranslation::make('paragraphs'),
                ]),
            Number::make('order')
                ->label(__('velor-pages::resources.paragraphs.fields.order'))
                ->sortable(),
        ];
    }

    /**
     * @return array<int, ResourceTab>
     */
    public function tabs(): array
    {
        return [
            ResourceTab::make(__('velor-pages::resources.paragraphs.tabs.page'))
                ->url(fn (Paragraph $paragraph, mixed $_urlGenerator): string => $this->urlGenerator->route(
                    'pages.show',
                    ['page' => $paragraph->getAttribute('page_id')]
                ))
                ->onlyOnIndex(),
        ];
    }
}
