#
# KeyDB Dockerfile for x86_64
#
#
# Pull base image.
FROM ubuntu:18.04

# add our user and group first to make sure their IDs get assigned consistently, regardless of whatever dependencies get added
RUN groupadd -r keydb && useradd -r -g keydb keydb

# grab gosu for easy step-down from root
# https://github.com/tianon/gosu/releases
ENV GOSU_VERSION 1.10
RUN set -ex; \
        \
        fetchDeps=" \
                ca-certificates \
                dirmngr \
                gnupg \
                wget \
        "; \
        apt-get update; \
        apt-get install -y --no-install-recommends libcurl4-openssl-dev $fetchDeps; \
        rm -rf /var/lib/apt/lists/*; \
        \
        dpkgArch="$(dpkg --print-architecture | awk -F- '{ print $NF }')"; \
        wget -O /usr/local/bin/gosu "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$dpkgArch"; \
        wget -O /usr/local/bin/gosu.asc "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$dpkgArch.asc"; \
        export GNUPGHOME="$(mktemp -d)"; \
        gpg --batch --keyserver hkps://keys.openpgp.org --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4; \
        gpg --batch --verify /usr/local/bin/gosu.asc /usr/local/bin/gosu; \
        gpgconf --kill all; \
        rm -r "$GNUPGHOME" /usr/local/bin/gosu.asc; \
        chmod +x /usr/local/bin/gosu; \
        gosu nobody true; \
        \
        apt-get purge -y --auto-remove $fetchDeps


# Load binaries to image. Much smaller size than building.
ADD ./app/* /usr/local/bin/

RUN \
  cd /usr/local/bin && \
  mkdir -p /etc/keydb && \
  mv -f *.conf /etc/keydb && \
# update config file for use in container
  sed -i 's/^\(bind .*\)$/# \1/' /etc/keydb/keydb.conf && \
  sed -i 's/^\(daemonize .*\)$/# \1/' /etc/keydb/keydb.conf && \
  sed -i 's/^\(dir .*\)$/# \1\ndir \/data/' /etc/keydb/keydb.conf && \
  sed -i 's/^\(logfile .*\)$/# \1/' /etc/keydb/keydb.conf && \
  sed -i 's/protected-mode yes/protected-mode no/g' /etc/keydb/keydb.conf

# Define default command.
#CMD ["keydb-server", "/etc/keydb/redis.conf"]

RUN \
  mkdir /data && chown keydb:keydb /data && \
  mkdir /flash && chown keydb:keydb /flash

VOLUME /data
WORKDIR /data
ENV KEYDB_PRO_DIRECTORY=/usr/local/bin/

#COPY docker-entrypoint.sh /usr/local/bin/
ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 6379
CMD ["keydb-server", "/etc/keydb/keydb.conf"]
