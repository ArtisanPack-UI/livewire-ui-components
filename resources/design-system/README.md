# Design system token map

`tokens.php` is a copy of the token map that `@artisanpack-ui/tokens` builds (`packages/tokens/generated/tokens.php` in the [design-system](https://github.com/ArtisanPack-UI/design-system) repo). `Theming\DesignTokens` reads it, so PHP needs no Node.

Don't edit it by hand. When the tokens change, copy the new build over it and the generator fixtures from `packages/tokens/test/fixtures/generator/*.json` into `tests/Fixtures/design-system/generator/`. The parity tests fail until both match. The asset build in #42 will pull these from the published packages instead.
