# Calendar Component Implementation - Step 1

## Update the Calendar Component Class

This document outlines the first step in implementing the enhanced Calendar component according to the [Calendar Implementation Plan](calendar-implementation-plan.md). This step focuses on updating the Calendar component class with new properties, enhanced configuration options, and updated event handling.

### 1. Add New Properties for Color Scheme and View Options

Update the `__construct` method in the `Calendar` class to include the new properties:

```php
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
)
{
    $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
}
```

### 2. Enhance Configuration Options

Update the `setup` method to incorporate the new properties into the configuration:

```php
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
```

### 3. Add Helper Method for Color Contrast

Add a new method to determine appropriate text color based on background color:

```php
/**
 * Determines appropriate text color (black or white) based on background color
 * 
 * @param string $hexColor Hex color code (e.g., #FF5733)
 * @return string Text color (#ffffff or #000000)
 */
public function getContrastColor(string $hexColor = null): string
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
```

### 4. Update Event Handling

Modify the `popups` method to support the new event properties:

```php
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
                $textColor = $this->getContrastColor($customColor);
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
```

### 5. Update CSS Classes Method

Enhance the `addCss` method to include classes for the new color schemes:

```php
public function addCss(string $config): string
{
    $cssClasses = [
        'grid' => 'vanilla-calendar-grid flex flex-wrap justify-around',
        'calendar' => 'vanilla-calendar',
        'event' => [
            'primary' => 'bg-primary text-white',
            'secondary' => 'bg-secondary text-white',
            'accent' => 'bg-accent text-white',
            'custom' => 'custom-color', // Will be styled inline with JavaScript
        ],
    ];
    
    return str_replace('"y"', json_encode($cssClasses), $config);
}
```

### 6. Add JavaScript for Custom Colors

Add a method to generate JavaScript for handling custom colors:

```php
public function customColorScript(): string
{
    return <<<'JS'
    function applyCustomColors() {
        document.querySelectorAll('.custom-color').forEach(el => {
            const customColor = el.closest('[data-custom-color]')?.dataset.customColor;
            if (customColor) {
                const textColor = getContrastColor(customColor);
                el.style.backgroundColor = customColor;
                el.style.color = textColor;
            }
        });
    }
    
    function getContrastColor(hexColor) {
        hexColor = hexColor.replace('#', '');
        const r = parseInt(hexColor.substr(0, 2), 16);
        const g = parseInt(hexColor.substr(2, 2), 16);
        const b = parseInt(hexColor.substr(4, 2), 16);
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }
    JS;
}
```

### 7. Update the Render Method

Modify the `render` method to include the custom color script:

```php
public function render(): View|Closure|string
{
    return <<<'HTML'
        <div wire:key="calendar-{{ rand() }}">
            <div 
                x-data 
                x-init="
                    const calendar = new VanillaCalendar($el, {{ $setup() }}); 
                    calendar.init();
                    {{ $customColorScript() }}
                    applyCustomColors();
                " 
                class="w-fit"
            ></div>
        </div>
        HTML;
}
```

## Next Steps

After implementing these changes to the Calendar component class, the next step will be to implement the Tailwind-based styling as outlined in the implementation plan. This will involve:

1. Using Tailwind utility classes for all styling
2. Implementing color scheme variations using Tailwind's color utilities
3. Ensuring responsive behavior using Tailwind's responsive prefixes
4. Adding transitions and hover effects with Tailwind utilities