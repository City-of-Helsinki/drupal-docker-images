variable "REPO_BASE" {
  default = "ghcr.io/city-of-helsinki/drupal-docker-base"
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
    ALPINE_VERSION = "3.20"
  }
}

target "php83-dev" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.3"
    PHP_SHORT_VERSION = "83"
    ALPINE_VERSION = "3.20"
  }
  tags = ["${REPO_BASE}:8.3-dev"]
  platforms = ["linux/amd64"]
}

target "php83" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.3"
    PHP_SHORT_VERSION = "83"
    ALPINE_VERSION = "3.20"
  }
  tags = ["${REPO_BASE}:8.3"]
  platforms = ["linux/amd64"]
}

target "php84-dev" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.4"
    PHP_SHORT_VERSION = "84"
    ALPINE_VERSION = "3.20"
  }
  tags = ["${REPO_BASE}:8.4-dev"]
  platforms = ["linux/amd64"]
}

target "php84" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.4"
    PHP_SHORT_VERSION = "84"
    ALPINE_VERSION = "3.20"
  }
  tags = ["${REPO_BASE}:8.4"]
  platforms = ["linux/amd64"]
}

