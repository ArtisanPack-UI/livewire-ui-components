# Design system token map, icons and upgrade rules

`tokens.php` is a copy of the token map that `@artisanpack-ui/tokens` builds (`packages/tokens/generated/tokens.php` in the [design-system](https://github.com/ArtisanPack-UI/design-system) repo). `Theming\DesignTokens` reads it, so PHP needs no Node.

`icons/lucide.json` is a copy of the default icon set from `@artisanpack-ui/behavior` (`packages/behavior/icons/lucide.json`, D-017). `Support\IconRegistry` resolves string icon names (`leading-icon="plus"`) against it, so a name renders the same SVG as in React and Vue. `icons/LICENSE-lucide.txt` is Lucide's ISC notice, copied with it.

`upgrade-rules.php` is a copy of the Livewire upgrade rules that `@artisanpack-ui/codemods` generates (`packages/codemods/generated/upgrade-rules.php`, D-056): what every 2.x tag, attribute and slot becomes in 3.0, and the DaisyUI classes to report. `Upgrade\UpgradeRules` reads it for `php artisan artisanpack:upgrade-ui`.

Don't edit any of them by hand. The asset build writes them, along with the prebuilt CSS and JS in `dist/`, from the published design-system packages pinned in `package.json`:

```bash
npm ci
node scripts/build-assets.mjs --check   # fails if the committed copies are stale (CI runs this through DesignSystemDistTest)
node scripts/build-assets.mjs           # rewrites them after you bump the pins
```

To move to a new design-system release, change the versions in `package.json`, run `npm install`, then rebuild. `npm ci` also installs the design-system PHP kit (`@artisanpack-ui/manifest` and `@artisanpack-ui/testing`), which `composer.json` autoloads for the parity tests.

The npm packages don't ship the design system's test fixtures: the generator fixtures in `tests/Fixtures/design-system/generator/` and the Markdown vectors in `tests/Fixtures/design-system/markdown-vectors.json`. To refresh those, or to try unreleased design-system changes, build from a checkout instead (run `npm run build` in it first):

```bash
node scripts/build-assets.mjs --from ../design-system   # or DESIGN_SYSTEM_PATH=../design-system
```
