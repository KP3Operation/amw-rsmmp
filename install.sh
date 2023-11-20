#!/bin/bash

check_php_version() {
    php_version=$(php -v | grep -oE "^PHP [0-9]+\.[0-9]+" | cut -d' ' -f2)
    required_php_version="8.1"

    if [ "$(echo -e "$php_version\n$required_php_version" | sort -V | head -n1)" != "$required_php_version" ]; then
        echo "PHP 8.1 or above is required. Please install PHP 8.1 or above and try again."
        exit 1
    fi
}

check_php_extensions() {
    required_php_extensions=("mbstring" "pdo" "xml" "json" "bcmath" "ctype" "fileinfo" "tokenizer" "pgsql")

    missing_extensions=()
    for extension in "${required_php_extensions[@]}"; do
        if ! php -m | grep -i -q "$extension"; then
            missing_extensions+=("$extension")
        fi
    done

    if [ ${#missing_extensions[@]} -gt 0 ]; then
        echo "Required PHP extensions are missing: ${missing_extensions[@]}"
        exit 1
    fi
}

check_node_npm() {
    if ! command -v node &> /dev/null || ! command -v npm &> /dev/null; then
        echo "Node.js and npm are required. Please install Node.js and npm and try again."
        exit 1
    fi
}

check_composer() {
    if ! command -v composer &> /dev/null; then
        echo "Composer is not installed. Please install Composer and try again."
        exit 1
    fi
}

echo ".................................................................................................................."
echo "..####...##..##..######...####...######..##...##...####...#####...######..##......######..##...##..######..#####.."
echo ".##..##..##..##....##....##..##....##....###.###..##..##..##..##....##....##......##......##...##..##......##..##."
echo ".######..##..##....##....######....##....##.#.##..##..##..#####.....##....##......####....##.#.##..####....#####.."
echo ".##..##...####.....##....##..##....##....##...##..##..##..##..##....##....##......##......#######..##......##..##."
echo ".##..##....##....######..##..##....##....##...##...####...#####...######..######..######...##.##...######..#####.."
echo ".................................................................................................................."
echo "Select an option:"
echo "1. Fresh Install"
echo "2. Upgrade"
echo "3. Check Requirements"
echo "4. Exit"

read -p "Enter your choice: " choice

if [ "$choice" -eq 1 ]; then
    echo "Performing fresh install..."
    check_php_version
    check_php_extensions
    check_node_npm
    check_composer

    # Ask for environment variables with default values
    read -p "Enter APP_ENV (default is production): " APP_ENV
    APP_ENV=${APP_ENV:-production}

    read -p "Enter APP_DEBUG (default is false): " APP_DEBUG
    APP_DEBUG=${APP_DEBUG:-false}

    read -p "Enter APP_URL (default is http://localhost): " APP_URL
    APP_URL=${APP_URL:-http://localhost}

    read -p "Enter DB_HOST (default is 127.0.0.1): " DB_HOST
    DB_HOST=${DB_HOST:-127.0.0.1}

    read -p "Enter DB_PORT (default is 5432): " DB_PORT
    DB_PORT=${DB_PORT:-5432}

    read -p "Enter DB_DATABASE: " DB_DATABASE

    read -p "Enter DB_USERNAME: " DB_USERNAME

    read -p "Enter DB_PASSWORD: " DB_PASSWORD

    read -p "Enter WATZAP_API_KEY: " WATZAP_API_KEY

    read -p "Enter WATZAP_NUMBER_KEY: " WATZAP_NUMBER_KEY

    read -p "Enter SIMRS_ACCESS_KEY: " SIMRS_ACCESS_KEY

    # Create or update .env file
    if [ -f .env ]; then
        # .env file exists, update it
        sed -i "s/APP_ENV=.*/APP_ENV=$APP_ENV/" .env
        sed -i "s/APP_DEBUG=.*/APP_DEBUG=$APP_DEBUG/" .env
        sed -i "s|APP_URL=.*|APP_URL=$APP_URL|" .env
        sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
        sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
        sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
        sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
        sed -i "s/WATZAP_API_KEY=.*/WATZAP_API_KEY=$WATZAP_API_KEY/" .env
        sed -i "s/WATZAP_NUMBER_KEY=.*/WATZAP_NUMBER_KEY=$WATZAP_NUMBER_KEY/" .env
        sed -i "s/SIMRS_ACCESS_KEY=.*/SIMRS_ACCESS_KEY=$SIMRS_ACCESS_KEY/" .env
    else
        # .env file does not exist, create it by copying from .env.example
        cp .env.example .env
        sed -i "s/APP_ENV=.*/APP_ENV=$APP_ENV/" .env
        sed -i "s/APP_DEBUG=.*/APP_DEBUG=$APP_DEBUG/" .env
        sed -i "s|APP_URL=.*|APP_URL=$APP_URL|" .env
        sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
        sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
        sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
        sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
        sed -i "s/WATZAP_API_KEY=.*/WATZAP_API_KEY=$WATZAP_API_KEY/" .env
        sed -i "s/WATZAP_NUMBER_KEY=.*/WATZAP_NUMBER_KEY=$WATZAP_NUMBER_KEY/" .env
        sed -i "s/SIMRS_ACCESS_KEY=.*/SIMRS_ACCESS_KEY=$SIMRS_ACCESS_KEY/" .env
    fi

    # Run composer install
    echo "Running composer install..."
    composer install

    # Run npm install
    echo "Running npm install..."
    npm install

    # Run npm run build
    echo "Running npm run build..."
    npm run build

    # Additional commands after npm run build
    echo "Running php artisan key:generate..."
    php artisan key:generate

    echo "Running php artisan view:clear..."
    php artisan view:clear

    echo "Running php artisan cache:clear..."
    php artisan cache:clear

    echo "Running php artisan config:clear..."
    php artisan config:clear

    echo "Running php artisan route:clear..."
    php artisan route:clear

    # Ask the user if they want to run migrations
    read -p "Do you want to run fresh migrations and seeding initial data? (yes/no): " run_migrations
    if [ "$run_migrations" = "yes" ]; then
        echo "Running php artisan migrate:fresh --seed..."
        php artisan migrate:fresh --seed
    fi

    echo "Fresh installation completed successfully. Please re-validate the .env file."
elif [ "$choice" -eq 2 ]; then
    echo "Performing upgrade..."
    # Run composer install
    echo "Running composer install..."
    composer install

    # Run npm install
    echo "Running npm install..."
    npm install

    # Run npm run build
    echo "Running npm run build..."
    npm run build

    # Additional commands after npm run build
    echo "Running php artisan key:generate..."
    php artisan key:generate

    echo "Running php artisan view:clear..."
    php artisan view:clear

    echo "Running php artisan cache:clear..."
    php artisan cache:clear

    echo "Running php artisan config:clear..."
    php artisan config:clear

    echo "Running php artisan route:clear..."
    php artisan route:clear

    # Ask the user if they want to run migrations
    read -p "Do you want to run migrations? (yes/no): " run_migrations
    if [ "$run_migrations" = "yes" ]; then
      echo "Running php artisan migrate..."
      php artisan migrate
    fi

    echo "Upgrade completed."
elif [ "$choice" -eq 3 ]; then
    echo "Checking requirements..."
    check_php_version
    check_php_extensions
    check_node_npm
    check_composer
    echo "All requirements are met. Ready to proceed."
elif [ "$choice" -eq 4 ]; then
    echo "Exiting..."
    exit 0
else
    echo "Invalid choice. Please select a valid option."
    exit 1
fi
