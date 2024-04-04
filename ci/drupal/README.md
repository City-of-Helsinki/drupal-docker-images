# Drupal CI image

Available PHP versions: `8.3`, `8.2`, `8.1`:

- `ghcr.io/city-of-helsinki/drupal-php-docker:8.3-alpine`
- `ghcr.io/city-of-helsinki/drupal-php-docker:8.2-alpine`
- `ghcr.io/city-of-helsinki/drupal-php-docker:8.1-alpine`

All images should include all packages necessary to run all Drupal tests (including FunctionalJavascript tests using `chromium-driver`).

See [test.yml](https://github.com/City-of-Helsinki/drupal-helfi-platform/blob/main/.github/workflows/test.yml.dist) for an example how to run tests using this image.

## Environment variables

- `SIMPLETEST_DB=mysql://drupal:drupal@db:3306/drupal`
- `SIMPLETEST_BASE_URL=http://127.0.0.1:8888`
- `COMPOSER_HOME=/tmp/.composer`: The composer home is overridden to mitigate permission issues
- `COMPOSER_MIRROR_PATH_REPOS=1`: Individual module tests use `composer config repositories N $GITHUB_WORKSPACE && composer require drupal/$MODULE_NAME` to symlink module's codebase from $GITHUB_WORKSPACE to public/modules/contrib folder. PHPUnit seems to sometimes report an incorrect test coverage if the test folder is a symlink. This setting changes composer to mirror the content instead of symlinking it. See https://helsinkisolutionoffice.atlassian.net/browse/UHF-8566
- `COMPOSER_DISCARD_CHANGES=true`: Automatically discard uncommitted changes

## Development

### Requirements

- [GoogleContainerTools/container-structure-test](https://github.com/GoogleContainerTools/container-structure-test)

### Building

To build a specific image, call:

- `8.1-alpine` tag: `make build-php81`

To run built image locally:

- `8.1-alpine` tag: `make run-php81`

### Testing

We use [GoogleContainerTools/container-structure-test](https://github.com/GoogleContainerTools/container-structure-test) to test images. See [tests/](tests/) for available tests.

Running tests will always rebuild the image first.

- Run tests against `8.1-alpine` tag: `make test-php81`

### Release process

Calling `make push-php*` will:
- Run the build script
- Run all tests
- Push the image to docker repository

Release a specific tag:

- Release `8.1-alpine` tag: `make push-php81`

Release all tags at once: `make push-php`.
