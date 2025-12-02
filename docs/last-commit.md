# Last Commit Auto-fill

This small utility keeps the `README.md` Last commit line up to date.

Usage:

1. From the repository root run (bash):

```bash
./scripts/update_readme_last_commit.sh
```

2. The script will update the block in `README.md` between the markers:

```
<!-- LAST_COMMIT_START -->
**Last commit:** `...`
<!-- LAST_COMMIT_END -->
```

This is a convenience for keeping the README in-sync with the repository's
latest commit. You can run it from CI or as part of a release step.
