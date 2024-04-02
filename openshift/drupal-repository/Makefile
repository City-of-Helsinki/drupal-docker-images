REPOSITORY = ghcr.io/city-of-helsinki/drupal-repository

ifeq ($(TAG),)
	TAG = latest
endif

.PHONY: push release

default: push

push:
	docker buildx build --pull --platform linux/arm64,linux/amd64 -t $(REPOSITORY):$(TAG) ./ --push

release: build push
