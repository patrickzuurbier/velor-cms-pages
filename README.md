# Velor CMS Pages

Pages is a first-party resource plugin for Velor CMS. It owns the page and
paragraph vertical slice and uses Velor CMS extension points for resources,
routes, menu items, policies, privileges, config, translations, migrations, and
tests.

This package owns Page and Paragraph models, factories, migrations, seeders,
controllers, form requests, resource classes, policies, resource translations,
resource registration, policy registration, CMS menu registration, and CMS
routes.

## Contents

- [Local Development](#local-development)
- [Installation](#installation)
- [Package Contents](#package-contents)
- [Routes](#routes)
- [Models And Factories](#models-and-factories)
- [Controllers](#controllers)
- [Form Requests](#form-requests)
- [Resources](#resources)
- [CMS Menu](#cms-menu)
- [Policies](#policies)
- [Publishing](#publishing)
- [Testing](#testing)
- [Uninstalling](#uninstalling)
- [License](#license)
- [Development Notes](#development-notes)

## Local Development

The Velor CMS repository can temporarily load this package through a Composer
path repository during package development:

```json
{
    "type": "path",
    "url": "packages/velor/pages",
    "options": {
        "symlink": true
    }
}
```

Install or update it from inside the app container:

```bash
docker compose exec app composer update patrickzuurbier/velor-cms-pages --with-dependencies
```

With `symlink` enabled, edits in this directory are used by the host app without
copying files into `vendor`. Remove the path repository again before testing a
real install from GitHub.

## Installation

Install the package in a Velor CMS application:

```bash
composer config repositories.velor-cms-pages vcs https://github.com/patrickzuurbier/velor-cms-pages.git
composer require patrickzuurbier/velor-cms-pages:^1.0
```

For local path development inside the Velor CMS repository, temporarily point
Composer to the package workspace and update the package from the app
container:

```bash
composer config repositories.velor-cms-pages path packages/velor/pages
docker compose exec app composer update patrickzuurbier/velor-cms-pages --with-dependencies
```

With a local path repository, Composer can symlink
`vendor/patrickzuurbier/velor-cms-pages` to `packages/velor/pages`. Remove the
local path repository again before testing a real install from GitHub:

```bash
composer config --unset repositories.velor-cms-pages
composer config repositories.velor-cms-pages vcs https://github.com/patrickzuurbier/velor-cms-pages.git
```

The package service provider is auto-discovered by Laravel. When enabled, it
registers resources, policies, CMS menu items, CMS routes, translations, and
migrations.

Publish the package migrations when the application should own them:

```bash
php artisan vendor:publish --tag=velor-pages-migrations
```

Then run the database migrations:

```bash
php artisan migrate
```

For local development, use the Makefile from the Velor CMS host application:

```bash
make migrate
```

Optionally publish and run the package seeders for local data:

```bash
php artisan vendor:publish --tag=velor-pages-seeders
php artisan db:seed --class=Velor\Pages\Database\Seeders\PagesTableSeeder
php artisan db:seed --class=Velor\Pages\Database\Seeders\ParagraphsTableSeeder
```

## Package Contents

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

## Routes

CMS routes live in `routes/cms.php`. The service provider loads that file
through Velor CMS' route registrar, so this package does not define the global
`cms` prefix or CMS middleware itself. The route names and URLs remain the same
as the original host application routes.

Paragraphs use Velor CMS core row ordering:

```text
POST /cms/resource-row-order/paragraphs
```

The package marks the Paragraph model as row-orderable and scopes ordering by
`page_id`, so paragraphs can only be reordered inside the current page. The
endpoint is protected by the Paragraph policy's `reorder` ability.

## Models And Factories

The package models extend Velor CMS' `AbstractModel` and explicitly resolve
their package factories, so `Page::factory()` and `Paragraph::factory()` keep
working while the package is developed through a Composer path repository.

## Controllers

The package controllers render the generic Velor CMS index, show, and form
views.

## Form Requests

The package form requests use Velor CMS' resource validation factories so
validation remains defined by the package resources.

## Resources

The service provider registers the package Page and Paragraph resource classes
through Velor CMS' `ResourceRegistryInterface`.

Published config can override the resource classes when a project needs custom
resources:

```php
'resources' => [
    'page' => App\Resources\PageResource::class,
],
```

The Paragraph resource uses Velor CMS' core `RichText` field. Rich text image
insertion is optional and should be provided by an images/media package through
Velor CMS extension points. This package must not require an images package.

When `patrickzuurbier/velor-cms-images` is installed, its `images.picker` route
is detected by Velor CMS core and the `RichText` image button opens the Velor
CMS image picker. Without that route, Quill keeps its original URL-based image
insertion behaviour.

## CMS Menu

The package registers its menu item through Velor CMS'
`CmsMenuItemRegistryInterface`. It anchors the Pages item before the Images
route, so the package owns its menu entry while the host menu remains
predictable.

## Policies

The service provider registers the package Page and Paragraph policies through
Velor CMS' `PolicyRegistryInterface`. Registered policies are also exposed to
role privileges by default.

Published config can override policy classes per model:

```php
'policies' => [
    Velor\Pages\Models\Page::class => App\Policies\PagePolicy::class,
],
```

## Publishing

The package should remain in `vendor` by default. Publish only project-owned
files:

```bash
php artisan vendor:publish --tag=velor-pages-config
php artisan vendor:publish --tag=velor-pages-migrations
php artisan vendor:publish --tag=velor-pages-seeders
php artisan vendor:publish --tag=velor-pages-lang
```

Optional override tags can be added later:

Resource, controller, policy, and model customization should use publishable
stubs with host namespaces plus package config override maps. Do not publish raw
package PHP classes into `app/`, because their namespaces still belong to the
package.

## Testing

Package integration is covered by the Velor CMS host application test suite:

```bash
make test
```

The package-level `tests` namespace is reserved for standalone package tests.
Those tests should verify provider boot, resource/policy/CMS menu registration,
routes, translations, factories, and migrations against a consuming Velor CMS
test application.

## Uninstalling

The package can be removed from a consuming application with Composer:

```bash
composer remove patrickzuurbier/velor-cms-pages
```

Velor CMS core should continue to boot without this package. Package-owned CMS
routes, resources, policies, CMS menu items, translations, and loaded migrations
disappear with the package. Existing database tables and published files are
project data and are not deleted automatically by Composer.

## License

This package is open-sourced software licensed under the MIT license.

## Development Notes

Pages and paragraphs belong together. The package owns their models, factories,
migrations, seeders, relationship, tabs, ordering, translated fields, slugs,
anchors, and rich text configuration.

Velor CMS core must not contain page-specific route, menu, or field logic after
this package is installed.
