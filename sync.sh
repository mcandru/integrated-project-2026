#!/bin/bash
echo

# Check if upstream remote already exists
if ! git remote get-url upstream > /dev/null 2>&1; then
    echo "Setting up connection to the template repo for the first time..."
    git remote add upstream https://github.com/mcandru/integrated-project-2026.git
    echo "Done."
    echo
fi

echo "Fetching latest updates from the template repo..."
git fetch upstream
echo

echo "Updating docs and examples folders..."
git checkout upstream/master -- docs/ src/examples/
echo

echo "All done! Your docs and examples folders have been updated."
echo "You can commit this change with:"
echo "  git add docs/ src/examples/"
echo '  git commit -m "Update docs and examples from template"'
echo
