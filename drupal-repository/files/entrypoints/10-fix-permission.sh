#!/bin/sh

# Make sure $PROJECT_DIR is marked as safe directory on local.
if [ -d "$PROJECT_DIR/.git" ]; then
  git config --global --add safe.directory $PROJECT_DIR
fi
