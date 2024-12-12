PHONY :=
BUILD_EXTRA_ARGS :=
REPOSITORY = ghcr.io/city-of-helsinki/drupal-docker-base

PHONY += help
help:
	@echo -e "See README.md for usage"

###########
# php-dev #
###########
PHONY += push-php-dev
push-php-dev: push-php83-dev push-php84-dev

PHONY += test-php-dev
test-php-dev: test-php83-dev test-php84-dev

#######
# php #
#######
PHONY += test-php
test-php: test-php83 test-php84

PHONY += push-php
push-php: push-php83 push-php84

###########
# 8.3-dev #
###########
PHONY += test-php83-dev
test-php83-dev:
	$(call run_tests,php83-dev)

PHONY += push-php83-dev
push-php83-dev:
	$(call build_image,php83-dev)

#######
# 8.3 #
#######
PHONY += test-php83
test-php83:
	$(call run_tests,php83)

PHONY += push-php83
push-php83: test-php83
	$(call build_image,php83)

###########
# 8.4-dev #
###########
PHONY += test-php84-dev
test-php84-dev:
	$(call run_tests,php84-dev)

PHONY += push-php84-dev
push-php84-dev: test-php84-dev
	$(call build_image,php84-dev)

#######
# 8.4 #
#######
PHONY += test-php84
test-php84:
	$(call run_tests,php84)

PHONY += push-php84
push-php84: test-php84
	$(call build_image,php84)

define run_tests
	docker buildx bake test $(1) test
endef

define build_image
	docker buildx bake $(BUILD_EXTRA_ARGS) --no-cache --pull --push -f docker-bake.hcl $(1)
endef

.PHONY: $(PHONY)
