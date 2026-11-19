# OpenShift Drupal repository

This image is used to run [City-of-Helsinki/drupal-repository](https://github.com/City-of-Helsinki/drupal-repository) project.

## Development

Run the tests: `make test`. The tests are PHPUnit tests in [tests](tests),
run inside the Dockerfile's `test` stage for every platform.

Test and push the image: `make push` (`make push TAG=dev` for another tag).
