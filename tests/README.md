# Velor Pages Tests

While this package is developed inside the Velor CMS repository, integration
tests live in the host test suite under `tests/Integration/Packages/VelorPages`.

When this package moves to its own repository, package tests should cover:

- Service provider boot.
- Resource, policy, sidebar, and route registration.
- Translation loading.
- Model factories and database migrations.
- Core form and index flows against a consuming Velor CMS test application.
