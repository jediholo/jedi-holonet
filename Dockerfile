ARG BASE_IMAGE

FROM $BASE_IMAGE

RUN apt-get update && \
    apt-get install -y libxml2-dev && \
    docker-php-ext-install soap && \
    rm -rf /var/lib/apt/lists/*
