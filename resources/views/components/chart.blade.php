@php
    $baseClasses = [
        'relative rounded-lg p-4',
        'bg-base-100' => !$glass,
    ];
    $inlineStyles = [];

    // Handle glass effect (call $glassStyle() only once)
    if ($glass) {
        $baseClasses[] = $glassClasses();
        $computedGlassStyle = $glassStyle();
        if ($computedGlassStyle) {
            $inlineStyles[] = $computedGlassStyle;
        }
    }

    // Merge inline styles (preserves user-provided styles)
    if (!empty($inlineStyles)) {
        $attributes = $attributes->merge(['style' => implode(' ', $inlineStyles)]);
    }

    // Merge wire:key and classes into attributes
    $attributes = $attributes->merge(['wire:key' => $uuid])->class($baseClasses);

    // Prepare JSON data for real-time updates
    $chartOptionsJson = json_encode($chartOptions());
    $chartSeriesJson = json_encode($chartSeries());
    $themeConfigJson = json_encode($themeConfig());
    $glassLightOverridesJson = json_encode($glassLightOverrides());
    $glassDarkOverridesJson = json_encode($glassDarkOverrides());
@endphp
<div
    {{ $attributes }}
    x-data="artisanpackChart({
        initialOptions: {{ $chartOptionsJson }},
        initialSeries: {{ $chartSeriesJson }},
        themeConfig: {{ $themeConfigJson }},
        themeName: @js($chartTheme()),
        isGlassTheme: @js($isGlassTheme()),
        glassLightOverrides: {{ $glassLightOverridesJson }},
        glassDarkOverrides: {{ $glassDarkOverridesJson }},
        chartId: @js($uuid)
    })"
    data-chart-options="{{ $chartOptionsJson }}"
    data-chart-series="{{ $chartSeriesJson }}"
>
    <div x-ref="chart" wire:ignore></div>
</div>

