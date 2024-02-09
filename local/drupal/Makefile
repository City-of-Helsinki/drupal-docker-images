PHONY :=
REPOSITORY = ghcr.io/city-of-helsinki/drupal-web

PHONY += run-php83-amd64
run-php83-amd64:
	$(call run_image,linux/amd64,php83,8.3,83)

PHONY += run-php82-amd64
run-php82-amd64:
	$(call run_image,linux/amd64,php82,8.2,82)

PHONY += run-php81-amd64
run-php81-amd64:
	$(call run_image,linux/amd64,php81,8.1,81)

PHONY += run-php83-arm64
run-php83-arm64:
	$(call run_image,linux/arm64,php83,8.3,83)

PHONY += run-php82-arm64
run-php82-arm64:
	$(call run_image,linux/arm64,php82,8.2,82)

PHONY += run-php81-arm64
run-php81-arm64:
	$(call run_image,linux/arm64,php81,8.1,81)

PHONY += push-php83
push-php83:
	$(call push_image,php83,8.3,83)

PHONY += push-php82
push-php82:
	$(call push_image,php82,8.2,82)

PHONY += push-php81
push-php81:
	$(call push_image,php81,8.1,81)

PHONY += push-php
push-php: push-php81 push-php82 push-php83

define run_image
	docker buildx build --load --platform $(1) --target=$(2) -t $(REPOSITORY):$(3) --build-arg PHP_VERSION=$(3) --build-arg PHP_SHORT_VERSION=$(4) ./
endef

define push_image
	docker buildx build --pull --platform linux/arm64,linux/amd64 --target=$(1) -t $(REPOSITORY):$(2) --build-arg PHP_VERSION=$(2) --build-arg PHP_SHORT_VERSION=$(3) ./ --push
endef

.PHONY: $(PHONY)
