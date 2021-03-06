# OpenShift Drupal base images

Available PHP versions: `8.0`, `8.1`:

- `ghcr.io/city-of-helsinki/drupal-docker-base:8.0-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.0`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.1-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.1`

Using PHP 8.1 is not recommended until [microsoft/mysqlnd_azure](https://github.com/microsoft/mysqlnd_azure) adds a PHP 8.1 support. See:

- https://github.com/microsoft/mysqlnd_azure/issues/31
- https://github.com/microsoft/mysqlnd_azure/issues/30

## Development

All dev/testing environments use `*-dev` images by default and it's highly recommended to push changes to `*-dev` tag first and test them on testing environment before pushing them into production (`8.0` tag for example).

To test production images locally:
- Change project's `DRUPAL_IMAGE` to `ghcr.io/city-of-helsinki/drupal-docker-base:8.0-dev` (in `.env` file)
- Change app container's volume from `/app` to `/var/www/html` (in `docker-compose.yml` file)

### Requirements

- [GoogleContainerTools/container-structure-test](https://github.com/GoogleContainerTools/container-structure-test)
- [docker/buildx](https://github.com/docker/buildx) (most likely already included by default)

### Building

To build a specific image, call:

- `8.0-dev` tag: `make build-php80-dev`
- `8.0` tag: `make build-php80`
- `8.1-dev` tag: `make build-php81-dev`
- `8.1` tag: `make build-php81`

To run built image locally:

- `8.0-dev` tag: `make run-php80-dev`
- `8.0` tag: `make run-php80`
- `8.1-dev` tag: `make run-php81-dev`
- `8.1` tag: `make run-php81`

You can also build all tags at once: `make build-php-dev` (builds all `*-dev` tags) or `make build-php` (builds stable tags).

### Testing

We use [GoogleContainerTools/container-structure-test](https://github.com/GoogleContainerTools/container-structure-test) to test images. See [tests/](tests/) for available tests.

Running tests will always rebuild the image first.

- Run tests against `8.0-dev` tag: `make test-php80-dev`
- Run tests against `8.0` tag: `make test-php80`
- Run tests against `8.1-dev` tag: `make test-php81-dev`
- Run tests against `8.1` tag: `make test-php81`

### Release process

Calling `make push-php*` will:
- Run the build script
- Run all tests
- Push the image to docker repository

Release a specific tag:

- Release `8.0-dev` tag: `make push-php80-dev`
- Release `8.0` tag: `make push-php80`
- Release `8.1-dev` tag: `make push-php81-dev`
- Release `8.1` tag: `make push-php81`

Release all `*-dev` tags: `make push-php-dev` or release all stable tags: `make push-php`.
