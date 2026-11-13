<div>
	@if ( $isLoading )
		{{-- Loading State --}}
		@if ( $compact )
			<div class="flex items-center gap-4 animate-pulse">
				<div class="h-8 bg-base-300 rounded w-20"></div>
				<div class="h-8 bg-base-300 rounded w-20"></div>
			</div>
		@else
			<div class="card bg-base-100 shadow-sm">
				<div class="card-body">
					<div class="h-4 bg-base-300 rounded w-1/4 mb-4 animate-pulse"></div>
					<div class="grid grid-cols-3 gap-4">
						@foreach ( range( 1, 3 ) as $i )
							<div class="h-16 bg-base-300 rounded animate-pulse"></div>
						@endforeach
					</div>
					@if ( $showChart )
						<div class="h-24 bg-base-300 rounded mt-4 animate-pulse"></div>
					@endif
				</div>
			</div>
		@endif
	@else
		@if ( $compact )
			{{-- Compact View --}}
			<div class="flex items-center gap-6 text-sm">
				<div class="flex items-center gap-2">
					<x-artisanpack-icon name="o-eye" class="w-4 h-4 text-base-content/50" />
					<span class="font-medium">{{ $this->getFormattedPageViews() }}</span>
					<span class="text-base-content/50">{{ __( 'views' ) }}</span>
				</div>
				<div class="flex items-center gap-2">
					<x-artisanpack-icon name="o-users" class="w-4 h-4 text-base-content/50" />
					<span class="font-medium">{{ $this->getFormattedVisitors() }}</span>
					<span class="text-base-content/50">{{ __( 'visitors' ) }}</span>
				</div>
				<div class="flex items-center gap-2">
					<x-artisanpack-icon name="o-arrow-uturn-left" class="w-4 h-4 text-base-content/50" />
					<span class="font-medium">{{ $this->getFormattedBounceRate() }}</span>
					<span class="text-base-content/50">{{ __( 'bounce' ) }}</span>
				</div>
			</div>
		@else
			{{-- Full View --}}
			<x-artisanpack-card :title="__( 'Page Analytics' )" :subtitle="$path">
				<x-slot:menu>
					{{-- Date Range --}}
					<x-artisanpack-dropdown right>
						<x-slot:trigger>
							<x-artisanpack-button icon="o-calendar" :label="$this->getDateRangeLabel()" class="btn-ghost btn-xs" />
						</x-slot:trigger>
						@foreach ( $this->getDateRangePresets() as $preset => $label )
							<x-artisanpack-menu-item
								wire:click="setDateRange('{{ $preset }}')"
								:title="$label"
								:active="$dateRangePreset === $preset"
							/>
						@endforeach
					</x-artisanpack-dropdown>
					{{-- Refresh --}}
					<x-artisanpack-button
						wire:click="refreshData"
						class="btn-ghost btn-xs"
						icon="o-arrow-path"
						spinner
						:tooltip="__( 'Refresh' )"
					/>
				</x-slot:menu>

				{{-- Stats Grid --}}
				<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
					{{-- Page Views --}}
					<x-artisanpack-stat
						:title="__( 'Page Views' )"
						:value="$this->getFormattedPageViews()"
						icon="o-eye"
						color="text-primary"
						class="bg-base-200/50"
						size="sm"
					/>

					{{-- Visitors --}}
					<x-artisanpack-stat
						:title="__( 'Visitors' )"
						:value="$this->getFormattedVisitors()"
						icon="o-users"
						color="text-secondary"
						class="bg-base-200/50"
						size="sm"
					/>

					{{-- Bounce Rate --}}
					<x-artisanpack-stat
						:title="__( 'Bounce Rate' )"
						:value="$this->getFormattedBounceRate()"
						icon="o-arrow-uturn-left"
						color="text-accent"
						class="bg-base-200/50"
						size="sm"
					/>
				</div>

				{{-- Sparkline Chart --}}
				@if ( $showChart && $viewsOverTime->isNotEmpty() )
					<div
						style="height: 80px"
						x-data="{
							chart: null,
							init() {
								this.renderChart();
							},
							renderChart() {
								if (this.chart) {
									this.chart.destroy();
								}
								const ctx = this.$refs.canvas.getContext('2d');
								this.chart = new Chart(ctx, @js( $this->getSparklineConfig() ));
							}
						}"
						x-init="init"
						wire:ignore
					>
						<canvas x-ref="canvas"></canvas>
					</div>
				@endif
			</x-artisanpack-card>
		@endif
	@endif
</div>
