REPOSITORY = ghcr.io/city-of-helsinki/drupal-repository

ifeq ($(TAG),)
	TAG = latest
endif

.PHONY: push test

default: push

test:
	docker buildx build --pull -t $(REPOSITORY):$(TAG) ./ --load
push:
	docker buildx build --pull --platform linux/arm64,linux/amd64 -t $(REPOSITORY):$(TAG) ./ --push

release: push
