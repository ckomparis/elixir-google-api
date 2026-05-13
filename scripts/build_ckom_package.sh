#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
PLUGIN_DIR="$ROOT_DIR/plugins/system/sensogpt"
PACKAGE_DIR="$ROOT_DIR/packages/pkg_ckom_gpttags"
DIST_DIR="$ROOT_DIR/dist"
RELEASE_DIR="$ROOT_DIR/release"
TMP_DIR="$ROOT_DIR/.tmp/pkg_ckom_gpttags"

mkdir -p "$DIST_DIR" "$RELEASE_DIR" "$TMP_DIR"

rm -f "$DIST_DIR"/plg_system_sensogpt-*.zip "$DIST_DIR"/pkg_ckom_gpttags-*.zip
rm -f "$RELEASE_DIR"/pkg_ckom_gpttags-*.zip "$RELEASE_DIR"/pkg_ckom_gpttags-*.zip.sha256
rm -f "$PACKAGE_DIR"/plg_system_sensogpt-*.zip
rm -f "$TMP_DIR"/*

(
  cd "$PLUGIN_DIR"
  zip -r -X "$DIST_DIR/plg_system_sensogpt-0.2.1.zip" .
)

cp "$DIST_DIR/plg_system_sensogpt-0.2.1.zip" "$PACKAGE_DIR/plg_system_sensogpt-0.2.1.zip"
cp "$PACKAGE_DIR/pkg_ckom_gpttags.xml" "$TMP_DIR/pkg_ckom_gpttags.xml"
cp "$PACKAGE_DIR/plg_system_sensogpt-0.2.1.zip" "$TMP_DIR/plg_system_sensogpt-0.2.1.zip"

(
  cd "$TMP_DIR"
  zip -r -X "$DIST_DIR/pkg_ckom_gpttags-0.2.1.zip" pkg_ckom_gpttags.xml plg_system_sensogpt-0.2.1.zip
)

cp "$DIST_DIR/pkg_ckom_gpttags-0.2.1.zip" "$RELEASE_DIR/pkg_ckom_gpttags-0.2.1.zip"
sha256sum "$RELEASE_DIR/pkg_ckom_gpttags-0.2.1.zip" > "$RELEASE_DIR/pkg_ckom_gpttags-0.2.1.zip.sha256"

echo "Package ready: $RELEASE_DIR/pkg_ckom_gpttags-0.2.1.zip"
