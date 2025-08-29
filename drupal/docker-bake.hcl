variable "REPO_BASE" {
  default = "ghcr.io/city-of-helsinki/drupal-docker-base"
}

variable "REPO_WEB" {
  default = "ghcr.io/city-of-helsinki/drupal-web"
}

group "default" {
  targets = ["dev"]
}

group "dev" {
  targets = ["php83-dev", "php84-dev"]
}

group "prod" {
  targets = ["php83", "php84"]
}

target "php" {
  target = "final"
  args = {
    TEST_TARGET = "final"
    ALPINE_VERSION = "3.22"
  }
  platforms = ["linux/amd64", "linux/arm64"]
  labels = {
    "org.opencontainers.image.url" = "https://github.com/City-of-Helsinki/drupal-docker-images"
    "org.opencontainers.image.source" = "https://github.com/City-of-Helsinki/drupal-docker-images"
    "org.opencontainers.image.licenses" = "MIT"
    "org.opencontainers.image.vendor" = "City of Helsinki"
    "org.opencontainers.image.created" = "${timestamp()}"
  }
}

target "test" {
  target = "test"
  output = ["type=cacheonly"]
}

target "php83-dev" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.3"
    PHP_SHORT_VERSION = "83"
    ALPINE_VERSION = "3.22"
  }
  tags = ["${REPO_BASE}:8.3-dev"]
}

target "php83" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.3"
    PHP_SHORT_VERSION = "83"
    ALPINE_VERSION = "3.22"
  }
  tags = ["${REPO_BASE}:8.3"]
}

target "php84-dev" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.4"
    PHP_SHORT_VERSION = "84"
    ALPINE_VERSION = "3.20"
  }
  tags = ["${REPO_BASE}:8.4-dev"]
}

target "php84" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.4"
    PHP_SHORT_VERSION = "84"
    ALPINE_VERSION = "3.22"
  }
  tags = ["${REPO_BASE}:8.4"]
}

target "drupal-web" {
  platforms = ["linux/amd64", "linux/arm64"]
  labels = {
    "org.opencontainers.image.url" = "https://github.com/City-of-Helsinki/drupal-docker-images"
    "org.opencontainers.image.source" = "https://github.com/City-of-Helsinki/drupal-docker-images"
    "org.opencontainers.image.licenses" = "MIT"
    "org.opencontainers.image.vendor" = "City of Helsinki"
    "org.opencontainers.image.created" = "${timestamp()}"
  }
  args = {
    TEST_TARGET = "drupal-web-final"
  }
  target = "drupal-web-final"
}

target "web-php83-dev" {
  inherits = ["drupal-web"]
  args = {
    PHP_VERSION = "8.3"
    PHP_SHORT_VERSION = "83"
  }
  tags = ["${REPO_WEB}:8.3-dev"]
}

target "web-php83" {
  inherits = ["drupal-web"]
  args = {
    PHP_VERSION = "8.3"
    PHP_SHORT_VERSION = "83"
  }
  tags = ["${REPO_WEB}:8.3"]
}

target "web-php84-dev" {
  inherits = ["drupal-web"]
  args = {
    PHP_VERSION = "8.4"
    PHP_SHORT_VERSION = "84"
  }
  tags = ["${REPO_WEB}:8.4-dev"]
}

target "web-php84" {
  inherits = ["drupal-web"]
  args = {
    PHP_VERSION = "8.4"
    PHP_SHORT_VERSION = "84"
  }
  tags = ["${REPO_WEB}:8.4"]
}
