<?php
/**
 * MenuItem
 *
 * This file contains the MenuItem class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\View
 * @subpackage Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */


namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use ArtisanPackUI\Accessibility\A11y;
/**
 * MenuItem Class
 *
 * Provides functionality for the MenuItem component.
 *
 * @since 1.0.0
 */

class MenuItem extends Component
{
	public string $uuid;
	// For theme colors like 'primary'
	public array $themeColorClasses = [];
	// For dynamic hex colors from a database
	public ?string $dynamicBgColor = null;
	public ?string $dynamicTextColor = null;

	public function __construct(
		public ?string $id = null,
		public ?string $title = null,
		public ?string $icon = null,
		public ?string $iconClasses = null,
		public ?string $spinner = null,
		public ?string $link = null,
		public ?string $route = null,
		public ?bool $external = false,
		public ?bool $noWireNavigate = false,
		public ?string $badge = null,
		public ?string $badgeClasses = null,
		public ?bool $active = false,
		public ?bool $separator = false,
		public ?bool $hidden = false,
		public ?bool $disabled = false,
		public ?bool $exact = false,
		public ?string $bgColor = null
	) {
		$this->uuid = "artisanpack" . md5(serialize($this)) . $id;

		if ($this->bgColor) {
			// Check if it's a dynamic hex color
			if (str_starts_with($this->bgColor, '#')) {
				$this->dynamicBgColor = $this->bgColor;
				$this->dynamicTextColor = generateAccessibleTextColor($this->bgColor);
			} else {
				// Otherwise, treat as a theme color and generate Tailwind classes
				$baseBgClass = "bg-{$this->bgColor}";
				$baseTextColorClass = "text-{$this->bgColor}-content";

				$this->themeColorClasses = [
					"active-bg"    => $baseBgClass,
					"active-text"  => $baseTextColorClass,
					"hover-focus" => [
						"hover:{$baseBgClass}",
						"hover:{$baseTextColorClass}",
						"focus:{$baseBgClass}",
						"focus:{$baseTextColorClass}",
					]
				];
			}
		}
	}

	public function spinnerTarget(): ?string
	{
		if ($this->spinner == 1) {
			return $this->attributes->whereStartsWith('wire:click')->first();
		}

		return $this->spinner;
	}

	public function routeMatches(): bool
	{
		if ($this->link == null) {
			return false;
		}

		if ($this->route) {
			return request()->routeIs($this->route);
		}

		$link = url($this->link ?? '');
		$route = url(request()->url());

		if ($link == $route) {
			return true;
		}

		return ! $this->exact && $this->link != '/' && Str::startsWith($route, $link);
	}

	public function render(): View|Closure|string
	{
		if ($this->hidden === true) {
			return '';
		}

		return view('livewire-ui-components::components.menu-item');
	}
}
