PHONY :=
REPOSITORY = ghcr.io/city-of-helsinki/drupal-web

PHONY += all
all: push-php-dev push-php

PHONY += push-php84-dev
push-php84-dev:
	$(call push_image,8.4-dev,84)

PHONY += push-php84
push-php84:
	$(call push_image,8.4,84)

PHONY += push-php83-dev
push-php83-dev:
	$(call push_image,8.3-dev,83)

PHONY += push-php83
push-php83:
	$(call push_image,8.3,83)

PHONY += push-php
push-php: push-php83 push-php84

PHONY += push-php-dev
push-php-dev: push-php83-dev push-php84-dev

define push_image
	docker buildx build --no-cache --pull --platform linux/arm64,linux/amd64 --target=final -t $(REPOSITORY):$(1) --build-arg PHP_VERSION=$(1) --build-arg PHP_SHORT_VERSION=$(2) ./ --push
endef

.PHONY: $(PHONY)
