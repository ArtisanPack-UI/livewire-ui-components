<div
   x-data="{
       progress: 0,
       indeterminate: false,
       cropper: null,
       imageCrop: null,
       croppingId: null,
       isDragOver: false,
       dragCounter: 0,
       dragFileCount: 0,

       init () {
           this.imageCrop = this.$refs.crop?.querySelector('img')

           this.$watch('progress', value => {
               this.indeterminate = value > 99
           })

           @if($withDragDrop)
           this.initDragDrop()
           @endif
       },
       initDragDrop() {
           // Prevent default drag behaviors
           ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
               document.addEventListener(eventName, (e) => {
                   e.preventDefault()
                   e.stopPropagation()
               }, false)
           })
       },
       handleDragEnter(e) {
           e.preventDefault()
           e.stopPropagation()
           this.dragCounter++
           if (this.dragCounter === 1) {
               this.isDragOver = true
               this.dragFileCount = e.dataTransfer.items ? e.dataTransfer.items.length : 0
           }
       },
       handleDragOver(e) {
           e.preventDefault()
           e.stopPropagation()
           e.dataTransfer.dropEffect = 'copy'
       },
       handleDragLeave(e) {
           e.preventDefault()
           e.stopPropagation()
           this.dragCounter--
           if (this.dragCounter === 0) {
               this.isDragOver = false
               this.dragFileCount = 0
           }
       },
       handleDrop(e) {
           e.preventDefault()
           e.stopPropagation()
           this.isDragOver = false
           this.dragCounter = 0
           this.dragFileCount = 0

           const files = e.dataTransfer.files
           if (files.length > 0) {
               this.processDroppedFiles(files)
           }
       },
       processDroppedFiles(files) {
           if (this.processing || this.indeterminate) {
               return
           }

           // Validate file types if accept attribute is present
           const acceptAttr = this.$refs.files.getAttribute('accept')
           const validFiles = []
           const invalidFiles = []

           Array.from(files).forEach(file => {
               if (acceptAttr && !this.isValidFileType(file, acceptAttr)) {
                   invalidFiles.push(file.name)
               } else {
                   validFiles.push(file)
               }
           })

           if (invalidFiles.length > 0) {
               alert(`The following files are not supported: ${invalidFiles.join(', ')}`)
           }

           if (validFiles.length > 0) {
               // Set the files to the input element
               const dt = new DataTransfer()
               validFiles.forEach(file => dt.items.add(file))
               this.$refs.files.files = dt.files

               // Trigger the change event to process the files
               this.progress = 1
           }
       },
       isValidFileType(file, acceptAttr) {
           const acceptedTypes = acceptAttr.split(',').map(type => type.trim())

           return acceptedTypes.some(type => {
               if (type.startsWith('.')) {
                   return file.name.toLowerCase().endsWith(type.toLowerCase())
               } else if (type.includes('/')) {
                   if (type.endsWith('/*')) {
                       return file.type.startsWith(type.slice(0, -1))
                   } else {
                       return file.type === type
                   }
               }
               return false
           })
       },
       get processing () {
           return this.progress > 0 && this.progress < 100
       },
       close() {
           $refs.maryCropModal.close()
           this.cropper?.destroy()
       },
       change() {
           if (this.processing) {
               return
           }

           this.$refs.files.click()
       },
       refreshImage() {

       },
       crop(id) {
           $refs.maryCropModal.showModal()

           this.cropper?.destroy()
           this.croppingId = id.split('-')[1]
           this.imageCrop.src = document.getElementById(id).src

           this.cropper = new Cropper(this.imageCrop, {{ $cropSetup() }});
       },
       removeMedia(uuid, url){
           this.indeterminate = true
           $wire.removeMedia(uuid, '{{ $modelName() }}', '{{ $libraryName() }}', url).then(() => this.indeterminate = false)
       },
       refreshMediaOrder(order){
           $wire.refreshMediaOrder(order, '{{ $libraryName() }}')
       },
       refreshMediaSources(){
           this.indeterminate = true
           $wire.refreshMediaSources('{{ $modelName() }}', '{{ $libraryName() }}').then(() => this.indeterminate = false)
       },
       async save() {
           $refs.maryCropModal.close();
           this.progress = 1

           this.cropper.getCroppedCanvas().toBlob((blob) => {
               @this.upload(this.croppingId, blob,
                   (uploadedFilename) => { this.refreshMediaSources() },
                   (error) => { this.progress = 0; },
                   (event) => { this.progress = event.detail.progress;  }
               )
           })
       }
    }"

   x-on:livewire-upload-progress="progress = $event.detail.progress;"
   x-on:livewire-upload-finish="refreshMediaSources()"

   @if($withDragDrop)
   @dragenter="handleDragEnter($event)"
   @dragover.prevent="handleDragOver($event)"
   @dragleave="handleDragLeave($event)"
   @drop.prevent="handleDrop($event)"
   :class="isDragOver && 'border-primary bg-primary/5 {{ $dragDropClass }}'"
   {{ $attributes->whereStartsWith('class')->class([
       'relative border-2 border-dashed border-base-300 rounded-lg transition-all duration-200'
   ]) }}
   @else
   {{ $attributes->whereStartsWith('class') }}
   @endif
