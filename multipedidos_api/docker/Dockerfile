FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
# Add a non-root user to prevent files being created with root permissions on host machine.
ARG PUSER
ARG PUID
ARG PGID

ENV PUSER ${PUSER}
ENV PUID ${PUID}
ENV PGID ${PGID}

# Install system dependencies
RUN apt-get update -y && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libjpeg-dev \
    libxml2-dev \
    libxslt-dev \
    libpq-dev \
    locales \
    zip \
    unzip

RUN groupadd -g ${PGID} ${PUSER} && \
    useradd -u ${PUID} -g ${PUSER} -m ${PUSER} && \
    apt-get update && \
    apt-get install -y sudo && \
    echo "${PUSER} ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath opcache

RUN sed --in-place '/pt_BR.UTF-8/s/^#//' /etc/locale.gen

ENV LANG pt_BR.UTF-8
ENV LANGUAGE pt_BR:pt:en
ENV LC_ALL pt_BR.UTF-8

RUN locale-gen

RUN echo "America/Sao_Paulo" > /etc/timezone
RUN dpkg-reconfigure -f noninteractive tzdata

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN mkdir -p /root/.composer

USER ${PUSER}

# Set working directory
WORKDIR /var/www

USER ${PUSER}
