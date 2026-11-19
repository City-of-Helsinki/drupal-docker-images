REPOSITORY = ghcr.io/city-of-helsinki/drupal-repository
PLATFORMS ?= linux/arm64,linux/amd64

ifeq ($(TAG),)
	TAG = latest
endif

.PHONY: push test release

default: push

# Builds the image and runs the tests (the Dockerfile's test stage) on every
# platform. Nothing is exported.
test:
	docker buildx build --pull --platform $(PLATFORMS) --target test --output type=cacheonly ./

push: test
	docker buildx build --pull --platform $(PLATFORMS) --target drupal-repository -t $(REPOSITORY):$(TAG) ./ --push

release: push
