PHONY :=

# Images are built for every platform in PLATFORMS, with a dedicated buildx
# builder (BUILDER) that can build all of them. `make builder` creates it.
BUILDER ?= helfi-search
PLATFORMS ?= linux/amd64,linux/arm64
export PLATFORMS

PHONY += help
help:
	@echo -e "See README.md for usage"

# Creates the multi-platform builder if it doesn't exist, and checks that it
# can build every platform in PLATFORMS. Platforms other than this machine's
# need QEMU emulation, see README.md.
PHONY += builder
builder:
	@docker buildx inspect $(BUILDER) >/dev/null 2>&1 || \
		docker buildx create --name $(BUILDER) --driver docker-container --platform $(PLATFORMS)
	@supported="$$(docker buildx inspect --bootstrap $(BUILDER) | sed -n 's/^Platforms: *//p' | tr -d ' ')"; \
	missing=""; \
	for platform in $$(echo "$(PLATFORMS)" | tr ',' ' '); do \
		echo ",$$supported," | grep -q ",$$platform[,*]" || missing="$$missing $$platform"; \
	done; \
	if [ -n "$$missing" ]; then \
		echo "Builder $(BUILDER) can't build:$$missing. Install QEMU emulation:"; \
		echo "  docker run --privileged --rm tonistiigi/binfmt --install all"; \
		echo "then remove the builder (docker buildx rm $(BUILDER)) and run make builder again."; \
		exit 1; \
	fi; \
	echo "Builder $(BUILDER) builds: $(PLATFORMS)"

PHONY += test
test: builder
	$(call run_tests,test)

# The latest tags, used by the project's OpenShift images.
PHONY += push-prod
push-prod: test
	$(call build_image,prod)

# The dev tags, used for local development and for testing changes before a
# production release.
PHONY += push-dev
push-dev: test
	$(call build_image,dev)

# Build the dev images into the local Docker, for trying changes in the project
# before they're published. Only for this machine's platform, with Docker's own
# builder, which writes the images straight into the local Docker instead of
# importing them from a separate builder. PLATFORM must match this machine,
# e.g. linux/arm64 on Apple silicon.
PLATFORM ?= linux/amd64

PHONY += load
load:
	docker buildx bake --builder default --set '*.platform=$(PLATFORM)' dev

define run_tests
	docker buildx bake --builder $(BUILDER) $(1)
endef

define build_image
	docker buildx bake --builder $(BUILDER) --no-cache --pull --push $(1)
endef

.PHONY: $(PHONY)
