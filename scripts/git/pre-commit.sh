#!/usr/bin/env bash

echo "Executing .git/hooks/pre-commit..."
ahoy lint && ahoy unit

# Return the status of the last run command.
exit $?
