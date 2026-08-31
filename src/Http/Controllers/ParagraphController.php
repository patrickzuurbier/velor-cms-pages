<?php

declare(strict_types=1);

namespace Velor\Pages\Http\Controllers;

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Velor\Pages\Resources\ParagraphResource;
use Velor\Pages\Http\Requests\ParagraphRequest;
use App\Services\Resources\Contracts\ResourceIndexQueryInterface;

class ParagraphController extends Controller
{
    public function __construct(
        protected ResourceIndexQueryInterface $resourceIndexQuery,
        protected ParagraphResource $paragraphResource,
    ) {
    }

    public function index(Request $request, Page $page): View
    {
        return view(
            'cms.layouts.index',
            [
                'pagination' => $this->resourceIndexQuery->paginate(
                    resource: $this->paragraphResource,
                    parent: $page,
                    relationship: 'paragraphs',
                    search: $request->string('search')->toString(),
                ),
                'resource' => $this->paragraphResource,
            ]
        );
    }

    public function create(Page $page): View
    {
        return view('cms.layouts.form', [
            'resource' => $this->paragraphResource,
        ]);
    }

    public function store(ParagraphRequest $request, Page $page): RedirectResponse
    {
        $paragraph = $page->paragraphs()->create($request->validated());

        return redirect()
            ->route('pages.paragraphs.show', ['page' => $page->getKey(), 'paragraph' => $paragraph->getKey()])
            ->with('status', 'Paragraph created.');
    }

    public function show(Page $page, Paragraph $paragraph): View
    {
        return view('cms.layouts.show', [
            'resource' => $this->paragraphResource,
        ]);
    }

    public function edit(Page $page, Paragraph $paragraph): View
    {
        return view('cms.layouts.form', [
            'resource' => $this->paragraphResource,
        ]);
    }

    public function update(ParagraphRequest $request, Page $page, Paragraph $paragraph): RedirectResponse
    {
        $paragraph->update($request->validated());

        return redirect()
            ->route('pages.paragraphs.show', ['page' => $page->getKey(), 'paragraph' => $paragraph->getKey()])
            ->with('status', 'Paragraph updated.');
    }

    public function destroy(Page $page, Paragraph $paragraph): RedirectResponse
    {
        $paragraph->delete();

        return redirect()
            ->route('pages.paragraphs.index', ['page' => $page->getKey()])
            ->with('status', 'Paragraph deleted.');
    }
}
