<?php

declare(strict_types=1);

namespace Velor\Pages\Http\Controllers;

use Velor\Pages\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Velor\Pages\Http\Requests\PageRequest;
use App\Services\Resources\Contracts\ResourceIndexQueryInterface;

class PageController extends Controller
{
    public function __construct(
        protected ResourceIndexQueryInterface $resourceIndexQuery,
    ) {
    }

    public function index(Request $request): View
    {
        return view(
            'cms.layouts.index',
            [
                'pagination' => $this->resourceIndexQuery->paginate(
                    model: Page::class,
                    search: $request->string('search')->toString(),
                ),
                'model' => new Page(),
            ]
        );
    }

    public function create(): View
    {
        return view('cms.layouts.form', [
            'model' => new Page(),
        ]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = Page::create($request->validated());

        return redirect()
            ->route('pages.show', ['page' => $page->getKey()])
            ->with('status', 'Page created.');
    }

    public function show(Page $page): View
    {
        return view('cms.layouts.show', [
            'model' => $page,
        ]);
    }

    public function edit(Page $page): View
    {
        return view('cms.layouts.form', [
            'model' => $page,
        ]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update($request->validated());

        return redirect()
            ->route('pages.show', ['page' => $page->getKey()])
            ->with('status', 'Page updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()
            ->route('pages.index')
            ->with('status', 'Page deleted.');
    }
}
