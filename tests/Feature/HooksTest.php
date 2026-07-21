<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Feature;

use ArtisanPack\LivewireUiComponents\Support\ModalBridge;
use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use ArtisanPack\LivewireUiComponents\View\Components\Icon;
use ArtisanPack\LivewireUiComponents\View\Components\Table;
use ArtisanPack\LivewireUiComponents\View\Components\ThemeToggle;
use ArtisanPackUI\Hooks\Facades\Action;
use ArtisanPackUI\Hooks\Facades\Filter;
use Illuminate\Support\Facades\Route;
use Illuminate\View\ComponentAttributeBag;
use PHPUnit\Framework\Attributes\Test;
use Throwable;

class HooksTest extends TestCase
{
    #[Test]
    public function component_classes_filter_receives_class_list_component_name_and_attributes(): void
    {
        $captured = null;

        Filter::add(
            'ap.livewireUiComponents.componentClasses',
            function (array $classes, string $name, ComponentAttributeBag $attrs) use (&$captured) {
                $captured = ['classes' => $classes, 'name' => $name, 'attrs' => $attrs];

                return array_merge($classes, ['extra-class']);
            },
        );

        $harness = new class extends \ArtisanPack\LivewireUiComponents\View\Components\BaseComponent {
            public function render(): \Illuminate\Contracts\View\View
            {
                return view('livewire-ui-components::components.button');
            }

            public function callGetClasses(array $classes): array
            {
                return $this->getClasses($classes);
            }
        };

        $result = $harness->callGetClasses(['btn', 'btn-primary']);

        $this->assertEquals(['btn', 'btn-primary', 'extra-class'], $result);
        $this->assertNotNull($captured);
        $this->assertEquals(['btn', 'btn-primary'], $captured['classes']);
        $this->assertInstanceOf(ComponentAttributeBag::class, $captured['attrs']);
    }

    #[Test]
    public function component_attributes_filter_runs_via_with_attributes(): void
    {
        $captured = null;

        Filter::add(
            'ap.livewireUiComponents.componentAttributes',
            function (ComponentAttributeBag $attrs, string $name) use (&$captured) {
                $captured = $name;

                return $attrs->merge(['data-hook-touched' => 'yes']);
            },
        );

        $harness = new class extends \ArtisanPack\LivewireUiComponents\View\Components\BaseComponent {
            public function render(): \Illuminate\Contracts\View\View
            {
                return view('livewire-ui-components::components.button');
            }
        };

        $harness->withAttributes(['class' => 'btn']);

        $this->assertNotNull($captured);
        $this->assertEquals('yes', $harness->attributes->get('data-hook-touched'));
    }

    #[Test]
    public function icon_alias_filter_rewrites_the_icon_name(): void
    {
        Filter::add(
            'ap.livewireUiComponents.iconAlias',
            fn (string $name) => 'user' === $name ? 'o-user' : $name,
        );

        $icon = new Icon(name: 'user');

        $this->assertEquals('heroicon-o-user', (string) $icon->icon());
    }

    #[Test]
    public function icon_alias_filter_leaves_dotted_names_alone_when_unfiltered(): void
    {
        $icon = new Icon(name: 'fa.solid.user');

        $this->assertEquals('fa-solid-user', (string) $icon->icon());
    }

    #[Test]
    public function toast_dispatched_action_fires_with_type_title_and_options(): void
    {
        $captured = null;

        Action::add(
            'ap.livewireUiComponents.toastDispatched',
            function (string $type, string $title, array $options) use (&$captured): void {
                $captured = compact('type', 'title', 'options');
            },
        );

        $harness = new class {
            use \ArtisanPack\LivewireUiComponents\Traits\Toast;

            public function js(string $script): void {}

            public function redirect(string $url, bool $navigate = false): string
            {
                return $url;
            }
        };

        // The trait's Blade::render call for the icon depends on the full
        // BladeUI\Icons manifest, which isn't wired up in the test env.
        // The action fires before the render, so we can still verify it.
        try {
            $harness->toast('success', 'Saved', 'It worked', 'top-end', 'o-check-circle', 'alert-success', 5000);
        } catch (Throwable) {
            // Ignore rendering failure in the test environment.
        }

        $this->assertNotNull($captured);
        $this->assertEquals('success', $captured['type']);
        $this->assertEquals('Saved', $captured['title']);
        $this->assertEquals('It worked', $captured['options']['description']);
        $this->assertEquals('o-check-circle', $captured['options']['icon']);
        $this->assertEquals(5000, $captured['options']['duration']);
    }

