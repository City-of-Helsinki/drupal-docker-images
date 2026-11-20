REPOSITORY = ghcr.io/city-of-helsinki/drupal-repository
PLATFORMS ?= linux/arm64,linux/amd64

ifeq ($(TAG),)
	TAG = latest
endif

.PHONY: build push test release

default: push

build:
	docker buildx build --no-cache --pull --platform $(PLATFORMS) --target drupal-repository --output type=cacheonly ./

test:
	docker buildx build --pull --no-cache-filter test --platform $(PLATFORMS) --target test --output type=cacheonly ./

push: build test
	docker buildx build --platform $(PLATFORMS) --target drupal-repository -t $(REPOSITORY):$(TAG) ./ --push

release: push
