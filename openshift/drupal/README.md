# OpenShift Drupal base images

Supported PHP versions: `8.3`, `8.4`:

- `ghcr.io/city-of-helsinki/drupal-docker-base:8.3-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.3`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.4-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.4`

Testing environment uses `*-dev` images by default, and it's highly recommended to push changes to `*-dev` tag first and test them on testing environment before pushing them into production (`8.0` tag for example).

## Development

### Requirements

- Install [GoogleContainerTools/container-structure-test](https://github.com/GoogleContainerTools/container-structure-test)
- [docker/buildx](https://github.com/docker/buildx) (most likely already included by default)

### Testing production image locally

- Build the image: `make build-php84-dev` (change `php84` to whatever is your actual PHP version)
- Find the image ID of latest `docker images ghcr.io/city-of-helsinki/drupal-docker-base` image and copy the image ID
- Change project's `DRUPAL_IMAGE` (in `.env` file) to image ID from previous step
- Add `SIMPLETEST_DB: "mysql://drupal:drupal@db:3306/drupal"` to `app` service's environments in `compose.yaml`
- Change app container's volume from `/app` to `/var/www/html` (in `compose.yaml` file)
- Re/start containers: `docker-compose stop && docker compose up -d`

### Building

To build a specific image, call:

- `8.4-dev` tag: `make build-php84-dev`
- `8.4` tag: `make build-php84`

To run built image locally:

- `8.4-dev` tag: `make run-php84-dev`
- `8.4` tag: `make run-php84`

You can also build all tags at once: `make build-php-dev` (builds all `*-dev` tags) or `make build-php` (builds stable tags).

### Testing

We use [GoogleContainerTools/container-structure-test](https://github.com/GoogleContainerTools/container-structure-test) to test images. See [tests/](tests/) for available tests.

Running tests will always re/build the image first.

- Run tests against `8.4-dev` tag: `make test-php84-dev`
- Run tests against `8.4` tag: `make test-php84`

### Release process

Make sure youre logged in to `ghcr.io` Docker repository: https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry#authenticating-to-the-container-registry

Call `make push-php` or `make push-php-dev` to:
- Build all PHP versions at once
- Run all tests
- Push all built images to Docker repository

You can also release a specific tag by:

- `make push-php84-dev`: Build, tests and push the `8.4-dev` tag
- `make push-php84`: Builds, tests and push the `8.4` tag
