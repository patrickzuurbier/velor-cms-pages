<?php

declare(strict_types=1);

namespace Velor\Pages\Http\Controllers;

use Velor\Pages\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Velor\Pages\Resources\PageResource;
use Velor\Pages\Http\Requests\PageRequest;
use Velor\Pages\Repositories\Contracts\PageRepositoryInterface;
use App\Services\Resources\Contracts\ResourceIndexQueryInterface;

class PageController extends Controller
{
    public function __construct(
        protected ResourceIndexQueryInterface $resourceIndexQuery,
        protected PageResource $pageResource,
        protected PageRepositoryInterface $pageRepository,
    ) {
    }

    public function index(Request $request): View
    {
        return view(
            'cms.layouts.index',
            [
                'pagination' => $this->resourceIndexQuery->paginate(
                    resource: $this->pageResource,
                    search: $request->string('search')->toString(),
                ),
                'resource' => $this->pageResource,
            ]
        );
    }

    public function create(): View
    {
        return view('cms.layouts.form', [
            'resource' => $this->pageResource,
        ]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = $this->pageRepository->create($request->validated());

        return redirect()
            ->route('pages.show', ['page' => $page->getKey()])
            ->with('status', 'Page created.');
    }

    public function show(Page $page): View
    {
        return view('cms.layouts.show', [
            'resource' => $this->pageResource,
        ]);
    }

    public function edit(Page $page): View
    {
        return view('cms.layouts.form', [
            'resource' => $this->pageResource,
        ]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $this->pageRepository->update($page, $request->validated());

        return redirect()
            ->route('pages.show', ['page' => $page->getKey()])
            ->with('status', 'Page updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->pageRepository->delete($page);

        return redirect()
            ->route('pages.index')
            ->with('status', 'Page deleted.');
    }
}
