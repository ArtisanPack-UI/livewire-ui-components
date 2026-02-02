<div
   x-data="{
       progress: 0,
       cropper: null,
       justCropped: false,
       fileChanged: false,
       imagePreview: null,
       imageCrop: null,
       originalImageUrl: null,
       cropAfterChange: {{ json_encode($cropAfterChange) }},
       file: $wire.entangle('{{ $attributes->wire('model')->value() }}'),
       isDragOver: false,
       dragCounter: 0,
       init () {
           this.imagePreview = this.$refs.preview?.querySelector('img')
           this.imageCrop = this.$refs.crop?.querySelector('img')
           this.originalImageUrl = this.imagePreview?.src

           this.$watch('progress', value => {
               if (value == 100 && this.cropAfterChange && !this.justCropped) {
                   this.crop()
               }
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
           }
       },
       handleDrop(e) {
           e.preventDefault()
           e.stopPropagation()
           this.isDragOver = false
           this.dragCounter = 0

           const files = e.dataTransfer.files
           if (files.length > 0) {
               this.processDroppedFiles(files)
           }
       },
       processDroppedFiles(files) {
           if (this.processing) {
               return
           }

           const file = files[0] // Take first file for single file component

           // Validate file type if accept attribute is present
           const acceptAttr = this.$refs.file.getAttribute('accept')
           if (acceptAttr && !this.isValidFileType(file, acceptAttr)) {
               alert('File type not supported')
               return
           }

           // Set the file to the input element
           const dt = new DataTransfer()
           dt.items.add(file)
           this.$refs.file.files = dt.files

           // Trigger the change event to process the file
           this.refreshImage()
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
           $refs.maryCrop.close()
           this.cropper?.destroy()
       },
       change() {
           if (this.processing) {
               return
           }

           this.$refs.file.click()
       },
       refreshImage() {
           this.progress = 1
           this.justCropped = false

           if (this.imagePreview?.src) {
               this.imagePreview.src = URL.createObjectURL(this.$refs.file.files[0])
               this.imageCrop.src = this.imagePreview.src
           }
       },
       crop() {
           $refs.maryCrop.showModal()
           this.cropper?.destroy()

           this.cropper = new Cropper(this.imageCrop, {{ $cropSetup() }});
       },
       revert() {
            $wire.$removeUpload('{{ $attributes->wire('model')->value }}', this.file.split('livewire-file:').pop(), () => {
               this.imagePreview.src = this.originalImageUrl
            })
       },
       async save() {
           $refs.maryCrop.close();

           this.progress = 1
           this.justCropped = true

           this.imagePreview.src = this.cropper.getCroppedCanvas().toDataURL()
           this.imageCrop.src = this.imagePreview.src

           this.cropper.getCroppedCanvas().toBlob((blob) => {
               blob.name = $refs.file.files[0].name
               @this.upload('{{ $attributes->wire('model')->value }}', blob,
                   (uploadedFilename) => {  },
                   (error) => {  },
                   (event) => { this.progress = event.detail.progress }
               )
           }, '{{ $cropMimeType }}')
       }
    }"

   x-on:livewire-upload-progress="progress = $event.detail.progress;"

   @if($withDragDrop)
   @dragenter="handleDragEnter($event)"
   @dragover.prevent="handleDragOver($event)"
   @dragleave="handleDragLeave($event)"
   @drop.prevent="handleDrop($event)"
   :class="isDragOver && 'border-primary bg-primary/10 {{ $dragDropClass }}'"
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
       class="absolute inset-0 flex items-center justify-center bg-base-100/90 rounded-lg z-10"
       role="presentation"
       aria-hidden="true"
   >
       <div class="text-center">
           <svg class="mx-auto h-12 w-12 text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
           </svg>
           <p class="text-lg font-medium text-primary">{{ $dragDropText }}</p>
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

       {{-- PROGRESS BAR  --}}
       @if(! $hideProgress && $slot->isEmpty())
           <progress
               x-cloak
               max="100"
               :value="progress"
               :class="!processing && 'hidden'"
               class="progress h-1 absolute -mt-2 w-56"></progress>
       @endif

       {{-- INPUT --}}
       <input
           id="{{ $uuid }}"
           type="file"
           x-ref="file"
           @change="refreshImage()"

           {{
               $attributes->whereDoesntStartWith('class')->class([
                   "file-input w-full",
                   "!file-input-error" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError,
                   "hidden" => $slot->isNotEmpty()
               ])
           }}
       />

       @if ($slot->isNotEmpty())
           <!-- PREVIEW AREA -->
           <div x-ref="preview" class="relative flex">
               <div
                   wire:ignore
                   @click="change()"
                   :class="processing && 'opacity-50 pointer-events-none'"
                   class="cursor-pointer hover:scale-105 transition-all tooltip"
                   data-tip="{{ $changeText }}"
               >
                   {{ $slot }}
               </div>
               <!-- PROGRESS -->
               <div
                   x-cloak
                   :style="`--value:${progress}; --size:1.5rem; --thickness: 4px;`"
                   :class="!processing && 'hidden'"
                   class="radial-progress text-success absolute top-5 start-5 bg-neutral"
                   role="progressbar"
               ></div>
           </div>

           <!-- CROP MODAL -->
           <div @click.prevent="" x-ref="crop" wire:ignore>
               <x-artisanpack-modal id="maryCrop{{ $uuid }}" x-ref="maryCrop" :title="$cropTitleText" separator class="backdrop-blur-sm" persistent @keydown.window.esc.prevent="" without-trap-focus>
                   <img src="" />
                   <x-slot:actions>
                       <x-artisanpack-button :label="$cropCancelText" @click="close()" />
                       <x-artisanpack-button :label="$cropSaveText" class="btn-primary" @click="save()" x-bind:disabled="processing" />
                   </x-slot:actions>
               </x-artisanpack-modal>
           </div>
       @endif

       {{-- ERROR --}}
       @if(!$omitError && $errors->has($errorFieldName()))
           @foreach($errors->get($errorFieldName()) as $message)
               @foreach(Arr::wrap($message) as $line)
                   <div class="{{ $errorClass }}" x-classes="text-error">{{ $line }}</div>
                   @break($firstErrorOnly)
               @endforeach
               @break($firstErrorOnly)
           @endforeach
       @endif

       {{-- MULTIPLE --}}
       @error($modelName().'.*')
           <div class="text-error" x-classes="text-error">{{ $message }}</div>
       @enderror

       {{-- HINT --}}
       @if($hint)
           <div class="{{ $hintClass }}" x-classes="fieldset-label">{{ $hint }}</div>
       @endif
   </fieldset>
</div>
