# OpenShift Drupal base images

Supported PHP versions: `8.3`, `8.4`:

- `ghcr.io/city-of-helsinki/drupal-docker-base:8.3-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.3`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.4-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.4`

Testing environment uses `*-dev` images by default, and it's highly recommended to push changes to `*-dev` tag first and test them on testing environment before pushing them into production (`8.0` tag for example).

## Development

### Requirements

- [docker/buildx](https://github.com/docker/buildx) (most likely already included by default)

### Building

To build a specific image, call:

- `8.4-dev` tag: `make push-php84-dev`
- `8.4` tag: `make push-php84`

You can also build all tags at once: `make push-php-dev` (builds all `*-dev` tags) or `make push-php` (builds stable tags).

### Testing

- Run tests against `8.4-dev` tag: `make test-php84-dev`
- Run tests against `8.4` tag: `make test-php84`

### Release process

Make sure you're logged in to `ghcr.io` Docker repository: https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry#authenticating-to-the-container-registry

Call `make push-php` or `make push-php-dev` to:
- Build all PHP versions at once
- Run all tests
- Push all built images to Docker repository

You can also release a specific tag by:

- `make push-php84-dev`: Build, tests and push the `8.4-dev` tag
- `make push-php84`: Builds, tests and push the `8.4` tag
