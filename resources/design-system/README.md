# Design system token map and icons

`tokens.php` is a copy of the token map that `@artisanpack-ui/tokens` builds (`packages/tokens/generated/tokens.php` in the [design-system](https://github.com/ArtisanPack-UI/design-system) repo). `Theming\DesignTokens` reads it, so PHP needs no Node.

`icons/lucide.json` is a copy of the default icon set from `@artisanpack-ui/behavior` (`packages/behavior/icons/lucide.json`, D-017). `Support\IconRegistry` resolves string icon names (`leading-icon="plus"`) against it, so a name renders the same SVG as in React and Vue. `icons/LICENSE-lucide.txt` is Lucide's ISC notice, copied with it.

Don't edit either by hand. The asset build writes them, along with the prebuilt CSS and JS in `dist/` and, from a checkout, the generator fixtures in `tests/Fixtures/design-system/generator/`:

```bash
node scripts/build-assets.mjs --from ../design-system   # or DESIGN_SYSTEM_PATH=../design-system
node scripts/build-assets.mjs --check --from ../design-system   # fails if the committed copies are stale
```

Build the design-system packages first (`npm run build` in the checkout). Without `--from` or `DESIGN_SYSTEM_PATH`, the script reads `node_modules/@artisanpack-ui/*`, once those packages are published; the generator fixtures aren't in the npm packages, so that path leaves them as they are. The parity tests fail until the token map and the fixtures match.
