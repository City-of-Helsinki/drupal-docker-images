# Drupal base images

## OpenShift

Supported PHP versions: `8.3`, `8.4`:

- `ghcr.io/city-of-helsinki/drupal-docker-base:latest-dev` (PHP 8.4)
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.4-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:latest` (PHP 8.4)
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.4`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.3-dev`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.3`

## Local / CI

Supported PHP versions: `8.3`, `8.4`:

- `ghcr.io/city-of-helsinki/drupal-web:latest-dev` (PHP 8.4)
- `ghcr.io/city-of-helsinki/drupal-web:8.4-dev`
- `ghcr.io/city-of-helsinki/drupal-web:latest` (PHP 8.4)
- `ghcr.io/city-of-helsinki/drupal-web:8.4`
- `ghcr.io/city-of-helsinki/drupal-web:8.3-dev`
- `ghcr.io/city-of-helsinki/drupal-web:8.3`

## Development

Make sure you're logged in to `ghcr.io` Docker repository: https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry#authenticating-to-the-container-registry

See [Makefile](Makefile) for up-to-date build commands.

## Updating PHP version

Make sure to update `latest` tag accordingly in th [docker-bake.hcl](/drupal/docker-bake.hcl) file. The `latest` tag should always point to the development version of the latest supported PHP version, `8.4-dev` for example.

### Build & Push

```bash
# Build and push all images 
make push-prod
# Build and push drupal-docker-base images
make push-php
# Build and push drupal-web images
make push-php-web
# Build and push a specific version of drupal-docker-base image
make push-php84
# Build and push a specific version of drupal-web image
make push-php84-web

# Build and push a development version of drupal-docker-base images
make push-php-dev
# Build and push a development version of drupal-web-images
make push-php-web-dev
# Build and push a development version of drupal-docker-base images
make push-php84-dev
# Build and push a specific version of drupal-web development image
make push-php84-web-dev
```

### Testing

We use `phpunit` to verify images are built correctly. See [tests/](tests/) for available tests.

#### Run tests

```bash
# Test all images
make test
# Test drupal-docker-base images
make test-php
# Test drupal-web images
make test-php-web
```

### Release process

You can either build and push images manually, or build images using GitHub Actions.

Go to `Actions` -> `drupal-build.yml` -> Click "Run workflow" -> Select an environment -> Run workflow.
