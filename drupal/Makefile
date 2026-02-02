PHONY :=

PHONY += help
help:
	@echo -e "See README.md for usage"

PHONY += push-prod
push-prod: push-php push-php-web

PHONY += push-dev
push-dev: push-php-dev push-php-web-dev

PHONY += test
test: test-php test-php-web

###########
# php-dev #
###########
PHONY += push-php-dev
push-php-dev: push-php85-dev push-php84-dev

PHONY += test-php-dev
test-php-dev: test-php85-dev test-php84-dev

PHONY += test-php-web-dev
test-php-web-dev: test-php85-web-dev test-php84-web-dev

PHONY += push-php-web-dev
push-php-web-dev: push-php85-web-dev push-php84-web-dev

#######
# php #
#######
PHONY += test-php
test-php: test-php85 test-php84

PHONY += push-php
push-php: push-php85 push-php84


PHONY += test-php-web
test-php-web: test-php85-web test-php84-web

PHONY += push-php-web
push-php-web: push-php85-web push-php84-web

###########
# 8.5-dev #
###########
PHONY += test-php85-dev
test-php85-dev:
	$(call run_tests,test-php85)

PHONY += push-php85-dev
push-php85-dev: test-php85-dev
	$(call build_image,php85-dev)

PHONY += test-php85-web-dev
test-php85-web-dev:
	$(call run_tests,drupal-web-test-php85)

PHONY += push-php85-web-dev
push-php85-web-dev: test-php85-web-dev
	$(call build_image,web-php85-dev)

#######
# 8.5 #
#######
PHONY += test-php85
test-php85:
	$(call run_tests,test-php85)

PHONY += push-php85
push-php85: test-php85
	$(call build_image,php85)

PHONY += test-php85-web
test-php85-web:
	$(call run_tests,drupal-web-test-php85)

PHONY += push-php85-web
push-php85-web: test-php85-web
	$(call build_image,web-php85)

###########
# 8.4-dev #
###########
PHONY += test-php84-dev
test-php84-dev:
	$(call run_tests,test-php84)

PHONY += push-php84-dev
push-php84-dev: test-php84-dev
	$(call build_image,php84-dev)

PHONY += test-php84-web-dev
test-php84-web-dev:
	$(call run_tests,drupal-web-test-php84)

PHONY += push-php84-web-dev
push-php84-web-dev: test-php84-web-dev
	$(call build_image,web-php84-dev)

#######
# 8.4 #
#######
PHONY += test-php84
test-php84:
	$(call run_tests,test-php84)

PHONY += push-php84
push-php84: test-php84
	$(call build_image,php84)

PHONY += test-php84-web
test-php84-web:
	$(call run_tests,drupal-web-test-php84)

PHONY += push-php84-web
push-php84-web: test-php84-web
	$(call build_image,web-php84)

define run_tests
	docker buildx bake $(1)
endef

define build_image
	docker buildx bake --no-cache --pull --push $(1)
endef

.PHONY: $(PHONY)
