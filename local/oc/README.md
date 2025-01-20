# OpenShift origin client (oc) image

Available tags:
- `ghcr.io/city-of-helsinki/drupal-oc-cli:latest`

This is used by [local Drupal image](/local/drupal) image.

## Development

### Requirements

- [docker/buildx](https://github.com/docker/buildx) (most likely already included by default)
- Ability to build multi-platform images. See [docker/buildx#building-multi-platform-images](https://github.com/docker/buildx#building-multi-platform-images)

### Release process

- `make release`

### Known issues

The build seems to fail pretty often and you might have to re-run it multiple times.
