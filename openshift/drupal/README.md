# OpenShift Drupal base images

Available PHP versions: `8.2`, `8.1`, `8.0`:

- `ghcr.io/city-of-helsinki/drupal-docker-base:8.2-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.2`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.1-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.1`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.0-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.0`

## Development

Testing environment uses `*-dev` images by default and it's highly recommended to push changes to `*-dev` tag first and test them on testing environment before pushing them into production (`8.0` tag for example).

To test production images locally:
- Build the image: `make build-php81-dev` (change `php81` to whatever is your actual PHP version)
- Find the image ID of latest built image: `docker images ghcr.io/city-of-helsinki/drupal-docker-base` and copy the image ID
- Change project's `DRUPAL_IMAGE` (in `.env` file) to image ID from previous step
- Change app container's volume from `/app` to `/var/www/html` (in `docker-compose.yml` file)
- Re/start containers: `docker-compose stop && docker compose up -d`

### Requirements

- Install [GoogleContainerTools/container-structure-test](https://github.com/GoogleContainerTools/container-structure-test)
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

Running tests will always re/build the image first.

- Run tests against `8.1-dev` tag: `make test-php81-dev`
- Run tests against `8.1` tag: `make test-php81`

### Release process

Make sure youre logged in to `ghcr.io` docker repository: https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry#authenticating-to-the-container-registry

Call `make push-php` or `make push-php-dev` to:
- Build all PHP versions at once
- Run all tests
- Push all built images to Docker repository

You can also release a specific tag by:

- `make push-php81-dev`: Build, tests and pushes the `8.1-dev` tag
- `make push-php81`: Builds, tests and pushes the `8.1` tag

See Makefile for available PHP versions.
