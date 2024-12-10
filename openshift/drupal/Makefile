PHONY :=
BUILD_EXTRA_ARGS :=
REPOSITORY = ghcr.io/city-of-helsinki/drupal-docker-base

PHONY += help
help:
	@echo -e "See README.md for usage"

###########
# php-dev #
###########
PHONY += build-php-dev
build-php-dev: build-php83-dev build-php84-dev

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

PHONY += build-php
build-php: build-php83 build-php84

###########
# 8.3-dev #
###########
PHONY += build-php83-dev
build-php83-dev:
	$(call build_image,php83-dev)

PHONY += run-php83-dev
run-php83-dev:
	docker run -it --rm -u 100000 $(REPOSITORY):8.3-dev /bin/sh

PHONY += test-php83-dev
test-php83-dev: build-php83-dev
	$(call run_tests,8.3-dev)

PHONY += push-php83-dev
push-php83-dev: test-php83-dev
	docker push $(REPOSITORY):8.3-dev

#######
# 8.3 #
#######
PHONY += build-php83
build-php83:
	$(call build_image,php83)

PHONY += run-php83
run-php83:
	docker run -it --rm -u 100000 $(REPOSITORY):8.3 /bin/sh

PHONY += test-php83
test-php83: build-php83
	$(call run_tests,8.3)

PHONY += push-php83
push-php83: test-php83
	docker push $(REPOSITORY):8.3

###########
# 8.4-dev #
###########
PHONY += build-php84-dev
build-php84-dev:
	$(call build_image,php84-dev)

PHONY += run-php84-dev
run-php84-dev:
	docker run -it --rm -u 100000 $(REPOSITORY):8.4-dev /bin/sh

PHONY += test-php84-dev
test-php84-dev: build-php84-dev
	$(call run_tests,8.4-dev)

PHONY += push-php84-dev
push-php84-dev: test-php84-dev
	docker push $(REPOSITORY):8.4-dev

#######
# 8.4 #
#######
PHONY += build-php84
build-php84:
	$(call build_image,php84)

PHONY += run-php84
run-php84:
	docker run -it --rm -u 100000 $(REPOSITORY):8.4 /bin/sh

PHONY += test-php84
test-php84: build-php84
	$(call run_tests,8.4)

PHONY += push-php84
push-php84: test-php84
	docker push $(REPOSITORY):8.4

define run_tests
	container-structure-test test --image $(REPOSITORY):$(1) --config tests/php$(subst -dev,,$(subst .,,$(1))).yaml
	container-structure-test test --image $(REPOSITORY):$(1) --config tests/php.yaml
	container-structure-test test --image $(REPOSITORY):$(1) --config tests/nginx.yaml
endef

define build_image
	DOCKER_BUILDKIT=1 docker buildx bake $(BUILD_EXTRA_ARGS) --load --pull -f docker-bake.hcl $(1)
endef

.PHONY: $(PHONY)
