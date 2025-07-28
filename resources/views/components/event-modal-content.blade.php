<div>
	@if ($event)
		<div>
			<h3 class="text-2xl font-bold text-dark dark:text-white">{{ $event['title'] ?? $event['label'] }}</h3>

			@if (!empty($event['description']))
				<p class="mt-4 mb-4 text-base-content">{{ $event['description'] }}</p>
			@endif

			<div class="space-y-2">
				<div class="flex items-center space-x-2 text-base-content">
					<x-artisanpack-icon name="o-calendar" class="w-5 h-5" />
					<span><strong class="font-semibold">Starts:</strong> {{ $event['start_date'] ?? 'N/A' }}</span>
				</div>
				<div class="flex items-center space-x-2 text-base-content">
					<x-artisanpack-icon name="o-calendar" class="w-5 h-5" />
					<span><strong class="font-semibold">Ends:</strong> {{ $event['end_date'] ?? 'N/A' }}</span>
				</div>
				<div class="flex items-center space-x-2 text-base-content">
					<x-artisanpack-icon name="o-clock" class="w-5 h-5" />
					<span>
						<strong class="font-semibold">Time:</strong>
						@if (!empty($event['start_time']))
							{{ $event['start_time'] }}@if (!empty($event['end_time'])) - {{ $event['end_time'] }}@endif
						@else
							All day
						@endif
					</span>
				</div>
			</div>
		</div>
	@endif
</div>