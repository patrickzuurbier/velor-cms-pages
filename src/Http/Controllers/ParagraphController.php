<?php

declare(strict_types=1);

namespace Velor\Pages\Http\Controllers;

use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Velor\Pages\Resources\ParagraphResource;
use Velor\Pages\Http\Requests\ParagraphRequest;
use Velor\Pages\Repositories\Contracts\ParagraphRepositoryInterface;
use App\Services\Resources\Contracts\ResourceIndexQueryInterface;
use App\Services\Images\Contracts\ImageUploadServiceInterface;
use App\Services\Resources\Http\Controllers\AbstractResourceController;

class ParagraphController extends AbstractResourceController
{
    protected static string $resource = ParagraphResource::class;

    public function __construct(
        protected ResourceIndexQueryInterface $resourceIndexQuery,
        protected ImageUploadServiceInterface $imageUploadService,
        protected ParagraphResource $paragraphResource,
        protected ParagraphRepositoryInterface $paragraphRepository,
    ) {
    }

    public function index(Request $request, Page $page): View
    {
        return view(
            'velor.layouts.index',
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
        return view('velor.layouts.form', [
            'resource' => $this->paragraphResource,
        ]);
    }

    public function store(ParagraphRequest $request, Page $page): RedirectResponse
    {
        $paragraph = $this->imageUploadService->storeResourceUploads(
            $this->paragraphResource,
            $request->validated(),
            fn (array $attributes): Paragraph => $this->paragraphRepository->createForPage($page, $attributes),
        );

        return redirect()
            ->route('pages.paragraphs.show', ['page' => $page->getKey(), 'paragraph' => $paragraph->getKey()])
            ->with('status', 'Paragraph created.');
    }

    public function show(Page $page, Paragraph $paragraph): View
    {
        return view('velor.layouts.show', [
            'resource' => $this->paragraphResource,
        ]);
    }

    public function edit(Page $page, Paragraph $paragraph): View
    {
        return view('velor.layouts.form', [
            'resource' => $this->paragraphResource,
        ]);
    }

    public function update(ParagraphRequest $request, Page $page, Paragraph $paragraph): RedirectResponse
    {
        $this->imageUploadService->storeResourceUploads(
            $this->paragraphResource,
            $request->validated(),
            fn (array $attributes): Paragraph => $this->paragraphRepository->update($paragraph, $attributes),
        );

        return redirect()
            ->route('pages.paragraphs.show', ['page' => $page->getKey(), 'paragraph' => $paragraph->getKey()])
            ->with('status', 'Paragraph updated.');
    }

    public function destroy(Page $page, Paragraph $paragraph): RedirectResponse
    {
        $this->imageUploadService->deleteResourceWithUsages(
            $paragraph,
            function () use ($paragraph): void {
                $this->paragraphRepository->delete($paragraph);
            },
        );

        return redirect()
            ->route('pages.paragraphs.index', ['page' => $page->getKey()])
            ->with('status', 'Paragraph deleted.');
    }
}
