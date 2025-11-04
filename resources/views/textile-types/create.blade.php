<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('textile-types.index') }}" class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Nuevo Tipo de Textil') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('textile-types.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nombre -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Color Picker -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Color <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <input type="color" id="colorPicker" value="#ff0000"
                                           class="w-24 h-24 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer">
                                </div>
                                <div class="flex-1">
                                    <input type="text" name="color" id="colorValue" value="{{ old('color', 'rgba(255, 0, 0, 1)') }}" required readonly
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 @error('color') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Formato RGBA</p>
                                    
                                    <!-- Slider de opacidad -->
                                    <div class="mt-3">
                                        <label for="alphaSlider" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                            Opacidad: <span id="alphaValue">100</span>%
                                        </label>
                                        <input type="range" id="alphaSlider" min="0" max="100" value="100" 
                                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                                    </div>
                                </div>
                                <div id="colorPreview" class="w-24 h-24 rounded-lg border-2 border-gray-300 dark:border-gray-600 shadow-md" 
                                     style="background-color: rgba(255, 0, 0, 1);"></div>
                            </div>
                            @error('color')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Material -->
                        <div class="mb-6">
                            <label for="material" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Material <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="material" id="material" value="{{ old('material') }}" required
                                   placeholder="Ej: Algodón, Poliéster, Lino, Seda..."
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('material') border-red-500 @enderror">
                            @error('material')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto -->
                        <div class="mb-6">
                            <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Foto del Textil
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img id="photoPreview" src="" alt="Vista previa" 
                                         class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600 hidden">
                                    <div id="photoPlaceholder" class="w-32 h-32 rounded-lg bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg,image/gif"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('photo') border-red-500 @enderror">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</p>
                                    @error('photo')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descripción
                            </label>
                            <textarea name="description" id="description" rows="4"
                                      placeholder="Describe las características del textil..."
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado (Switch) -->
                        <div class="mb-6">
                            <label class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="status" id="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-14 h-8 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-6 peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Estado: <span id="statusLabel" class="font-semibold text-indigo-600 dark:text-indigo-400">Activo</span>
                                </span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Activa o desactiva este tipo de textil</p>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('textile-types.index') }}" 
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Guardar Tipo de Textil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Color picker functionality
        const colorPicker = document.getElementById('colorPicker');
        const colorValue = document.getElementById('colorValue');
        const colorPreview = document.getElementById('colorPreview');
        const alphaSlider = document.getElementById('alphaSlider');
        const alphaValue = document.getElementById('alphaValue');

        function hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }

        function updateColor() {
            const rgb = hexToRgb(colorPicker.value);
            const alpha = alphaSlider.value / 100;
            
            if (rgb) {
                const rgba = `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${alpha})`;
                colorValue.value = rgba;
                colorPreview.style.backgroundColor = rgba;
            }
        }

        colorPicker.addEventListener('input', updateColor);
        alphaSlider.addEventListener('input', function() {
            alphaValue.textContent = this.value;
            updateColor();
        });

        // Photo preview
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    const placeholder = document.getElementById('photoPlaceholder');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Status switch label
        const statusCheckbox = document.getElementById('status');
        const statusLabel = document.getElementById('statusLabel');
        
        statusCheckbox.addEventListener('change', function() {
            statusLabel.textContent = this.checked ? 'Activo' : 'Inactivo';
            statusLabel.className = this.checked 
                ? 'font-semibold text-indigo-600 dark:text-indigo-400' 
                : 'font-semibold text-gray-600 dark:text-gray-400';
        });
    </script>
</x-app-layout>
