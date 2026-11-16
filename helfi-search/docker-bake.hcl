variable "PLATFORMS" {
  default = "linux/amd64,linux/arm64"
}

variable "REPO_WEBHOOK" {
  default = "ghcr.io/city-of-helsinki/helfi-search-webhook"
}

variable "REPO_CRAWLER" {
  default = "ghcr.io/city-of-helsinki/helfi-search-elasticsearch-crawler"
}

group "prod" {
  targets = ["webhook", "elasticsearch-crawler"]
}

group "dev" {
  targets = ["webhook-dev", "elasticsearch-crawler-dev"]
}

group "test" {
  targets = ["test-webhook", "test-elasticsearch-crawler"]
}

target "common" {
  platforms = split(",", PLATFORMS)
  labels = {
    "org.opencontainers.image.url" = "https://github.com/City-of-Helsinki/drupal-docker-images"
    "org.opencontainers.image.source" = "https://github.com/City-of-Helsinki/drupal-docker-images"
    "org.opencontainers.image.licenses" = "MIT"
    "org.opencontainers.image.vendor" = "City of Helsinki"
    "org.opencontainers.image.created" = "${timestamp()}"
  }
}

# helfi-search-webhook
target "webhook" {
  inherits = ["common"]
  target = "webhook"
  tags = ["${REPO_WEBHOOK}:latest"]
}

target "webhook-dev" {
  inherits = ["webhook"]
  tags = ["${REPO_WEBHOOK}:dev"]
}

target "test-webhook" {
  inherits = ["common"]
  target = "test-webhook"
  output = ["type=cacheonly"]
}

# helfi-search-elasticsearch-crawler
target "elasticsearch-crawler" {
  inherits = ["common"]
  target = "elasticsearch-crawler"
  tags = ["${REPO_CRAWLER}:latest"]
}

target "elasticsearch-crawler-dev" {
  inherits = ["elasticsearch-crawler"]
  tags = ["${REPO_CRAWLER}:dev"]
}

target "test-elasticsearch-crawler" {
  inherits = ["common"]
  target = "test-elasticsearch-crawler"
  output = ["type=cacheonly"]
}
