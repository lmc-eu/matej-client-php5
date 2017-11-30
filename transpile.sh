#!/usr/bin/env bash
set -e

mkdir tmp-7
mkdir tmp-5

# Clone original PHP 7 repository
git clone git@github.com:lmc-eu/matej-client-php.git tmp-7/

# Transpilation from PHP 7 to PHP 5
vendor/bin/php7to5 convert tmp-7/src/ tmp-5/src
vendor/bin/php7to5 convert tmp-7/tests/ tmp-5/tests

for FN in $(find tmp-5/ -type f -name "*.php"); do
    # Remove class constants visibility, see https://github.com/spatie/7to5/issues/30
    sed -i -- "s/\(protected\|private\|public\) const/const/g" $FN
done

# Copy non-php fixtures from tests
for FN in $(find tmp-7/tests/ -type f -not -name "*.php"); do
    RELATIVE_PATH=$(echo $FN | sed s~tmp-7/~~)
    mkdir -p $(dirname "${RELATIVE_PATH}")
    cp "$FN" "$RELATIVE_PATH";
done

# Replace client name constant
sed -i -- "s/CLIENT_ID = 'php-client'/CLIENT_ID = 'php5-client'/" tmp-5/src/Matej.php

cd tmp-7/
LATEST_COMMIT_MESSAGE=$(git log -1 --pretty=%s)
LATEST_TAG=$(git describe --abbrev=0 --tags)
cd ..

rm -rf tmp-7

# Fix codestyle of the PHP 5 source
vendor/bin/php-cs-fixer fix tmp-5/

# Move contents of tmp-5/ to root directory
(cd tmp-5 && tar c .) | (tar xf -)
rm -rf tmp-5

# Run all tests and checks
composer all

echo Check the git diff now!
echo "Suggested commit message:"
echo "git commit -m \"$LATEST_COMMIT_MESSAGE\""
echo "Suggested tag message:"
echo "git tag $LATEST_TAG HEAD"
