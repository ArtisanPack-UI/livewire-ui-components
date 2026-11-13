<div>
	<x-artisanpack-modal
		wire:model="isOpen"
		:title="__('Manage Tags')"
		class="w-full"
	>
		{{-- Form Section --}}
		<div class="mb-6">
			<x-artisanpack-card variant="primary">
				<x-artisanpack-heading level="3" class="mb-4">
					@if($isEditing)
						{{ __('Edit Tag') }}
					@else
						{{ __('Create New Tag') }}
					@endif
				</x-artisanpack-heading>

				<form wire:submit.prevent="save" class="space-y-4">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<x-artisanpack-input
							wire:model.live="form.name"
							:label="__('Tag Name')"
							:placeholder="__('Enter tag name')"
							required
						/>

						<x-artisanpack-input
							wire:model="form.slug"
							:label="__('Slug')"
							:placeholder="__('Auto-generated from name')"
							:help="__('Used in URLs (automatically generated from name)')"
							required
						/>
					</div>

					<x-artisanpack-textarea
						wire:model="form.description"
						:label="__('Description')"
						:placeholder="__('Enter tag description (optional)')"
						rows="3"
					/>

					<div class="flex items-center justify-end gap-2">
						@if($isEditing)
							<x-artisanpack-button
								type="button"
								wire:click="cancelEdit"
								variant="primary"
								size="sm"
							>
								{{ __('Cancel') }}
							</x-artisanpack-button>
						@endif

						<x-artisanpack-button
							type="submit"
							variant="primary"
							size="sm"
						>
							@if($isEditing)
								{{ __('Update Tag') }}
							@else
								{{ __('Create Tag') }}
							@endif
						</x-artisanpack-button>
					</div>
				</form>
			</x-artisanpack-card>
		</div>

		{{-- Tags List --}}
		<div>
			<x-artisanpack-heading level="3" class="mb-4">
				{{ __('Existing Tags') }}
			</x-artisanpack-heading>

			@if($tags->isEmpty())
				<div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
					<x-artisanpack-icon name="fas.tags" class="w-12 h-12 mx-auto mb-2 opacity-50"/>
					<p>{{ __('No tags created yet') }}</p>
				</div>
			@else
				<div class="space-y-2 max-h-96 overflow-y-auto">
					@foreach($tags as $tag)
						<div
							class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
							<div class="flex-1">
								<div class="flex items-center gap-2">
									<x-artisanpack-icon name="fas.tag"
														class="w-4 h-4 text-zinc-500 dark:text-zinc-400"/>
									<span class="font-medium text-zinc-900 dark:text-zinc-100">
													{{ $tag->name }}
												</span>
									<span
										class="px-2 py-0.5 text-xs font-mono bg-zinc-200 dark:bg-zinc-600 text-zinc-700 dark:text-zinc-300 rounded">
													{{ $tag->slug }}
												</span>
								</div>
								@if($tag->description)
									<p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1 ml-6">
										{{ $tag->description }}
									</p>
								@endif
								<div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 ml-6">
									{{ __(':count media items', ['count' => $tag->mediaCount()]) }}
								</div>
							</div>

							<div class="flex items-center gap-2 ml-4">
								<x-artisanpack-button
									type="button"
									wire:click="edit({{ $tag->id }})"
									variant="primary"
									size="sm"
								>
									<x-artisanpack-icon name="fas.edit" class="w-3 h-3"/>
								</x-artisanpack-button>

								<x-artisanpack-button
									type="button"
									wire:click="delete({{ $tag->id }})"
									wire:confirm="{{ __('Are you sure you want to delete this tag? It will be removed from all media items.') }}"
									variant="error"
									size="sm"
								>
									<x-artisanpack-icon name="fas.trash" class="w-3 h-3"/>
								</x-artisanpack-button>
							</div>
						</div>
					@endforeach
				</div>
			@endif
		</div>

		<x-slot:actions>
			<x-artisanpack-button
				type="button"
				wire:click="close"
				variant="primary"
			>
				{{ __('Close') }}
			</x-artisanpack-button>
		</x-slot:actions>
	</x-artisanpack-modal>
</div>
