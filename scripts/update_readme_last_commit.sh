#!/usr/bin/env bash
set -euo pipefail
# update_readme_last_commit.sh
# Writes the latest git commit summary into README.md between markers

REPO_ROOT="$(cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd)"
README="$REPO_ROOT/README.md"

if [ ! -d "$REPO_ROOT/.git" ]; then
  echo "This script must be run in a git repository (no .git found)."
  exit 1
fi

cd "$REPO_ROOT"

# Get short commit info: hash - subject (date)
commit_info=$(git log -1 --pretty=format:'%h - %s (%ci)')

if [ -z "$commit_info" ]; then
  echo "No commits found in repository."
  exit 1
fi

# Prepare replacement block. Use perl to replace the section between markers.
replacement="<!-- LAST_COMMIT_START -->\n**Last commit:** \\`$commit_info\\` - (auto-filled)\n<!-- LAST_COMMIT_END -->"

perl -0777 -pe "s/<!-- LAST_COMMIT_START -->.*?<!-- LAST_COMMIT_END -->/$replacement/s" -i "$README"

echo "Updated README with: $commit_info"
