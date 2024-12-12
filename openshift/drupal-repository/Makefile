REPOSITORY = ghcr.io/city-of-helsinki/drupal-repository

ifeq ($(TAG),)
	TAG = latest
endif

.PHONY: push release

default: push

test-amd64:
	docker buildx build --pull --platform linux/amd64 -t $(REPOSITORY):$(TAG) ./ --load
push:
	docker buildx build --pull --platform linux/arm64,linux/amd64 -t $(REPOSITORY):$(TAG) ./ --push

release: build push
