# Drupal docker images

Build scripts for Docker images used by City-of-Helsinki Drupal projects.

## Requirements

Login to `ghcr.io` docker repository: https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry#authenticating-to-the-container-registry

## OpenShift Drupal docker image

Contains production Docker images for Drupal.

See:
- [City-of-Helsinki/drupal-helfi-platform/documentation/openshift.md](https://github.com/City-of-Helsinki/drupal-helfi-platform/blob/main/documentation/openshift.md) for documentation on how to use this image in your own project.
- [openshift/drupal/README.md](openshift/drupal/README.md) for developer documentation.

Available PHP versions: `8.3`, `8.4`:

- `ghcr.io/city-of-helsinki/drupal-docker-base:8.3`
- `ghcr.io/city-of-helsinki/drupal-docker-base:8.4`

## Local Drupal docker image

Based on [City-of-Helsinki/drupal-docker-base](/openshift/drupal) with some additions.

See [local/drupal/README.md](local/drupal/README.md) for documentation.

Available PHP versions: `8.3`, `8.4`:

- `ghcr.io/city-of-helsinki/drupal-web:8.3`
- `ghcr.io/city-of-helsinki/drupal-web:8.4`

## OpenShift Drupal repository image

Docker image used to run [City-of-Helsinki/drupal-repository](https://github.com/City-of-Helsinki/drupal-repository).

See [openshift/drupal-repository](openshift/drupal-repository) for more documentation.

## Contact

Slack: #helfi-drupal (http://helsinkicity.slack.com/)
