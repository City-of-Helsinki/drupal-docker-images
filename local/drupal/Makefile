PHONY :=
REPOSITORY = ghcr.io/city-of-helsinki/drupal-web

PHONY += run-php84-amd64
run-php84-amd64:
	$(call run_image,linux/amd64,php84,8.4,84)

PHONY += run-php83-amd64
run-php83-amd64:
	$(call run_image,linux/amd64,php83,8.3,83)

PHONY += run-php84-arm64
run-php84-arm64:
	$(call run_image,linux/arm64,php84,8.4,84)

PHONY += run-php83-arm64
run-php83-arm64:
	$(call run_image,linux/arm64,php83,8.3,83)

PHONY += push-php84
push-php84:
	$(call push_image,php84,8.4,84)

PHONY += push-php83
push-php83:
	$(call push_image,php83,8.3,83)

PHONY += push-php
push-php: push-php83 push-php84

define run_image
	docker buildx build --load --platform $(1) --target=$(2) -t $(REPOSITORY):$(3) --build-arg PHP_VERSION=$(3) --build-arg PHP_SHORT_VERSION=$(4) ./
endef

define push_image
	docker buildx build --pull --platform linux/arm64,linux/amd64 --target=$(1) -t $(REPOSITORY):$(2) --build-arg PHP_VERSION=$(2) --build-arg PHP_SHORT_VERSION=$(3) ./ --push
endef

.PHONY: $(PHONY)
