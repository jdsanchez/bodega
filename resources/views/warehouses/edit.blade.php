<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('warehouses.index') }}" class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Bodega') }}
            </h2>
        </div>
    </x-slot>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('warehouses.update', $warehouse) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $warehouse->name) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Título -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Título <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title', $warehouse->title) }}" required
                                       placeholder="Ej: Bodega Principal, Almacén Central..."
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="mt-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Dirección <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" id="address" rows="2" required
                                      placeholder="Dirección completa de la bodega..."
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('address') border-red-500 @enderror">{{ old('address', $warehouse->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <!-- Teléfono -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Teléfono <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $warehouse->phone) }}" required
                                       placeholder="XXXX-XXXX"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- WhatsApp -->
                            <div>
                                <label for="whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    WhatsApp
                                </label>
                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $warehouse->whatsapp) }}"
                                       placeholder="+502 XXXX-XXXX"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('whatsapp') border-red-500 @enderror">
                                @error('whatsapp')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Correo Electrónico
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $warehouse->email) }}"
                                       placeholder="bodega@ejemplo.com"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Encargado (Select2) -->
                        <div class="mt-6">
                            <label for="manager_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Encargado
                            </label>
                            <select name="manager_id" id="manager_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                <option value="">Seleccione un encargado...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee['id'] }}" {{ old('manager_id', $warehouse->manager_id) == $employee['id'] ? 'selected' : '' }}>
                                        {{ $employee['text'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- URL de Google Maps -->
                        <div class="mt-6">
                            <label for="google_maps_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ubicación en Google Maps
                            </label>
                            <input type="url" name="google_maps_url" id="google_maps_url" value="{{ old('google_maps_url', $warehouse->google_maps_url) }}"
                                   placeholder="https://maps.google.com/..."
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('google_maps_url') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pega el enlace de Google Maps de la ubicación</p>
                            @error('google_maps_url')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto de la Bodega -->
                        <div class="mt-6">
                            <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Foto de la Bodega
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($warehouse->photo)
                                        <img id="photoPreview" src="{{ Storage::url($warehouse->photo) }}" alt="Foto actual" 
                                             class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600">
                                    @else
                                        <img id="photoPreview" src="" alt="Vista previa" 
                                             class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600 hidden">
                                    @endif
                                    <div id="photoPlaceholder" class="w-32 h-32 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white {{ $warehouse->photo ? 'hidden' : '' }}">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg,image/gif"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('photo') border-red-500 @enderror">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $warehouse->photo ? 'Dejar vacío para mantener la foto actual.' : 'Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.' }}
                                    </p>
                                    @error('photo')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Capacidad -->
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Capacidad (m²) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $warehouse->capacity) }}" required min="1"
                                       placeholder="Ej: 500"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('capacity') border-red-500 @enderror">
                                @error('capacity')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 @error('status') border-red-500 @enderror">
                                    @foreach(App\Models\Warehouse::getStatuses() as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $warehouse->status) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4 mt-8">
                            <a href="{{ route('warehouses.index') }}" 
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Actualizar Bodega
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('#manager_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Buscar encargado...',
                allowClear: true,
                width: '100%'
            });
        });

        // Preview de foto
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
    </script>
</x-app-layout>