    #[Test]
    public function table_columns_filter_rewrites_headers(): void
    {
        Filter::add(
            'ap.livewireUiComponents.tableColumns',
            function (array $columns, string $context) {
                $columns[] = ['key' => 'context', 'label' => $context];

                return $columns;
            },
        );

        $table = new Table(
            headers: [['key' => 'name', 'label' => 'Name']],
            rows: [],
            id: 'users-table',
        );

        $this->assertCount(2, $table->headers);
        $this->assertEquals('users-table', $table->headers[1]['label']);
    }

    #[Test]
    public function table_columns_filter_uses_default_context_when_no_id(): void
    {
        $captured = null;

        Filter::add(
            'ap.livewireUiComponents.tableColumns',
            function (array $columns, string $context) use (&$captured) {
                $captured = $context;

                return $columns;
            },
        );

        new Table(headers: [['key' => 'name']], rows: []);

        $this->assertEquals('default', $captured);
    }

    #[Test]
    public function theme_colors_filter_can_swap_theme_configuration(): void
    {
        Filter::add(
            'ap.livewireUiComponents.themeColors',
            function (array $themes) {
                $themes['light']['label'] = 'Day';
                $themes['dark']['theme']  = 'midnight';

                return $themes;
            },
        );

        $toggle = new ThemeToggle;

        $this->assertEquals('Day', $toggle->light);
        $this->assertEquals('midnight', $toggle->darkTheme);
        $this->assertEquals('light', $toggle->lightTheme);
    }

    #[Test]
    public function modal_will_open_action_fires_with_modal_id(): void
    {
        $captured = null;

        Action::add(
            'ap.livewireUiComponents.modalWillOpen',
            function (string $modalId) use (&$captured): void {
                $captured = $modalId;
            },
        );

        ModalBridge::willOpen('confirm-delete');

        $this->assertEquals('confirm-delete', $captured);
    }

    #[Test]
    public function modal_will_close_action_fires_with_modal_id(): void
    {
        $captured = null;

        Action::add(
            'ap.livewireUiComponents.modalWillClose',
            function (string $modalId) use (&$captured): void {
                $captured = $modalId;
            },
        );

        ModalBridge::willClose('confirm-delete');

        $this->assertEquals('confirm-delete', $captured);
    }

    #[Test]
    public function spotlight_commands_filter_rewrites_search_results(): void
    {
        config()->set('artisanpack.livewire-ui-components.route_prefix', '');
        config()->set(
            'artisanpack.livewire-ui-components.components.spotlight.class',
            SpotlightHooksFixture::class,
        );

        $this->reloadRoutes();

        Filter::add(
            'ap.livewireUiComponents.spotlightCommands',
            function (array $commands) {
                $commands[] = ['id' => 'added-by-hook', 'name' => 'Added by hook'];

                return $commands;
            },
        );

        $response = $this->getJson(route('artisanpack.spotlight'));

        $response->assertOk();
        $payload = $response->json();
        $this->assertCount(2, $payload);
        $this->assertEquals('added-by-hook', $payload[1]['id']);
    }

    private function reloadRoutes(): void
    {
        Route::getRoutes()->refreshNameLookups();

        require __DIR__.'/../../routes/web.php';
    }
}

class SpotlightHooksFixture
{
    public function search($request): array
    {
        return [
            ['id' => 'original', 'name' => 'Original result'],
        ];
    }
}
