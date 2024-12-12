# Local Drupal docker image

Based on [City-of-Helsinki/drupal-docker-base](/openshift/drupal) image with a few additions:

- [OpenShift origin client (OC)](/local/oc)
- Xdebug

Available PHP versions: `8.3`, `8.4`:

- `ghcr.io/city-of-helsinki/drupal-web:8.3`
- `ghcr.io/city-of-helsinki/drupal-web:8.4`

## Development

### Requirements

1. You must be logged in to `ghcr.io` Docker repository: https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry#authenticating-to-the-container-registry
2. [docker/buildx](https://github.com/docker/buildx) (most likely already included by default)
3. Ability to build multi-platform images. You can check if you have multiplatform BuildKit enabled by calling `docker buildx ls`.

It should output something like (see `linux/amd64` and `linux/arm64`):

```bash
# docker buildx ls
NAME/NODE         DRIVER/ENDPOINT             STATUS   BUILDKIT PLATFORMS
fervent_wright *  docker-container
  fervent_wright0 unix:///var/run/docker.sock running  v0.11.5  linux/amd64, linux/amd64/v2, linux/amd64/v3, linux/386, linux/arm64, linux/riscv64, linux/ppc64le, linux/s390x, linux/mips64le, linux/mips64
```

If not, call `docker buildx create --use` and `docker run --privileged --rm tonistiigi/binfmt --install all`

See [docker/buildx#building-multi-platform-images](https://github.com/docker/buildx#building-multi-platform-images) for more documentation about building multi-platform images.

### Building

To run image locally:

- `8.1` tag (on `amd64` arch): `make run-php81-amd64`
- `8.1` tag (on `arm64` arch): `make run-php81-arm64`

To release a specific tag:

- Release `8.1` tag: `make push-php81`

To release all tags at once: `make push-php`.
