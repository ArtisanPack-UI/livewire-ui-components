/**
 * ArtisanPack UI Sparkline Alpine Component
 *
 * Registers the Alpine.js component for sparkline charts using ApexCharts.
 * This file is automatically loaded by the livewire-ui-components package.
 */
document.addEventListener( 'alpine:init', () => {
	Alpine.data( 'artisanpackSparkline', ( config ) => ( {
		chart: null,
		options: config.initialOptions,
		series: config.initialSeries,
		chartId: config.chartId,
		isUpdating: false,
		pendingUpdate: null,
		dataObserver: null,

		init() {
			this.renderChart();
			this.watchLivewire();
			this.setupDataAttributeObserver();
		},

		renderChart() {
			if ( this.chart ) {
				this.chart.destroy();
			}

			if ( typeof ApexCharts === 'undefined' ) {
				console.error( 'ApexCharts is not loaded. Please install it via npm: npm install apexcharts' );
				return;
			}

			const chartOptions = {
				...this.options,
				series: this.series,
			};

			this.chart = new ApexCharts( this.$refs.sparkline, chartOptions );
			this.chart.render();
		},

		updateChart( animate = true ) {
			// If already updating, queue the update
			if ( this.isUpdating ) {
				this.pendingUpdate = {
					options: { ...this.options },
					series: [ ...this.series ],
					animate: animate
				};
				return;
			}

			this.isUpdating = true;

			if ( this.chart ) {
				this.chart.updateOptions( this.options, true, animate );
				this.chart.updateSeries( this.series, animate );
			} else {
				this.renderChart();
			}

			// Reset updating flag and apply pending updates after animation completes
			setTimeout( () => {
				this.isUpdating = false;

				// Apply pending update if one exists
				if ( this.pendingUpdate ) {
					const pending = this.pendingUpdate;
					this.pendingUpdate = null;

					// Update internal state
					this.options = pending.options;
					this.series = pending.series;

					// Apply the queued update
					this.updateChart( pending.animate );
				}
			}, 350 );
		},

		watchLivewire() {
			this.$watch( 'series', () => {
				this.updateChart();
			} );

			this.$watch( 'options', () => {
				this.updateChart();
			} );
		},

		/**
		 * Setup MutationObserver to watch for data attribute changes.
		 * This allows Livewire polling (wire:poll) to update the sparkline
		 * by detecting changes to data-chart-options and data-chart-series attributes.
		 */
		setupDataAttributeObserver() {
			const observer = new MutationObserver( ( mutations ) => {
				let shouldUpdate    = false;
				let seriesChanged   = false;
				let optionsChanged  = false;

				mutations.forEach( ( mutation ) => {
					if ( mutation.type === 'attributes' ) {
						if ( mutation.attributeName === 'data-chart-series' ) {
							const newSeries = this.$el.getAttribute( 'data-chart-series' );
							if ( newSeries ) {
								try {
									const parsedSeries = JSON.parse( newSeries );
									if ( JSON.stringify( parsedSeries ) !== JSON.stringify( this.series ) ) {
										this.series     = parsedSeries;
										seriesChanged   = true;
										shouldUpdate    = true;
									}
								} catch ( e ) {
									console.error( 'Failed to parse sparkline series:', e );
								}
							}
						}

						if ( mutation.attributeName === 'data-chart-options' ) {
							const newOptions = this.$el.getAttribute( 'data-chart-options' );
							if ( newOptions ) {
								try {
									const parsedOptions = JSON.parse( newOptions );
									if ( JSON.stringify( parsedOptions ) !== JSON.stringify( this.options ) ) {
										this.options    = parsedOptions;
										optionsChanged  = true;
										shouldUpdate    = true;
									}
								} catch ( e ) {
									console.error( 'Failed to parse sparkline options:', e );
								}
							}
						}
					}
				} );

				if ( shouldUpdate ) {
					this.updateChart( true );
				}
			} );

			observer.observe( this.$el, {
				attributes: true,
				attributeFilter: [ 'data-chart-series', 'data-chart-options' ],
			} );

			// Store observer for cleanup
			this.dataObserver = observer;
		},

		destroy() {
			// Disconnect data attribute observer
			if ( this.dataObserver ) {
				this.dataObserver.disconnect();
				this.dataObserver = null;
			}

			// Destroy chart instance
			if ( this.chart ) {
				this.chart.destroy();
				this.chart = null;
			}
		}
	} ) );
} );
