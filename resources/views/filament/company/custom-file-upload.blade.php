
<div x-data="{ 
    uploading: false,
    progress: 0,
    fileName: @entangle('uploadedFileName').defer
}" 
x-init="
    $wire.on('clear-file-input', () => {
        $refs.fileInput.value = '';
        fileName = '';
    });
    
    $wire.on('file-removed', () => {
        $refs.fileInput.value = '';
    });
"
class="space-y-2">
    
    <!-- File Input -->
    <div class="relative">
        <input 
            type="file" 
            x-ref="fileInput"
            wire:model="uploadedFile"
            x-on:change="fileName = $event.target.files[0]?.name || ''"
            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.txt"
            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-500 file:text-white hover:file:bg-primary-600"
        />
        
        <!-- Upload Progress -->
        <div wire:loading wire:target="uploadedFile" class="mt-2">
            <div class="bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-300" 
                     style="width: 75%"></div>
            </div>
            <p class="text-sm text-gray-500 mt-1">Uploading...</p>
        </div>
    </div>

    <!-- File Preview -->
    @if($this->uploadedFile)
        <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg dark:bg-gray-800">
            <div class="flex items-center space-x-3">
                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $this->uploadedFileName ?: 'File uploaded' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Ready to send
                    </p>
                </div>
            </div>
            <button 
                type="button"
                wire:click="removeFile"
                class="text-red-500 hover:text-red-700 focus:outline-none"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <!-- Helper Text -->
    <p class="text-xs text-gray-500 dark:text-gray-400">
        Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF, TXT (Max: 10MB)
    </p>
    
    <!-- Error Messages -->
    @error('uploadedFile')
        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>