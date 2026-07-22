<?php

declare(strict_types=1);

namespace Velor\Pages\Http\Controllers;

use App\Models\Page;
use App\Models\Paragraph;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ParagraphRequest;
use App\Services\Resources\Contracts\ResourceIndexQueryInterface;

class ParagraphController extends Controller
{
    public function __construct(
        protected ResourceIndexQueryInterface $resourceIndexQuery,
    ) {
    }

    public function index(Request $request, Page $page): View
    {
        return view(
            'cms.layouts.index',
            [
                'pagination' => $this->resourceIndexQuery->paginate(
                    model: Paragraph::class,
                    parent: $page,
                    relationship: 'paragraphs',
                    search: $request->string('search')->toString(),
                ),
                'model' => new Paragraph([
                    'page_id' => $page->getKey(),
                ]),
            ]
        );
    }

    public function create(Page $page): View
    {
        return view('cms.layouts.form', [
           'model' => new Paragraph([
               'page_id' => $page->getKey(),
           ]),
        ]);
    }

    public function store(ParagraphRequest $request, Page $page): RedirectResponse
    {
        $paragraph = $page->paragraphs()->create($request->validated());

        return redirect()
            ->route('pages.paragraphs.show', ['page' => $page->id, 'paragraph' => $paragraph->id])
            ->with('status', 'Paragraph created.');
    }

    public function show(Page $page, Paragraph $paragraph): View
    {
        return view('cms.layouts.show', [
            'model' => $paragraph,
        ]);
    }

    public function edit(Page $page, Paragraph $paragraph): View
    {
        return view('cms.layouts.form', [
            'model' => $paragraph,
        ]);
    }

    public function update(ParagraphRequest $request, Page $page, Paragraph $paragraph): RedirectResponse
    {
        $paragraph->update($request->validated());

        return redirect()
            ->route('pages.paragraphs.show', ['page' => $paragraph->page, 'paragraph' => $paragraph->id])
            ->with('status', 'Paragraph updated.');
    }

    public function destroy(Page $page, Paragraph $paragraph): RedirectResponse
    {
        $paragraph->delete();

        return redirect()
            ->route('pages.paragraphs.index', ['page' => $page->id])
            ->with('status', 'Paragraph deleted.');
    }
}
