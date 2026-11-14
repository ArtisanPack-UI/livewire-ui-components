<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Feature;

use ArtisanPack\LivewireUiComponents\LivewireUiComponentsServiceProvider;
use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use ArtisanPack\LivewireUiComponents\View\Components\Alert;
use ArtisanPack\LivewireUiComponents\View\Components\Calendar;
use Illuminate\Support\Facades\Blade;
use Livewire\Mechanisms\ComponentRegistry;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use PHPUnit\Framework\Attributes\Test;

/**
 * Components render under any `prefix`, including none: the package's own
 * views compose components under the internal `artisanpack-` names.
 */
class ComponentPrefixTest extends TestCase
{
    #[Test]
    #[DefineEnvironment('useCustomPrefix')]
    public function a_composite_component_renders_under_a_custom_prefix(): void
    {
        $html = Blade::render('<x-ui-alert title="Saved" dismissible />');

        $this->assertStringContainsString('apui-alert', $html);
        $this->assertMatchesRegularExpression('/<button[^>]*apui-button[^>]*data-part="close-trigger"|<button[^>]*data-part="close-trigger"[^>]*apui-button/s', $html);
        $this->assertStringNotContainsString('<x-', $html);
    }

    #[Test]
    #[DefineEnvironment('useCustomPrefix')]
    public function the_table_renders_its_nested_components_under_a_custom_prefix(): void
    {
        $html = Blade::render(
            '<x-ui-table :headers="$headers" :rows="$rows" selectable />',
            [
                'headers' => [['key' => 'name', 'label' => 'Name']],
                'rows'    => [['id' => 1, 'name' => 'Ada']],
            ],
        );

        $this->assertStringContainsString('apui-table', $html);
        $this->assertStringContainsString('apui-checkbox', $html);
        $this->assertStringNotContainsString('<x-', $html);
    }

    #[Test]
    #[DefineEnvironment('useCustomPrefix')]
    public function a_custom_prefix_registers_the_prefixed_and_internal_names(): void
    {
        $aliases = Blade::getClassComponentAliases();

        $this->assertSame(Alert::class, $aliases['ui-alert']);
        $this->assertSame(Alert::class, $aliases['artisanpack-alert']);
        $this->assertArrayNotHasKey('alert', $aliases);
        $this->assertSame(Calendar::class, $this->livewireClass('ui-calendar'));
    }

    #[Test]
    #[DefineEnvironment('useEmptyPrefix')]
    public function a_composite_component_renders_with_an_empty_prefix(): void
    {
        $html = Blade::render('<x-alert title="Saved" dismissible />');

        $this->assertStringContainsString('apui-alert', $html);
        $this->assertStringContainsString('data-part="close-trigger"', $html);
        $this->assertStringContainsString('apui-button', $html);
        $this->assertStringNotContainsString('<x-', $html);
    }

    #[Test]
    #[DefineEnvironment('useEmptyPrefix')]
    public function an_empty_prefix_registers_bare_names_without_a_double_hyphen(): void
    {
        $aliases = Blade::getClassComponentAliases();

        $this->assertSame(Alert::class, $aliases['alert']);
        $this->assertSame(Alert::class, $aliases['artisanpack-alert']);
        $this->assertArrayNotHasKey('-alert', $aliases);
        $this->assertSame(Calendar::class, $this->livewireClass('calendar'));
    }

    #[Test]
    #[DefineEnvironment('useTrailingHyphenPrefix')]
    public function a_trailing_hyphen_in_the_prefix_is_ignored(): void
    {
        $aliases = Blade::getClassComponentAliases();

        $this->assertSame('artisanpack', LivewireUiComponentsServiceProvider::componentPrefix());
        $this->assertArrayHasKey('artisanpack-alert', $aliases);
        $this->assertArrayNotHasKey('artisanpack--alert', $aliases);
    }

    #[Test]
    public function component_name_joins_the_prefix_with_a_hyphen(): void
    {
        $this->assertSame('artisanpack-button', LivewireUiComponentsServiceProvider::componentName('button'));
        $this->assertSame('ui-button', LivewireUiComponentsServiceProvider::componentName('button', 'ui'));
        $this->assertSame('button', LivewireUiComponentsServiceProvider::componentName('button', ''));
        $this->assertSame(['artisanpack-button'], LivewireUiComponentsServiceProvider::registeredNames('button', 'artisanpack'));
        $this->assertSame(['button', 'artisanpack-button'], LivewireUiComponentsServiceProvider::registeredNames('button', ''));
    }

    /**
     * The class a Livewire component name resolves to (Livewire 3's registry).
     */
    protected function livewireClass(string $name): ?string
    {
        if (! class_exists(ComponentRegistry::class)) {
            $this->markTestSkipped('Needs Livewire 3\'s component registry.');
        }

        return app(ComponentRegistry::class)->getClass($name);
    }

    protected function useCustomPrefix($app): void
    {
        $app['config']->set('artisanpack.livewire-ui-components.prefix', 'ui');
    }

    protected function useEmptyPrefix($app): void
    {
        $app['config']->set('artisanpack.livewire-ui-components.prefix', '');
    }

    protected function useTrailingHyphenPrefix($app): void
    {
        $app['config']->set('artisanpack.livewire-ui-components.prefix', 'artisanpack-');
    }
}
