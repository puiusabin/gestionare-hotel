#!/bin/bash
set -e

# Disable conflicting MPMs at runtime
a2dismod mpm_event || true
a2dismod mpm_worker || true
a2enmod mpm_prefork || true

# Execute the original command (usually apache2-foreground)
exec "$@"
