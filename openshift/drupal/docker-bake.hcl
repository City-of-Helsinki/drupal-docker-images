variable "REPO_BASE" {
  default = "ghcr.io/city-of-helsinki/drupal-docker-base"
}
group "default" {
  targets = ["dev"]
}

group "dev" {
  targets = ["php81-dev", "php82-dev", "php83-dev"]
}

group "prod" {
  targets = ["php81", "php82", "php83"]
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

target "php82-dev" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.2"
    PHP_SHORT_VERSION = "82"
    ALPINE_VERSION = "3.20"
  }
  tags = ["${REPO_BASE}:8.2-dev"]
  platforms = ["linux/amd64"]
}

target "php82" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.2"
    PHP_SHORT_VERSION = "82"
    ALPINE_VERSION = "3.20"
  }
  tags = ["${REPO_BASE}:8.2"]
  platforms = ["linux/amd64"]
}

target "php81-dev" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.1"
    PHP_SHORT_VERSION = "81"
    ALPINE_VERSION = "3.19"
  }
  tags = ["${REPO_BASE}:8.1-dev"]
  platforms = ["linux/amd64"]
}

target "php81" {
  inherits = ["php"]
  args = {
    PHP_VERSION = "8.1"
    PHP_SHORT_VERSION = "81"
    ALPINE_VERSION = "3.19"
  }
  tags = ["${REPO_BASE}:8.1"]
  platforms = ["linux/amd64"]
}