@once
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('artisanpackChart', (config) => ({
        chart: null,
        themeObserver: null,
        baseOptions: null, // Pristine options without theme overlays
        options: config.initialOptions,
        series: config.initialSeries,
        themeConfig: config.themeConfig,
        themeName: config.themeName,
        isGlassTheme: config.isGlassTheme,
        glassLightOverrides: config.glassLightOverrides,
        glassDarkOverrides: config.glassDarkOverrides,
        chartId: config.chartId,
        isDark: document.documentElement.getAttribute('data-theme') === 'dark',
        isUpdating: false,
        livewireListeners: [],

        init() {
            // Store pristine base options before any theme overlays
            this.baseOptions = this.deepClone(config.initialOptions);
            this.applyTheme();
            this.renderChart();
            this.watchTheme();
            this.watchLivewire();
            this.setupLivewireListeners();
            this.setupDataAttributeObserver();
        },

        /**
         * Deep clone an object to create a pristine copy.
         */
        deepClone(obj) {
            return JSON.parse(JSON.stringify(obj));
        },

        /**
         * Update the base options when new options are received from server.
         * This ensures applyTheme always rebuilds from clean server state.
         */
        updateBaseOptions(newOptions) {
            this.baseOptions = this.deepClone(newOptions);
        },

        applyTheme() {
            // Reset to pristine base options to avoid accumulating stale theme keys
            this.options = this.deepClone(this.baseOptions);

            // Apply the base theme config if set
            if (this.themeConfig && Object.keys(this.themeConfig).length > 0) {
                this.options = this.mergeDeep(this.options, this.themeConfig);
            }

            // Then apply mode-specific overrides
            if (this.isGlassTheme) {
                // Glass theme uses special overrides for light/dark
                const modeOverrides = this.isDark ? this.glassDarkOverrides : this.glassLightOverrides;
                this.options = this.mergeDeep(this.options, modeOverrides);
            } else if (this.themeName === 'artisanpack-light' || this.themeName === 'artisanpack-dark') {
                // For light/dark themes, switch between them based on mode
                const autoTheme = this.isDark ? this.getDarkTheme() : this.getLightTheme();
                this.options = this.mergeDeep(this.options, autoTheme);
            } else {
                // No theme set, use basic dark/light mode adjustments
                const basicTheme = this.isDark ? this.getBasicDarkTheme() : this.getBasicLightTheme();
                this.options = this.mergeDeep(this.options, basicTheme);
            }
        },

        getBasicLightTheme() {
            return {
                chart: {
                    foreColor: '#374151',
                },
                theme: {
                    mode: 'light',
                },
                grid: {
                    borderColor: '#e5e7eb',
                },
                tooltip: {
                    theme: 'light',
                },
            };
        },

        getBasicDarkTheme() {
            return {
                chart: {
                    foreColor: '#e5e7eb',
                },
                theme: {
                    mode: 'dark',
                },
                grid: {
                    borderColor: '#374151',
                },
                tooltip: {
                    theme: 'dark',
                },
            };
        },

        getLightTheme() {
            // Return full light theme for auto-switching
            return {
                colors: ['#3b82f6', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#6366f1'],
                chart: {
                    foreColor: '#374151',
                    dropShadow: {
                        enabled: false,
                    },
                },
                theme: {
                    mode: 'light',
                },
                grid: {
                    borderColor: '#e5e7eb',
                },
                xaxis: {
                    axisBorder: {
                        color: '#d1d5db',
                    },
                    axisTicks: {
                        color: '#d1d5db',
                    },
                    labels: {
                        style: {
                            colors: '#6b7280',
                        },
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#6b7280',
                        },
                    },
                },
                tooltip: {
                    theme: 'light',
                },
                legend: {
                    labels: {
                        colors: '#374151',
                    },
                },
                fill: {
                    opacity: 1,
                },
            };
        },

        getDarkTheme() {
            // Return full dark theme for auto-switching
            return {
                colors: ['#60a5fa', '#a78bfa', '#22d3ee', '#34d399', '#fbbf24', '#f87171', '#f472b6', '#818cf8'],
                chart: {
                    foreColor: '#e5e7eb',
                    dropShadow: {
                        enabled: true,
                        top: 0,
                        left: 0,
                        blur: 8,
                        opacity: 0.3,
                        color: '#60a5fa',
                    },
                },
                theme: {
                    mode: 'dark',
                },
                grid: {
                    borderColor: '#374151',
                },
                xaxis: {
                    axisBorder: {
                        color: '#4b5563',
                    },
                    axisTicks: {
                        color: '#4b5563',
                    },
                    labels: {
                        style: {
                            colors: '#9ca3af',
                        },
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#9ca3af',
                        },
                    },
                },
                tooltip: {
                    theme: 'dark',
                },
                legend: {
                    labels: {
                        colors: '#e5e7eb',
                    },
                },
                fill: {
                    opacity: 0.9,
                },
            };
        },

        mergeDeep(target, source) {
            const output = Object.assign({}, target);
            if (this.isObject(target) && this.isObject(source)) {
                Object.keys(source).forEach(key => {
                    if (this.isObject(source[key])) {
                        if (!(key in target)) {
                            Object.assign(output, { [key]: source[key] });
                        } else {
                            output[key] = this.mergeDeep(target[key], source[key]);
                        }
                    } else {
                        Object.assign(output, { [key]: source[key] });
                    }
                });
            }
            return output;
        },

        isObject(item) {
            return (item && typeof item === 'object' && !Array.isArray(item));
        },

        renderChart() {
            if (this.chart) {
                this.chart.destroy();
            }

            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts is not loaded. Please install it via npm: npm install apexcharts');
                return;
            }

            const chartOptions = {
                ...this.options,
                series: this.series,
            };

            this.chart = new ApexCharts(this.$refs.chart, chartOptions);
            this.chart.render();
        },

        updateChart(animate = true) {
            if (this.isUpdating) return;
            this.isUpdating = true;

            if (this.chart) {
                this.applyTheme();
                // Use ApexCharts updateOptions with animation parameter
                // Second parameter: redrawPaths - whether to redraw chart paths
                // Third parameter: animate - whether to animate the update
                this.chart.updateOptions(this.options, true, animate);
                this.chart.updateSeries(this.series, animate);
            } else {
                this.renderChart();
            }

            // Reset updating flag after animation completes
            setTimeout(() => {
                this.isUpdating = false;
            }, this.options?.chart?.animations?.dynamicAnimation?.speed || 350);
        },

        /**
         * Update only the series data for better performance with real-time updates.
         * This is more efficient than updating full options.
         * Sets isUpdating flag to prevent the $watch('series') watcher from triggering updateChart().
         */
        updateSeriesOnly(newSeries, animate = true) {
            if (this.chart && newSeries) {
                // Set flag to prevent watcher from triggering updateChart()
                this.isUpdating = true;
                this.series = newSeries;
                this.chart.updateSeries(this.series, animate);

                // Reset flag after animation completes
                setTimeout(() => {
                    this.isUpdating = false;
                }, this.options?.chart?.animations?.dynamicAnimation?.speed || 350);
            }
        },

        /**
         * Append data to existing series (useful for streaming data).
         * This method adds new data points without replacing existing data.
         * Syncs appended data to this.series so subsequent updateChart() calls
         * won't overwrite the appended points.
         *
         * @param {Array} newDataPoints - Array of objects with data arrays to append
         *                                e.g., [{data: [newValue1]}, {data: [newValue2]}]
         */
        appendData(newDataPoints) {
            if (this.chart && newDataPoints && Array.isArray(newDataPoints)) {
                // Set flag to prevent watcher from triggering updateChart()
                this.isUpdating = true;

                // Append to the chart
                this.chart.appendData(newDataPoints);

                // Sync appended data into this.series so future updates preserve it
                newDataPoints.forEach((pointObj, index) => {
                    if (this.series[index] && pointObj.data && Array.isArray(pointObj.data)) {
                        // Ensure series[index].data exists and is an array
                        if (!Array.isArray(this.series[index].data)) {
                            this.series[index].data = [];
                        }
                        // Append the new data points to the series
                        this.series[index].data = this.series[index].data.concat(pointObj.data);
                    }
                });

                // Reset flag after animation completes
                setTimeout(() => {
                    this.isUpdating = false;
                }, this.options?.chart?.animations?.dynamicAnimation?.speed || 350);
            }
        },

        watchTheme() {
            this.themeObserver = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'data-theme') {
                        this.isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                        this.updateChart();
                    }
                });
            });

            this.themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-theme'],
            });
        },

        watchLivewire() {
            this.$watch('series', () => {
                this.updateChart();
            });

            this.$watch('options', () => {
                this.updateChart();
            });
        },

        /**
         * Setup Livewire event listeners for programmatic chart updates.
         * Supports:
         * - chart-update: Full update of series and/or options
         * - chart-update-series: Update only series data
         * - chart-append-data: Append new data points (for streaming)
         */
        setupLivewireListeners() {
            // Store handlers so we can remove them in destroy()
            const chartUpdateHandler = (data) => {
                if (data.chartId && data.chartId !== this.chartId) return;

                if (data.series) {
                    this.series = data.series;
                }
                if (data.options) {
                    // Update base options with clean server state
                    // applyTheme() in updateChart() will rebuild this.options with theme overlays
                    this.updateBaseOptions(data.options);
                }
                this.updateChart(data.animate !== false);
            };

            const chartUpdateSeriesHandler = (data) => {
                if (data.chartId && data.chartId !== this.chartId) return;
                this.updateSeriesOnly(data.series, data.animate !== false);
            };

            const chartAppendDataHandler = (data) => {
                if (data.chartId && data.chartId !== this.chartId) return;
                this.appendData(data.data);
            };

            // Register listeners and store references for cleanup
            Livewire.on('chart-update', chartUpdateHandler);
            Livewire.on('chart-update-series', chartUpdateSeriesHandler);
            Livewire.on('chart-append-data', chartAppendDataHandler);

            this.livewireListeners = [
                { event: 'chart-update', handler: chartUpdateHandler },
                { event: 'chart-update-series', handler: chartUpdateSeriesHandler },
                { event: 'chart-append-data', handler: chartAppendDataHandler },
            ];
        },

        /**
         * Setup MutationObserver to watch for data attribute changes.
         * This allows Livewire polling (wire:poll) to update the chart
         * by detecting changes to data-chart-options and data-chart-series attributes.
         */
        setupDataAttributeObserver() {
            const observer = new MutationObserver((mutations) => {
                let shouldUpdate = false;
                let seriesChanged = false;
                let optionsChanged = false;

                mutations.forEach((mutation) => {
                    if (mutation.type === 'attributes') {
                        if (mutation.attributeName === 'data-chart-series') {
                            const newSeries = this.$el.getAttribute('data-chart-series');
                            if (newSeries) {
                                try {
                                    const parsedSeries = JSON.parse(newSeries);
                                    if (JSON.stringify(parsedSeries) !== JSON.stringify(this.series)) {
                                        this.series = parsedSeries;
                                        seriesChanged = true;
                                        shouldUpdate = true;
                                    }
                                } catch (e) {
                                    console.error('Failed to parse chart series:', e);
                                }
                            }
                        }

                        if (mutation.attributeName === 'data-chart-options') {
                            const newOptions = this.$el.getAttribute('data-chart-options');
                            if (newOptions) {
                                try {
                                    const parsedOptions = JSON.parse(newOptions);
                                    // Compare against current options to detect changes
                                    if (JSON.stringify(parsedOptions) !== JSON.stringify(this.baseOptions)) {
                                        // Update base options with clean server state
                                        // applyTheme() in updateChart() will rebuild this.options with theme overlays
                                        this.updateBaseOptions(parsedOptions);
                                        optionsChanged = true;
                                        shouldUpdate = true;
                                    }
                                } catch (e) {
                                    console.error('Failed to parse chart options:', e);
                                }
                            }
                        }
                    }
                });

                if (shouldUpdate) {
                    // If only series changed, use the more efficient updateSeriesOnly
                    if (seriesChanged && !optionsChanged) {
                        this.updateSeriesOnly(this.series, true);
                    } else {
                        this.updateChart(true);
                    }
                }
            });

            observer.observe(this.$el, {
                attributes: true,
                attributeFilter: ['data-chart-series', 'data-chart-options'],
            });

            // Store observer for cleanup
            this.dataObserver = observer;
        },

        destroy() {
            // Remove Livewire event listeners to prevent memory leak
            if (this.livewireListeners && this.livewireListeners.length > 0) {
                this.livewireListeners.forEach(({ event, handler }) => {
                    Livewire.off(event, handler);
                });
                this.livewireListeners = [];
            }

            // Disconnect theme observer to prevent memory leak
            if (this.themeObserver) {
                this.themeObserver.disconnect();
                this.themeObserver = null;
            }

            // Disconnect data attribute observer
            if (this.dataObserver) {
                this.dataObserver.disconnect();
                this.dataObserver = null;
            }

            // Destroy chart instance
            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }
        }
    }));
});
</script>
@endonce
