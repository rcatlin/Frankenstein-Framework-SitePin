#!/usr/bin/env bash

echo "Copying scripts/pre-commit.sh -> .git/hooks/pre-commit"

cp scripts/pre-commit.sh .git/hooks/pre-commit
chmod 0777 .git/hooks/pre-commit

