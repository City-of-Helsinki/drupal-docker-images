FROM alpine:3.22

ENV OC_TAG=openshift-clients-4.17.0-202409111134

# oc login --web uses xdg-open to open link in a browser and the command will fail.
COPY --chmod=744 xdg-open db-sync /usr/local/bin/

RUN \
  apk add --no-cache rsync; \
  apk add --no-cache --virtual .build-deps \
    git \
    gpgme-dev \
    libassuan-dev \
    build-base \
    linux-headers \
    krb5-dev \
    go; \
  git clone --branch ${OC_TAG} https://github.com/openshift/oc.git /oc-code; \
  cd /oc-code; \
  make oc && install -Dm755 oc -t "/usr/bin"; \
  rm -rf /oc-code /root/go /root/.cache; \
  apk del .build-deps ; \
  oc version
