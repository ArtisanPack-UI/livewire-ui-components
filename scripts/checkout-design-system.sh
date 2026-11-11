#!/usr/bin/env bash
#
# Clones ArtisanPack-UI/design-system next to this repo, at ../design-system,
# where the local `file:` and Composer path links expect it. Its packages are
# unpublished until design-system #81, so CI needs the checkout.
#
# Uses the design-system branch with the same name as this one when it exists
# (companion PRs), else release/1.0. With --build it also installs and builds
# the npm packages this repo links to.
#
# Usage: scripts/checkout-design-system.sh [--build]
set -euo pipefail

root="$(cd "$(dirname "$0")/.." && pwd)"
target="$(dirname "$root")/design-system"
repo="https://github.com/ArtisanPack-UI/design-system.git"
branch="${GITHUB_HEAD_REF:-${GITHUB_REF_NAME:-}}"
ref="release/1.0"

if [ -n "$branch" ] && git ls-remote --exit-code --heads "$repo" "$branch" >/dev/null 2>&1; then
	ref="$branch"
fi

if [ ! -d "$target/.git" ]; then
	echo "Cloning design-system ($ref) into $target"
	git clone --depth 1 --branch "$ref" "$repo" "$target"
fi

if [ "${1:-}" = "--build" ]; then
	cd "$target"
	npm ci --no-audit --no-fund
	npm run build \
		--workspace @artisanpack-ui/tokens \
		--workspace @artisanpack-ui/css \
		--workspace @artisanpack-ui/behavior \
		--workspace @artisanpack-ui/manifest \
		--workspace @artisanpack-ui/testing
fi
