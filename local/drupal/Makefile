PHONY :=
REPOSITORY = ghcr.io/city-of-helsinki/drupal-web

PHONY += push-php84-dev
push-php84-dev:
	$(call push_image,php84,8.4-dev,84)

PHONY += push-php84
push-php84:
	$(call push_image,php84,8.4,84)

PHONY += push-php83-dev
push-php83-dev:
	$(call push_image,php83,8.3-dev,83)

PHONY += push-php83
push-php83:
	$(call push_image,php83,8.3,83)

PHONY += push-php
push-php: push-php83 push-php84

PHONY += push-php-dev
push-php-dev: push-php83-dev push-php84-dev

define push_image
	docker buildx build --no-cache --pull --platform linux/arm64,linux/amd64 --target=$(1) -t $(REPOSITORY):$(2) --build-arg PHP_VERSION=$(2) --build-arg PHP_SHORT_VERSION=$(3) ./ --push
endef

.PHONY: $(PHONY)
