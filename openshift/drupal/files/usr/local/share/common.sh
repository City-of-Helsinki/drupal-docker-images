#!/bin/sh

source_all_files_in_dir() {
  dir="$1"
  for i in "$dir"/*; do
    if [ -r $i ]; then
      echo "# Source $i"
      . $i
    else
      echo "! $i not sourced"
    fi
  done
  unset i
  unset dir
}
