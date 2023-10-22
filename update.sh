#!/bin/bash

file_path="git_update"
if [ -f "$file_path" ]; then
    rm "$file_path"
    git pull origin main
fi