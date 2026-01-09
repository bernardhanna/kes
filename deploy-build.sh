#!/bin/bash

# Force use of nodenv shims (Node 21 + npm) for Plesk deploys
export PATH="/var/www/vhosts/matrix-test.com/.nodenv/shims:$PATH"

# DEBUG: log which node/npm/version Plesk is using (remove later if you like)
echo "Plesk deploy: node path    = $(which node)"
echo "Plesk deploy: node version = $(node -v)"
echo "Plesk deploy: npm path     = $(which npm)"

# One-time dependency install (runs only if .deps_installed does NOT exist)
if [ ! -f .deps_installed ]; then
  # Install PHP deps if Composer is available
  if command -v composer >/dev/null 2>&1; then
    composer install --no-dev --optimize-autoloader
  fi

  # Install Node deps without running postinstall scripts (no Playwright on server)
  if command -v npm >/dev/null 2>&1; then
    npm ci --ignore-scripts
  fi

  # Mark that dependencies have been installed
  touch .deps_installed
fi

# Always build assets on deploy
if command -v npm >/dev/null 2>&1; then
  npm run build
fi
