FROM alpine:3.20

RUN apk add --no-cache \
    php82 php82-cli php82-openssl php82-json php82-pdo php82-pdo_sqlite php82-sqlite3 \
    iputils bind-tools busybox-extras \
 && ln -sf /usr/bin/php82 /usr/bin/php

WORKDIR /srv/app
COPY app/ /srv/app/app/
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8081
ENTRYPOINT ["/entrypoint.sh"]
