<?php

declare(strict_types=1);

namespace Velor\Pages\Resources;

use Velor\Pages\Models\Page;
use App\Resources\AbstractResource;
use App\Resources\Fields\Checkbox;
use App\Resources\Fields\Field;
use App\Resources\Fields\Text;
use App\Resources\Fields\Textarea;
use App\Resources\Tabs\ResourceTab;
use App\Resources\Validation\Unique;
use App\Resources\Validation\UniqueTranslation;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;

class PageResource extends AbstractResource
{
    public static string $model = Page::class;

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
                ->label(__('velor-pages::resources.pages.fields.active'))
                ->sortable(),
            Text::make('name')
                ->label(__('velor-pages::resources.pages.fields.name'))
                ->sortable()
                ->searchable()
                ->rules([
                    'required',
                    Unique::make('pages'),
                ]),
            Text::make('title')
                ->label(__('velor-pages::resources.pages.fields.title'))
                ->translatable()
                ->sortable()
                ->searchable()
                ->rules([
                    'required',
                    'max:255',
                ]),
            Text::make('slug')
                ->label(__('velor-pages::resources.pages.fields.slug'))
                ->translatable()
                ->sortable()
                ->searchable()
                ->rules([
                    'required',
                    'max:255',
                    UniqueTranslation::make('pages'),
                ]),
            Textarea::make('intro')
                ->label(__('velor-pages::resources.pages.fields.intro'))
                ->translatable()
                ->sortable()
                ->searchable()
                ->rules([
                    'nullable',
                ]),
            Text::make('meta_title')
                ->label(__('velor-pages::resources.pages.fields.meta_title'))
                ->translatable()
                ->sortable()
                ->searchable()
                ->rules([
                    'nullable',
                    'max:255',
                ]),
            Textarea::make('meta_description')
                ->label(__('velor-pages::resources.pages.fields.meta_description'))
                ->translatable()
                ->sortable()
                ->searchable()
                ->rules([
                    'nullable',
                ]),
            Textarea::make('meta_keywords')
                ->label(__('velor-pages::resources.pages.fields.meta_keywords'))
                ->translatable()
                ->sortable()
                ->searchable()
                ->rules([
                    'nullable',
                ]),
        ];
    }

    /**
     * @return array<int, ResourceTab>
     */
    public function tabs(): array
    {
        return [
            ResourceTab::make(__('velor-pages::resources.pages.tabs.paragraphs'))
                ->url(fn (Page $page, mixed $_urlGenerator): string => $this->urlGenerator->route(
                    'pages.paragraphs.index',
                    ['page' => $page->getRouteKey()]
                ))
                ->onlyOnShow(),
        ];
    }
}
