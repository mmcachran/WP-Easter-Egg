#!/usr/bin/env bash
set -euo pipefail

# Docker-based test runner for WP Easter Egg
# Tests across multiple PHP and WordPress versions

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

# Default values
PHP_VERSION="${PHP_VERSION:-8.2}"
WORDPRESS_VERSION="${WORDPRESS_VERSION:-6.5}"
SERVICE_NAME="wp-${WORDPRESS_VERSION}-php${PHP_VERSION//./}"

echo "=========================================="
echo "WP Easter Egg - Docker Test Suite"
echo "=========================================="
echo "PHP Version: ${PHP_VERSION}"
echo "WordPress Version: ${WORDPRESS_VERSION}"
echo "Service: ${SERVICE_NAME}"
echo "=========================================="

# Ensure docker-compose is available
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo "ERROR: docker-compose is not installed"
    exit 1
fi

DOCKER_COMPOSE_CMD="docker-compose"
if ! command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE_CMD="docker compose"
fi

# Start the database and WordPress container
echo "Starting services..."
cd "${PROJECT_DIR}"
${DOCKER_COMPOSE_CMD} up -d db "${SERVICE_NAME}"

# Wait for WordPress to be ready
echo "Waiting for WordPress to be ready..."
for i in $(seq 1 30); do
    if curl -sf "http://localhost:8082/" > /dev/null 2>&1; then
        echo "WordPress is ready!"
        break
    fi
    echo "Attempt ${i}/30: WordPress not ready yet, waiting 5s..."
    sleep 5
done

# Check if WordPress is actually running
if ! curl -sf "http://localhost:8082/" > /dev/null 2>&1; then
    echo "ERROR: WordPress failed to start within timeout"
    ${DOCKER_COMPOSE_CMD} logs "${SERVICE_NAME}"
    exit 1
fi

# Run PHPUnit tests inside the container
echo "Running PHPUnit tests..."
${DOCKER_COMPOSE_CMD} exec -T "${SERVICE_NAME}" \
    bash -c "cd /var/www/html/wp-content/plugins/wp-easter-egg && composer install --no-interaction --prefer-dist && vendor/bin/phpunit"

TEST_EXIT_CODE=$?

# Stop services
echo "Stopping services..."
${DOCKER_COMPOSE_CMD} down

# Exit with test result
exit ${TEST_EXIT_CODE}
