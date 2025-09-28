FROM debian:trixie-slim AS builder

ENV OC_TAG=openshift-clients-4.19.0-202505210330

RUN \
  apt-get update && \
  apt-get install -y \
    rsync \
    git \
    build-essential \
    libkrb5-dev \
    golang; \
  git clone --depth 1 --branch ${OC_TAG} https://github.com/openshift/oc.git /oc-code && \
  cd /oc-code && \
  make oc && install -Dm755 oc -t "/usr/bin" && \
  rm -rf /oc-code /root/go /root/.cache && \
  oc version

FROM debian:trixie-slim

# oc login --web uses xdg-open to open link in a browser and the command will fail.
COPY --chmod=744 xdg-open db-sync /usr/local/bin/

# Install only runtime deps
RUN \
  apt-get update && \
  apt-get install -y --no-install-recommends \
    rsync libkrb5; \
  rm -rf /var/lib/apt/lists/*

# Copy the oc binary from builder
COPY --from=builder /usr/bin/oc /usr/bin/oc

RUN oc version
