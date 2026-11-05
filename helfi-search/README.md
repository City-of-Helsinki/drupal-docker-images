# helfi-search images

Base images for [City-of-Helsinki/helfi-search](https://github.com/City-of-Helsinki/helfi-search):

- `ghcr.io/city-of-helsinki/helfi-search-webhook`: the webhook, on
  [FrankenPHP](https://frankenphp.dev).
- `ghcr.io/city-of-helsinki/helfi-search-elasticsearch-crawler`: the worker
  and the full crawl, [Elastic Open Crawler](https://github.com/elastic/crawler)
  with PHP.

Tags:

- `latest`: used by the project's OpenShift images.
- `dev`: used for local development, and for testing changes before release.

Both use PHP 8.4 and run as a non-root user, as OpenShift requires. See the
[Dockerfile](Dockerfile) for details.

## Development

Make sure you're logged in to `ghcr.io`. See [Makefile](Makefile) for
up-to-date build commands.

```bash
# Run the tests
make test
# Build, test and push the dev images
make push-dev
# Build, test and push the latest images
make push-prod
# Build the dev images into the local Docker, to try them before pushing
make load
```

Images are built for `linux/amd64` and `linux/arm64`. Building for a platform
other than your machine's needs QEMU emulation. `make builder` sets up the
builder and tells you if emulation is missing:

```bash
docker run --privileged --rm tonistiigi/binfmt --install all
```

## Release process

Always build the `dev` images first, and build the `latest` images only after
`dev` has been tested:

1. Build and push the `dev` images: `make push-dev`, or in GitHub Actions:
   `Actions` -> `helfi-search-build.yml` -> "Run workflow" -> `dev`.
2. Test them: locally in helfi-search (`docker compose build --pull`), and in
   the testing and staging environments, which build helfi-search on the
   `dev` images (build argument `DOCKER_BUILD_TAG=dev`).
3. Build and push the `latest` images: `make push-prod`, or the same workflow
   with `prod`.

## Upstream images

The images always use the latest upstream versions: FrankenPHP
(`dunglas/frankenphp:php8.4-alpine`) and Elastic Open Crawler
(`docker.elastic.co/integrations/crawler:latest`). Every build picks up new
releases, so check the crawler's
[changelog](https://github.com/elastic/crawler/blob/main/docs/CHANGELOG.md)
when testing: helfi-search depends on its configuration format and output.
