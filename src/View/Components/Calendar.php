<?php
/**
 * Calendar
 *
 * This file contains the Calendar class for the ArtisanPack UI Livewire UI Components package.
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

use Carbon\CarbonPeriod;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;
/**
 * Calendar Class
 *
 * Provides functionality for the Calendar component.
 *
 * @since 1.0.0
 */

class Calendar extends Component
{
    public string $uuid;
    public string $customColorScript;

    public function __construct(
        public ?string $id = null,
        public ?int $months = 1,
        public ?string $locale = 'en-EN',
        public ?bool $weekendHighlight = false,
        public ?bool $sundayStart = false,
        public ?string $colorScheme = 'primary', // New: primary, secondary, accent, or custom
        public ?string $customColor = null,      // New: hex color code for custom scheme
        public ?string $view = 'month',          // New: day, week, month, year
        public ?array $config = [],
        public ?array $events = [],
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function setup(): string
    {
        $config = json_encode(array_merge([
            'type' => $this->months == 1 ? 'default' : 'multiple',
            'months' => $this->months,
            'jumpMonths' => $this->months,
            'view' => $this->view,                // New: Add view option
            'popups' => $this->popups(),
            'settings' => [
                'lang' => $this->locale,
                'visibility' => [
                    'daysOutside' => false,
                    'weekend' => $this->weekendHighlight,
                ],
                'selection' => [
                    'day' => false,
                ],
                'iso8601' => ! $this->sundayStart,
                'theme' => [                      // New: Add theme settings
                    'colorScheme' => $this->colorScheme,
                    'customColor' => $this->customColor,
                ],
            ],
            'CSSClasses' => 'y',
            'actions' => 'x',
        ], $this->config));

        $config = $this->addCss($config);

        return $config;
    }

    /**
     * Determines appropriate text color (black or white) based on background color
     *
     * @param string $hexColor Hex color code (e.g., #FF5733)
     * @return string Text color (#ffffff or #000000)
     */
    public function a11yGetContrastColor(string $hexColor = null): string
    {
        if (!$hexColor) {
            return '#ffffff'; // Default to white text
        }

        // Remove # if present
        $hexColor = ltrim($hexColor, '#');

        // Convert to RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        // Calculate luminance - standard formula
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Return black for bright colors, white for dark colors
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    // Add Tailwind CSS classes for the calendar
    public function addCss(string $config): string
    {
        $cssClasses = [
            'grid' => 'vanilla-calendar-grid flex flex-wrap justify-around',
            'calendar' => 'vanilla-calendar w-full',
            'header' => 'flex justify-between items-center p-4 border-b border-stroke dark:border-dark-3',
            'headerTitle' => 'text-lg font-semibold text-dark dark:text-white',
            'headerButton' => 'p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-3 transition-colors duration-200 ease-in-out',
            'weekDays' => 'grid grid-cols-7 text-center py-2 border-b border-stroke dark:border-dark-3',
            'weekDay' => match($this->colorScheme) {
                'secondary' => 'py-2 text-sm font-medium bg-secondary text-white',
                'accent' => 'py-2 text-sm font-medium bg-accent text-white',
                'custom' => 'py-2 text-sm font-medium custom-color-header',
                default => 'py-2 text-sm font-medium bg-primary text-white',
            },
            // Responsive day names for different screen sizes
            'weekDayName' => [
                'full' => 'hidden lg:block', // Full name (e.g., "Monday") - visible on large screens
                'short' => 'hidden md:block lg:hidden', // Short name (e.g., "Mon") - visible on medium screens
                'min' => 'block md:hidden', // Minimal name (e.g., "M") - visible on small screens
            ],
            'month' => 'grid grid-cols-7 gap-px',
            'monthDay' => 'p-1 h-24 sm:h-28 md:h-32 border border-stroke dark:border-dark-3 transition-all duration-200 ease-in-out hover:bg-gray-50 dark:hover:bg-dark-3',
            'monthDayNumber' => 'text-sm font-medium p-1',
            'monthDayToday' => 'bg-gray-100 dark:bg-dark-3',
            'monthDaySelected' => match($this->colorScheme) {
                'secondary' => 'bg-secondary/10 border-secondary',
                'accent' => 'bg-accent/10 border-accent',
                'custom' => 'custom-color-selected',
                default => 'bg-primary/10 border-primary',
            },
            'monthDayWeekend' => 'bg-gray-50 dark:bg-dark-3/50',
            'event' => [
                'primary' => 'bg-primary text-white rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
                'secondary' => 'bg-secondary text-white rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
                'accent' => 'bg-accent text-white rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
                'custom' => 'custom-color rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
            ],
            'popup' => 'bg-white dark:bg-dark-2 p-3 rounded-lg shadow-lg border border-stroke dark:border-dark-3 max-w-xs z-50',
            'popupEvent' => 'mb-2 last:mb-0',
            'popupEventTitle' => 'font-semibold text-dark dark:text-white',
            'popupEventDescription' => 'text-sm text-gray-600 dark:text-gray-300',
            'popupEventTime' => 'text-xs text-gray-500 dark:text-gray-400',
        ];

        return str_replace('"y"', json_encode($cssClasses), $config);
    }

    public function popups()
    {
        $buffer = [];

        return collect($this->events)->flatMap(function ($event) use (&$buffer) {
            if ($range = $event['range'] ?? []) {
                $dates = [];

                $period = CarbonPeriod::create($range[0], $range[1]);

                foreach ($period as $date) {
                    $dates[] = Carbon::parse($date)->format('Y-m-d');
                }
            }

            if (isset($event['date'])) {
                $dates = [Carbon::parse($event['date'])->format('Y-m-d')];
            }

            // Get event color scheme
            $colorScheme = $event['colorScheme'] ?? $this->colorScheme;
            $customColor = $event['customColor'] ?? $this->customColor;

            // Determine CSS class based on color scheme
            $cssClass = match($colorScheme) {
                'secondary' => 'bg-secondary text-white',
                'accent' => 'bg-accent text-white',
                'custom' => '',  // Will use inline style with custom color
                default => 'bg-primary text-white', // Default to primary
            };

            return collect($dates)->flatMap(function ($date) use ($event, &$buffer, $colorScheme, $customColor, $cssClass) {
                // Use title instead of description if available
                $title = $event['title'] ?? $event['description'] ?? null;
                $startTime = isset($event['start_time']) ? '<div class="text-sm">' . $event['start_time'] . '</div>' : '';

                // Create HTML for event popup
                $html = '<div class="event-popup">';
                $html .= '<div><strong>' . $event['label'] . '</strong></div>';
                $html .= $startTime;
                $html .= '<div>' . $title . '</div>';
                $html .= '<hr class="my-3 last:hidden" />';
                $html .= '</div>';

                $buffer[$date] = ($buffer[$date] ?? '') . $html;

                // Add custom color inline style if needed
                $modifier = $event['css'] ?? $cssClass;
                if ($colorScheme === 'custom' && $customColor) {
                    $textColor = $this->a11yGetContrastColor($customColor);
                    $modifier .= ' custom-color';
                    // The inline style will be applied via JavaScript
                }

                return [
                    $date => [
                        'modifier' => $modifier,
                        'html' => $buffer[$date],
                        'data' => [
                            'color-scheme' => $colorScheme,
                            'custom-color' => $customColor,
                        ],
                    ],
                ];
            });
        });
    }

    /**
     * Generate JavaScript for handling custom colors
     *
     * @return string JavaScript code
     */
    public function customColorScript(): string
    {
        // Get the custom color and contrast color
        $customColor = $this->customColor ?? '#6366f1'; // Default to indigo if not set
        $textColor = $this->a11yGetContrastColor($customColor);

        $this->customColorScript = <<<JS
    function applyCustomColors() {
        // Apply custom color to regular event elements
        document.querySelectorAll('.custom-color').forEach(el => {
            const customColor = el.closest('[data-custom-color]')?.dataset.customColor || '{$customColor}';
            if (customColor) {
                const textColor = a11yGetContrastColor(customColor);
                el.style.backgroundColor = customColor;
                el.style.color = textColor;
            }
        });
        
        // Apply custom color to header elements
        document.querySelectorAll('.custom-color-header').forEach(el => {
            el.style.backgroundColor = '{$customColor}';
            el.style.color = '{$textColor}';
        });
        
        // Apply custom color to selected day elements
        document.querySelectorAll('.custom-color-selected').forEach(el => {
            el.style.backgroundColor = `\${'{$customColor}'}20`; // 20% opacity
            el.style.borderColor = '{$customColor}';
        });
        
        // Add hover effect for custom color elements
        document.querySelectorAll('.custom-color').forEach(el => {
            el.addEventListener('mouseenter', () => {
                el.style.transform = 'scale(1.02)';
            });
            el.addEventListener('mouseleave', () => {
                el.style.transform = 'scale(1)';
            });
        });
    }
    
    function a11yGetContrastColor(hexColor) {
        hexColor = hexColor.replace('#', '');
        const r = parseInt(hexColor.substr(0, 2), 16);
        const g = parseInt(hexColor.substr(2, 2), 16);
        const b = parseInt(hexColor.substr(4, 2), 16);
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }
JS;

        return $this->customColorScript;
    }

    public function render(): View|Closure|string
    {
		return view('livewire-ui-components::calendar');
	}
}
