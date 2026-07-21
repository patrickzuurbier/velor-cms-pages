# Velor Pages

Pages is a future first-party resource plugin for Velor CMS. It will own the
page and paragraph vertical slice once Velor CMS has package extension points
for resources, routes, menu items, policies, privileges, config, translations,
migrations, and tests.

This package is currently a skeleton only. The runtime Page and Paragraph
implementation still lives in the main application.

## Intended Contents

```text
src/Models/Page.php
src/Models/Paragraph.php
src/Resources/PageResource.php
src/Resources/ParagraphResource.php
src/Http/Controllers/PageController.php
src/Http/Controllers/ParagraphController.php
src/Http/Requests/PageRequest.php
src/Http/Requests/ParagraphRequest.php
src/Policies/PagePolicy.php
src/Policies/ParagraphPolicy.php
database/migrations
database/seeders
lang/en/resources.php
lang/nl/resources.php
routes/cms.php
config/velor-pages.php
tests
```

## Publishing

The package should remain in `vendor` by default. Publish only project-owned
files or explicit override classes:

```bash
php artisan vendor:publish --tag=velor-pages-config
php artisan vendor:publish --tag=velor-pages-migrations
php artisan vendor:publish --tag=velor-pages-lang
```

Optional override tags can be added later:

```bash
php artisan vendor:publish --tag=velor-pages-resources
php artisan vendor:publish --tag=velor-pages-controllers
php artisan vendor:publish --tag=velor-pages-policies
```

## Extraction Notes

Pages and paragraphs should be extracted together. The package owns their
relationship, tabs, ordering, translated fields, slugs, anchors, and rich text
configuration.

Velor CMS core must not contain page-specific route, menu, or field logic after
the extraction.
