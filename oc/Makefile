PHONY :=
REPOSITORY = ghcr.io/city-of-helsinki/drupal-oc-cli
TAG := latest

.PHONY: test-amd64 test-arm64 release

test-amd64:
	docker buildx build --load --platform linux/amd64 -t $(REPOSITORY):$(TAG) ./
test-arm64:
	docker buildx build --load --platform linux/arm64 -t $(REPOSITORY):$(TAG) ./
release:
	docker buildx build --platform linux/amd64,linux/arm64 -t $(REPOSITORY):$(TAG) ./ --push

define test_image
	docker buildx build --load --platform $(1) -t $(REPOSITORY):$(2) ./
endef

define push_image
	docker buildx build --platform linux/amd64,linux/arm64 -t $(REPOSITORY):$(1) ./ --push
endef

.PHONY: $(PHONY)
