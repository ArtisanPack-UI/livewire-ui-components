<?php

/**
 * UserMenu Component
 *
 * The design-system UserMenu (#58): the account menu, composed from Avatar
 * and DropdownMenu on the same menu machine.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * UserMenu Component Class
 *
 * An avatar button that opens a DropdownMenu (`.apui-dropdown-menu
 * .apui-user-menu`) with the person's identity, a workspace group, a theme
 * control, the `items` and composed dropdown-menu children, and Log out last.
 * `wire:model` binds `open` through `$wire.entangle()`, as DropdownMenu does.
 * Workspace and theme options report workspace-change and theme-change;
 * other items report select. Attributes land on the element that dispatches
 * those events; `class` lands on the panel.
 *
 * @since 3.0.0
 */
class UserMenu extends DropdownMenu
{
    /**
     * The themes the theme control offers, with their icons.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const THEMES = ['light' => 'sun', 'dark' => 'moon', 'system' => 'monitor'];

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'user-menu';

    /**
     * Props that change between renders without changing the id.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['open', 'items', 'label', 'user', 'workspaces', 'workspace', 'theme'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $user  Who is signed in: `{ name, email, avatar, role, status }`.
     * @param  array<int, array<string, mixed>>  $workspaces  Workspaces to switch between: `{ id, name }`.
     * @param  string|null  $workspace  The current workspace's id.
     * @param  bool  $hideTheme  Hides the theme control.
     * @param  string  $theme  The checked theme: light, dark or system.
     * @param  array<int, array<string, mixed>>  $items  Items between the theme control and Log out, as DropdownMenu items data.
     * @param  bool  $compact  Shows only the avatar in the trigger.
     * @param  bool  $open  Whether it's open. `wire:model` binds it instead.
     * @param  string  $align  Which of the menu's edges lines up with the trigger's: start, center or end.
     * @param  string  $surface  The menu's surface: overlay or glass.
     * @param  bool  $portal  Renders the menu at the end of the body (x-teleport).
     * @param  string|null  $workspaceLabel  The workspace group's label. Defaults to __( 'Workspace' ).
     * @param  string|null  $themeLabel  The theme group's label. Defaults to __( 'Theme' ).
     * @param  array<string, string>  $themeLabels  The theme options' names: `{ light, dark, system }`.
     * @param  string|null  $logoutLabel  The last item's label. Defaults to __( 'Log out' ).
     * @param  string|null  $logoutAction  A URL Log out posts to, with the CSRF token.
     * @param  array<string, string>  $classes  Extra classes per part, the menu's included.
     */
    public function __construct(
        public array $user = [],
        public array $workspaces = [],
        public ?string $workspace = null,
        public bool $hideTheme = false,
        public string $theme = 'system',
        array $items = [],
        public bool $compact = false,
        bool $open = false,
        string $align = 'start',
        string $surface = 'overlay',
        bool $portal = true,
        public ?string $workspaceLabel = null,
        public ?string $themeLabel = null,
        public array $themeLabels = [],
        public ?string $logoutLabel = null,
        public ?string $logoutAction = null,
        array $classes = [],
    ) {
        parent::__construct(open: $open, label: null, items: $items, align: $align, surface: $surface, portal: $portal, classes: $classes);

        $this->workspaceLabel ??= __('Workspace');
        $this->themeLabel ??= __('Theme');
        $this->logoutLabel ??= __('Log out');
        $this->themeLabels = [
            'light'  => (string) ($this->themeLabels['light'] ?? __('Light')),
            'dark'   => (string) ($this->themeLabels['dark'] ?? __('Dark')),
            'system' => (string) ($this->themeLabels['system'] ?? __('System')),
        ];
    }

    /**
     * A field of `user` as a trimmed string, or an empty string.
     *
     * @since 3.0.0
     *
     * @param  string  $key  name, email, avatar, role or status.
     *
     * @return string The value.
     */
    public function userField(string $key): string
    {
        $value = data_get($this->user, $key);

        return is_scalar($value) ? trim((string) $value) : '';
    }

    /**
     * The workspaces as `{ id, name }` pairs, skipping malformed entries.
     *
     * @since 3.0.0
     *
     * @return list<array{id: string, name: string}> The workspaces.
     */
    public function workspaceOptions(): array
    {
        $options = [];
        foreach ($this->workspaces as $workspace) {
            $id   = data_get($workspace, 'id');
            $name = data_get($workspace, 'name');
            if (is_scalar($id) && '' !== (string) $id) {
                $options[] = ['id' => (string) $id, 'name' => is_scalar($name) ? (string) $name : (string) $id];
            }
        }

        return $options;
    }

    /**
     * The checked theme, falling back to system.
     *
     * @since 3.0.0
     *
     * @return string light, dark or system.
     */
    public function themeValue(): string
    {
        return array_key_exists($this->theme, self::THEMES) ? $this->theme : 'system';
    }

    /**
     * The `x-data` expression: `apuiDropdownMenu()` with the machine id, the
     * open state and the alignment, as DropdownMenu mounts it. The widget
     * dispatches open-change, select and checked-change; the view turns the
     * workspace and theme options' checked-change into workspace-change and
     * theme-change.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        return sprintf(
            'apuiDropdownMenu({ id: %s, open: %s, align: %s })',
            Js::from($this->baseId())->toHtml(),
            $this->openExpression(),
            Js::from(in_array($this->align, ['start', 'center', 'end'], true) ? $this->align : 'start')->toHtml(),
        );
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.user-menu');
    }
}
