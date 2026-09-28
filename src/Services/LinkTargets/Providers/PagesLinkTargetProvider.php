<?php

declare(strict_types=1);

namespace Velor\Pages\Services\LinkTargets\Providers;

use App\Services\LinkTargets\Contracts\LinkTargetProviderInterface;
use App\Services\LinkTargets\Data\LinkTargetData;
use App\Services\LinkTargets\Data\LinkTargetGroupData;
use Illuminate\Contracts\Translation\Translator;
use Velor\Pages\Models\Page;
use Velor\Pages\Models\Paragraph;
use Velor\Pages\Repositories\Contracts\PageRepositoryInterface;

class PagesLinkTargetProvider implements LinkTargetProviderInterface
{
    public function __construct(
        protected PageRepositoryInterface $pageRepository,
        protected Translator $translator,
    ) {
    }

    /**
     * @return array<int, LinkTargetGroupData>
     */
    public function targetGroups(): array
    {
        $pageTargets = [];
        $paragraphTargets = [];

        foreach ($this->pageRepository->linkTargetPages() as $page) {
            $pageUrls = $this->pageUrls($page);

            if ($pageUrls !== []) {
                $pageTargets[] = new LinkTargetData(
                    key: 'pages.' . $page->getKey(),
                    label: (string) $page->getAttribute('name'),
                    urls: $pageUrls,
                );
            }

            foreach ($page->paragraphs as $paragraph) {
                $paragraphUrls = $this->paragraphUrls($paragraph, $pageUrls);

                if ($paragraphUrls === []) {
                    continue;
                }

                $paragraphTargets[] = new LinkTargetData(
                    key: 'paragraphs.' . $paragraph->getKey(),
                    label: sprintf(
                        '%s - %s',
                        $page->getAttribute('name'),
                        $paragraph->getAttribute('name'),
                    ),
                    urls: $paragraphUrls,
                );
            }
        }

        $groups = [];

        if ($pageTargets !== []) {
            $groups[] = new LinkTargetGroupData(
                (string) $this->translator->get('velor-pages::resources.link_targets.pages'),
                $pageTargets,
            );
        }

        if ($paragraphTargets !== []) {
            $groups[] = new LinkTargetGroupData(
                (string) $this->translator->get('velor-pages::resources.link_targets.paragraph_anchors'),
                $paragraphTargets,
            );
        }

        return $groups;
    }

    /**
     * @return array<string, string>
     */
    protected function pageUrls(Page $page): array
    {
        $urls = [];

        foreach ($page->getTranslations('slug') as $locale => $slug) {
            $slug = trim($slug, '/ ');

            if ($slug !== '') {
                $urls[$locale] = '/' . $slug;
            }
        }

        return $urls;
    }

    /**
     * @param Paragraph $paragraph
     * @param array<string, string> $pageUrls
     * @return array<string, string>
     */
    protected function paragraphUrls(Paragraph $paragraph, array $pageUrls): array
    {
        $urls = [];

        foreach ($paragraph->getTranslations('anchor') as $locale => $anchor) {
            $anchor = trim($anchor, '# ');

            if ($anchor !== '' && isset($pageUrls[$locale])) {
                $urls[$locale] = $pageUrls[$locale] . '#' . $anchor;
            }
        }

        return $urls;
    }
}
