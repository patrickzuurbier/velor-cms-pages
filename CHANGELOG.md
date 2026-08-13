# Changelog

All notable changes to Velor Pages will be documented in this file.

This package follows semantic versioning: `MAJOR.MINOR.PATCH`.

## [Unreleased]

### Fixed

- Clarified that package seeders can be run from the package namespace without
  publishing them first.

## [1.2.0] - 2026-08-13

### Changed

- Updated the package to use Velor CMS `^1.8` CMS menu registration
  contracts.

## [1.1.0] - 2026-08-03

### Added

- Added draggable paragraph ordering inside a page through Velor CMS core row
  ordering.

### Changed

- Renamed the persistent paragraph order column from `order` to `sort_order`.

## [1.0.3] - 2026-07-23

### Changed

- Documented the optional rich text image picker integration when the Velor CMS
  Images package is installed.

## [1.0.2] - 2026-07-22

### Changed

- Clarified package installation documentation for VCS installs, local path
  development, migrations, and seeders.

## [1.0.1] - 2026-07-22

### Changed

- Documented package migration and seeder setup for consuming applications.

## [1.0.0] - 2026-07-22

### Added

- Initial local package extraction for the Page and Paragraph resource slice.
- Package-owned models, factories, migrations, seeders, controllers, requests,
  resources, policies, translations, CMS menu registration, and CMS routes.
- Config override maps for project-owned resource and policy classes.
- MIT license file for the package repository.

[Unreleased]: https://github.com/patrickzuurbier/velor-cms-pages/compare/1.2.0...HEAD
[1.2.0]: https://github.com/patrickzuurbier/velor-cms-pages/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/patrickzuurbier/velor-cms-pages/compare/1.0.3...1.1.0
[1.0.3]: https://github.com/patrickzuurbier/velor-cms-pages/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/patrickzuurbier/velor-cms-pages/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/patrickzuurbier/velor-cms-pages/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/patrickzuurbier/velor-cms-pages/releases/tag/1.0.0
