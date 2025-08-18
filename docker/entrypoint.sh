#!/bin/dash

# Exit immediately if a command returns a non-zero status
set -e

# Check if the environment variables file exists
if [ ! -f .env ]
then
	cp .env.example .env
fi

# Run migration
php bin/console migrate

# Load data
php bin/console load

# Run the default command
exec "$@"