>
   @if($withDragDrop)
   {{-- Drag and Drop Overlay --}}
   <div
       x-show="isDragOver"
       x-transition
       class="absolute inset-0 flex items-center justify-center bg-base-100/90 rounded-lg z-20"
       role="presentation"
       aria-hidden="true"
   >
       <div class="text-center">
           <svg class="mx-auto h-16 w-16 text-primary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
           </svg>
           <p class="text-xl font-medium text-primary"
              x-text="dragFileCount > 1 ? '{{ str_replace('{count}', '\' + dragFileCount + \'', $dragDropMultipleText) }}' : '{{ $dragDropText }}'">
           </p>
       </div>
   </div>
   @endif

   <fieldset class="fieldset py-0">
       {{-- STANDARD LABEL --}}
       @if($label)
           <legend class="fieldset-legend mb-0.5">
               {{ $label }}

               @if($attributes->get('required'))
                   <span class="text-error">*</span>
               @endif
           </legend>
       @endif

       {{-- PREVIEW AREA --}}
       <div
           :class="(processing || indeterminate) && 'opacity-50 pointer-events-none'"
           @class(["relative", "hidden" => $preview->count() == 0])
       >
           <div
               x-data="{ sortable: null }"
               x-init="sortable = new Sortable($el, { animation: 150, ghostClass: 'bg-base-300', filter: '.ignore-drag', onEnd: (ev) => refreshMediaOrder(sortable.toArray()) })"
               class="border-[length:var(--border)] border-base-content/10 border-dotted rounded-lg"
           >
               @foreach($preview as $key => $image)
                   <div class="relative border-b-base-content/10 border-b-[length:var(--border)] border-dotted last:border-none cursor-move hover:bg-base-200" data-id="{{ $image['uuid'] }}">
                       <div wire:key="preview-{{ $image['uuid'] }}" class="py-2 ps-16 pe-10 tooltip" data-tip="{{ $changeText }}">
                           {{-- IMAGE --}}
                           <img
                               src="{{ $image['url'] }}"
                               class="h-24 cursor-pointer border-2 border-base-content/10 rounded-lg hover:scale-105 transition-all ease-in-out"
                               @click="document.getElementById('file-{{ $uuid}}-{{ $key }}').click()"
                               id="image-{{ $modelName().'.'.$key  }}-{{ $uuid }}" />

                           {{-- VALIDATION --}}
                            @error($modelName().'.'.$key)
                               <div class="text-error label-text-alt p-1">{{ $validationMessage($message) }}</div>
                            @enderror

                           {{-- HIDDEN FILE INPUT --}}
                           <input
                               type="file"
                               id="file-{{ $uuid}}-{{ $key }}"
                               wire:model="{{ $modelName().'.'.$key  }}"
                               accept="{{ $attributes->get('accept') ?? $mimes }}"
                               class="hidden"
                               @change="progress = 1"
                               />
                       </div>

                       {{-- ACTIONS --}}
                       <div class="absolute flex flex-col gap-2 top-3 start-3 cursor-pointer  p-2 rounded-lg ignore-drag">
                           <x-artisanpack-button @click="removeMedia('{{ $image['uuid'] }}', '{{ $image['url'] }}')" @touchend.prevent="removeMedia('{{ $image['uuid'] }}', '{{ $image['url'] }}')" icon="o-x-circle" :tooltip="$removeText"  class="btn-sm btn-ghost btn-circle" />
                           <x-artisanpack-button @click="crop('image-{{ $modelName().'.'.$key  }}-{{ $uuid }}')" @touchend.prevent="crop('image-{{ $modelName().'.'.$key }}-{{ $uuid }}')" icon="o-scissors" :tooltip="$cropText"  class="btn-sm btn-ghost btn-circle" />
                       </div>
                   </div>
               @endforeach
           </div>
       </div>

       {{-- CROP MODAL --}}
       <div @click.prevent="" x-ref="crop" wire:ignore>
           <x-artisanpack-modal id="maryCropModal{{ $uuid }}" x-ref="maryCropModal" :title="$cropTitleText" separator class="backdrop-blur-sm" persistent @keydown.window.esc.prevent="" without-trap-focus>
               <img src="#" crossOrigin="Anonymous" />
               <x-slot:actions>
                   <x-artisanpack-button :label="$cropCancelText" @click="close()" />
                   <x-artisanpack-button :label="$cropSaveText" class="btn-primary" @click="save()" />
               </x-slot:actions>
           </x-artisanpack-modal>
       </div>

       {{-- PROGRESS BAR  --}}
       @if(! $hideProgress && $slot->isEmpty())
           <div class="-mt-2 h-1">
               <progress
                   x-cloak
                   :class="!processing && 'hidden'"
                   :value="progress"
                   max="100"
                   class="progress progress-primary h-1 w-full"></progress>

               <progress
                   x-cloak
                   :class="!indeterminate && 'hidden'"
                   class="progress progress-primary h-1 w-full"></progress>
           </div>
       @endif

       {{-- ADD FILES --}}
       <div @click="$refs.files.click()" class="btn btn-block" :class="(processing || indeterminate) && 'opacity-50 pointer-events-none'">
           <x-artisanpack-icon name="o-plus-circle" label="{{ $addFilesText }}" />
       </div>

       {{-- MAIN FILE INPUT --}}
       <input
           id="{{ $uuid }}"
           type="file"
           x-ref="files"
           class="file-input file-input-border file-input-primary hidden"
           wire:model="{{ $modelName() }}.*"
           accept="{{ $attributes->get('accept') ?? $mimes }}"
           @change="progress = 1"
           multiple />

       {{-- ERROR --}}
       @if (! $hideErrors)
           @error($libraryName())
               <div class="text-error">{{ $message }}</div>
           @enderror
       @endif

       {{-- HINT --}}
       @if($hint)
           <div class="fieldset-label">{{ $hint }}</div>
       @endif
  </fieldset>
</div>
