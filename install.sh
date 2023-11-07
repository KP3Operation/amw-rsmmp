#!/bin/sh

# Check if php installed
if command -v php &> /dev/null; then
    echo "PHP is installed"
else
    echo "PHP nis not installed"
fi


# Check if composer installed
if command -v composer &> /dev/null; then
    echo "composer is installed"
else
    echo "composer is not installed"
fi

# Check if required PHP extensions are enabled
required_exts=("ctype" "curl" "dom" "fileinfo" "filter" "hash" "mbstring" "openssl" "pdo_pgsql" "pgsql" "xml" "session" "PDO" "tokenizer")

for ext in "${required_exts[@]}"; do
    if ! php -m | grep -i -wq "$ext"; then
        echo "PHP extension $ext is not enabled"
        exit 1
    fi
done

# Check if node installed
if command -v node &> /dev/null; then
    echo "node is installed"
else
    echo "node is not installed"
fi


