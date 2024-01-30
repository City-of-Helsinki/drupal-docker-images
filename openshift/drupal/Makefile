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
build-php-dev: build-php81-dev build-php82-dev build-php83-dev

PHONY += push-php-dev
push-php-dev: push-php81-dev push-php82-dev push-php83-dev

PHONY += test-php-dev
test-php-dev: test-php81-dev test-php82-dev test-php83-dev

#######
# php #
#######
PHONY += test-php
test-php: test-php81 test-php82 test-php83

PHONY += push-php
push-php: push-php81 push-php82 push-php83

PHONY += build-php
build-php: build-php81 build-php82 build-php83

###########
# 8.1-dev #
###########
PHONY += build-php81-dev
build-php81-dev:
	$(call build_image,php81-dev)

PHONY += run-php81-dev
run-php81-dev:
	docker run -it --rm -u 100000 $(REPOSITORY):8.1-dev /bin/sh

PHONY += push-php81-dev
push-php81-dev: test-php81-dev
	docker push $(REPOSITORY):8.1-dev

PHONY += test-php81-dev
test-php81-dev: build-php81-dev
	$(call run_tests,8.1-dev)

#######
# 8.1 #
#######
PHONY += build-php81
build-php81:
	$(call build_image,php81)

PHONY += run-php81
run-php81:
	docker run -it --rm -u 100000 $(REPOSITORY):8.1 /bin/sh

PHONY += push-php81
push-php81: test-php81
	docker push $(REPOSITORY):8.1

PHONY += test-php81
test-php81: build-php81
	$(call run_tests,8.1)

###########
# 8.2-dev #
###########
PHONY += build-php82-dev
build-php82-dev:
	$(call build_image,php82-dev)

PHONY += run-php82-dev
run-php82-dev:
	docker run -it --rm -u 100000 $(REPOSITORY):8.2-dev /bin/sh

PHONY += test-php82-dev
test-php82-dev: build-php82-dev
	$(call run_tests,8.2-dev)

PHONY += push-php82-dev
push-php82-dev: test-php82-dev
	docker push $(REPOSITORY):8.2-dev

#######
# 8.2 #
#######
PHONY += build-php82
build-php82:
	$(call build_image,php82)

PHONY += run-php82
run-php82:
	docker run -it --rm -u 100000 $(REPOSITORY):8.2 /bin/sh

PHONY += test-php82
test-php82: build-php82
	$(call run_tests,8.2)

PHONY += push-php82
push-php82: test-php82
	docker push $(REPOSITORY):8.2

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

define run_tests
	container-structure-test test --image $(REPOSITORY):$(1) --config tests/php$(subst -dev,,$(subst .,,$(1))).yaml
	container-structure-test test --image $(REPOSITORY):$(1) --config tests/php.yaml
	container-structure-test test --image $(REPOSITORY):$(1) --config tests/nginx.yaml
endef

define build_image
	DOCKER_BUILDKIT=1 docker buildx bake $(BUILD_EXTRA_ARGS) --load --pull -f docker-bake.hcl $(1)
endef

.PHONY: $(PHONY)
