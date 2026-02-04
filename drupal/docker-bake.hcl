variable "REPO_BASE" {
  default = "ghcr.io/city-of-helsinki/drupal-docker-base"
}

variable "REPO_WEB" {
  default = "ghcr.io/city-of-helsinki/drupal-web"
}

group "php-dev" {
  targets = ["php85-dev", "php84-dev"]
}

group "php-prod" {
  targets = ["php85", "php84"]
}

group "drupal-web-prod" {
  targets = ["web-php85", "web-php84"]
}

group "drupal-web-dev" {
  targets = ["web-php85-dev", "web-php84-dev"]
}

target "php" {
  target = "final-php"
  platforms = ["linux/amd64", "linux/arm64"]
  args = {
    TEST_TARGET = "php"
  }
  labels = {
    "org.opencontainers.image.url" = "https://github.com/City-of-Helsinki/drupal-docker-images"
    "org.opencontainers.image.source" = "https://github.com/City-of-Helsinki/drupal-docker-images"
    "org.opencontainers.image.licenses" = "MIT"
    "org.opencontainers.image.vendor" = "City of Helsinki"
    "org.opencontainers.image.created" = "${timestamp()}"
  }
}

target "test" {
  output = ["type=cacheonly"]
  target = "test"
}

target "php84-common" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.4"
    PHP_SHORT_VERSION = "84"
  }
}

target "php85-common" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.5"
    PHP_SHORT_VERSION = "85"
  }
}

target "php84-dev" {
  inherits = ["php84-common"]
  tags = ["${REPO_BASE}:8.4-dev", "${REPO_BASE}:latest-dev"]
}

target "php84" {
  inherits = ["php84-common"]
  tags = ["${REPO_BASE}:8.4", "${REPO_BASE}:latest"]
}

target "test-php84" {
  inherits = ["php84", "test"]
  targets = ["test"]
}

target "php85-dev" {
  inherits = ["php85-common"]
  tags = ["${REPO_BASE}:8.5-dev"]
}

target "php85" {
  inherits = ["php85-common"]
  tags = ["${REPO_BASE}:8.5"]
}

target "test-php85" {
  inherits = ["php85", "test"]
  targets = ["test"]
}

# Drupal web images (drupal-web)
target "drupal-web" {
  args = {
    TEST_TARGET = "drupal-web"
  }
  target = "final-drupal-web"
}

target "web-php84" {
  inherits = ["php84-common", "drupal-web"]
  tags = ["${REPO_WEB}:8.4", "${REPO_WEB}:latest"]
}

target "web-php84-dev" {
  inherits = ["web-php84"]
  tags = ["${REPO_WEB}:8.4-dev", "${REPO_WEB}:latest-dev"]
}


target "web-php85" {
  inherits = ["php85-common", "drupal-web"]
  tags = ["${REPO_WEB}:8.5"]
}

target "web-php85-dev" {
  inherits = ["web-php85"]
  tags = ["${REPO_WEB}:8.5-dev"]
}

# Drupal web image tests
target "drupal-web-test-php85" {
  inherits = ["web-php85", "test"]
  targets = ["test"]
}

target "drupal-web-test-php84" {
  inherits = ["web-php84", "test"]
  targets = ["test"]
}

