function Check-PHP-Version {
    $phpVersion = php -v | Select-String -Pattern "^PHP [0-9]+\.[0-9]+" | ForEach-Object { $_.Matches[0].Value -replace "PHP\s+" }
    $requiredPhpVersion = "8.2"

    if ([version]$phpVersion -lt [version]$requiredPhpVersion) {
        Write-Output "PHP 8.2 is required. Please install PHP 8.2 and try again."
        exit 1
    }
}

function Check-PHP-Extensions {
    $requiredPhpExtensions = @("mbstring", "pdo", "xml", "json", "bcmath", "ctype", "fileinfo", "tokenizer", "pgsql")

    $missingExtensions = @()
    foreach ($extension in $requiredPhpExtensions) {
        if (!(php -m | Select-String -Pattern $extension -Quiet)) {
            $missingExtensions += $extension
        }
    }

    if ($missingExtensions.Count -gt 0) {
        Write-Output "Required PHP extensions are missing: $($missingExtensions -join ', ')"
        exit 1
    }
}

function Check-Node-Npm {
    $nodePath = Get-Command node -ErrorAction SilentlyContinue
    $npmPath = Get-Command npm -ErrorAction SilentlyContinue

    if (-not ($nodePath -and $npmPath)) {
        Write-Output "Node.js and npm are required. Please install Node.js and npm and try again."
        exit 1
    }
}

function Check-Composer {
    $composerPath = Get-Command composer -ErrorAction SilentlyContinue

    if (-not $composerPath) {
        Write-Output "Composer is not installed. Please install Composer and try again."
        exit 1
    }
}


Write-Output @"
..................................................................................................................
..####...##..##..######...####...######..##...##...####...#####...######..##......######..##...##..######..#####..
.##..##..##..##....##....##..##....##....###.###..##..##..##..##....##....##......##......##...##..##......##..##.
.######..##..##....##....######....##....##.#.##..##..##..#####.....##....##......####....##.#.##..####....#####..
.##..##...####.....##....##..##....##....##...##..##..##..##..##....##....##......##......#######..##......##..##.
.##..##....##....######..##..##....##....##...##...####...#####...######..######..######...##.##...######..#####..
..................................................................................................................
Select an option:
1. Fresh Install
2. Upgrade
3. Check Requirements
4. Exit
"@

$choice = Read-Host "Enter your choice"

if ($choice -eq 1) {
    Write-Output "Performing fresh install..."
    Check-PHP-Version
    Check-PHP-Extensions
    Check-Node-Npm
    Check-Composer

    $envVariables = @{
        "APP_ENV" = Read-Host "Enter APP_ENV (default is production)"
        "APP_DEBUG" = Read-Host "Enter APP_DEBUG (default is false)"
        "APP_URL" = Read-Host "Enter APP_URL (default is http://localhost)"
        "DB_HOST" = Read-Host "Enter DB_HOST (default is 127.0.0.1)"
        "DB_PORT" = Read-Host "Enter DB_PORT (default is 5432)"
        "DB_DATABASE" = Read-Host "Enter DB_DATABASE"
        "DB_USERNAME" = Read-Host "Enter DB_USERNAME"
        "DB_PASSWORD" = Read-Host "Enter DB_PASSWORD"
        "WATZAP_API_KEY" = Read-Host "Enter WATZAP_API_KEY"
        "WATZAP_NUMBER_KEY" = Read-Host "Enter WATZAP_NUMBER_KEY"
        "SIMRS_ACCESS_KEY" = Read-Host "Enter SIMRS_ACCESS_KEY"
    }

    # Create or update .env file
    if (Test-Path .\.env) {
        # .env file exists, update it
        $envVariables.GetEnumerator() | ForEach-Object {
            (Get-Content .\.env -Raw) -replace "$($_.Key)=.*", "$($_.Key)=$($_.Value)" | Set-Content .\.env
        }
    } else {
        # .env file does not exist, create it by copying from .env.example
        Copy-Item .\.env.example .\.env
        $envVariables.GetEnumerator() | ForEach-Object {
            Add-Content .\.env "$($_.Key)=$($_.Value)"
        }
    }

    Write-Output "Running composer install..."
    Invoke-Expression "composer install"

    Write-Output "Running npm install..."
    Invoke-Expression "npm install"

    Write-Output "Running npm run build..."
    Invoke-Expression "npm run build"

    Write-Output "Running php artisan key:generate..."
    Invoke-Expression "php artisan key:generate"

    Write-Output "Running php artisan view:clear..."
    Invoke-Expression "php artisan view:clear"

    Write-Output "Running php artisan cache:clear..."
    Invoke-Expression "php artisan cache:clear"

    Write-Output "Running php artisan config:clear..."
    Invoke-Expression "php artisan config:clear"

    Write-Output "Running php artisan route:clear..."
    Invoke-Expression "php artisan route:clear"

    # Ask the user if they want to run migrations
    $runMigrations = Read-Host "Do you want to run fresh migrations and seeding initial data? (yes/no)"
    if ($runMigrations -eq "yes") {
        Write-Output "Running php artisan migrate:fresh --seed..."
        Invoke-Expression "php artisan migrate:fresh --seed"
    }

    Write-Output "Fresh installation completed successfully. Please re-validate the .env file."
} elseif ($choice -eq 2) {
    Write-Output "Performing upgrade..."
    Write-Output "Running composer install..."
    Invoke-Expression "composer install"

    Write-Output "Running npm install..."
    Invoke-Expression "npm install"

    Write-Output "Running npm run build..."
    Invoke-Expression "npm run build"

    Write-Output "Running php artisan key:generate..."
    Invoke-Expression "php artisan key:generate"

    Write-Output "Running php artisan view:clear..."
    Invoke-Expression "php artisan view:clear"

    Write-Output "Running php artisan cache:clear..."
    Invoke-Expression "php artisan cache:clear"

    Write-Output "Running php artisan config:clear..."
    Invoke-Expression "php artisan config:clear"

    Write-Output "Running php artisan route:clear..."
    Invoke-Expression "php artisan route:clear"

    # Ask the user if they want to run migrations
    $runMigrations = Read-Host "Do you want to run migrations? (yes/no)"
    if ($runMigrations -eq "yes") {
        Write-Output "Running php artisan migrate..."
        Invoke-Expression "php artisan migrate"
    }

    Write-Output "Upgrade completed."
} elseif ($choice -eq 3) {
    Write-Output "Checking requirements..."
    Check-PHP-Version
    Check-PHP-Extensions
    Check-Node-Npm
    Check-Composer
    Write-Output "All requirements are met. Ready to proceed."
} elseif ($choice -eq 4) {
    Write-Output "Exiting..."
    exit 0
} else {
    Write-Output "Invalid choice. Please select a valid option."
    exit 1
}
