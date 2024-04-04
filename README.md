# Drupal docker images

Build scripts for Docker images used by City-of-Helsinki Drupal projects.

## Requirements

Login to `ghcr.io` docker repository: https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry#authenticating-to-the-container-registry

## OpenShift Drupal docker image

Contains production Docker images for Drupal.

See:
- [City-of-Helsinki/drupal-helfi-platform/documentation/openshift.md](https://github.com/City-of-Helsinki/drupal-helfi-platform/blob/main/documentation/openshift.md) for documentation on how to use this image in your own project.
- [openshift/drupal/README.md](openshift/drupal/README.md) for developer documentation.

Available PHP versions: `8.3`, `8.2`, `8.1`:

- `ghcr.io/city-of-helsinki/drupal-docker-base:8.3`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.2`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.1`

## Local Drupal docker image

Based on [druidfi/drupal-web](https://github.com/druidfi/docker-images) with some additions.

See [local/drupal/README.md](local/drupal/README.md) for documentation.

Available PHP versions: `8.3`, `8.2`, `8.1`:

- `ghcr.io/city-of-helsinki/drupal-web:8.3`
- `ghcr.io/city-of-helsinki/drupal-web:8.2`
- `ghcr.io/city-of-helsinki/drupal-web:8.1`

## CI docker images

Contains Docker images for testing Drupal.

See:
- [ci/drupal](ci/drupal) for more documentation about Drupal CI image.

Available PHP versions: `8.3`, `8.2`, `8.1`:

- `ghcr.io/city-of-helsinki/drupal-php-docker:8.3-alpine`
- `ghcr.io/city-of-helsinki/drupal-php-docker:8.2-alpine`
- `ghcr.io/city-of-helsinki/drupal-php-docker:8.1-alpine`

See [test.yml](https://github.com/City-of-Helsinki/drupal-helfi-platform/blob/main/.github/workflows/test.yml.dist) for an example how to run tests using this image.

## OpenShift Drupal repository image

Docker image used to run [City-of-Helsinki/drupal-repository](https://github.com/City-of-Helsinki/drupal-repository).

See [openshift/drupal-repository](openshift/drupal-repository) for more documentation.

## Contact

Slack: #helfi-drupal (http://helsinkicity.slack.com/)
