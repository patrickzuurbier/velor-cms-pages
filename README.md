# Velor Pages

Pages is a first-party resource plugin proving ground for Velor CMS. It will
own the page and paragraph vertical slice once Velor CMS has package extension
points for resources, routes, menu items, policies, privileges, config,
translations, migrations, and tests.

This package currently owns Page and Paragraph models, factories, controllers,
form requests, resource classes, policies, resource translations, resource
registration, policy registration, and CMS routes. Database migrations and
seeders still live in the host application until migration ownership is ready.

## Local Development

The host application can load this package through a Composer path repository:

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
docker compose exec app composer update velor/pages --with-dependencies
```

With `symlink` enabled, edits in this directory are used by the host app without
copying files into `vendor`.

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

## Routes

CMS routes live in `routes/cms.php`. The service provider loads that file
through Velor CMS' route registrar, so this package does not define the global
`cms` prefix or CMS middleware itself. The route names and URLs remain the same
as the original host application routes.

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

## Sidebar

The package should register sidebar entries through Velor CMS'
`SidebarItemRegistryInterface` once package sidebar ordering is solved. For
now, the host application still owns final sidebar composition so menu order
stays explicit.

## Policies

The service provider registers the package Page and Paragraph policies through
Velor CMS' `PolicyRegistryInterface`. Registered policies are also exposed to
role privileges by default.

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
models, factories, migrations, seeders, relationship, tabs, ordering,
translated fields, slugs, anchors, and rich text configuration.

Velor CMS core must not contain page-specific route, menu, or field logic after
the extraction.
