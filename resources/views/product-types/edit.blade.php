<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Tipo de Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('product-types.update', $productType) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Información General -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información del Producto</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Nombre -->
                                <div>
                                    <x-input-label for="name" :value="__('Nombre del Tipo de Producto')" class="dark:text-gray-200" />
                                    <x-text-input id="name" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="name" :value="old('name', $productType->name)" required autofocus placeholder="Ej: Tela de Algodón" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Descripción -->
                                <div>
                                    <x-input-label for="description" :value="__('Descripción')" class="dark:text-gray-200" />
                                    <textarea id="description" name="description" rows="4" class="block mt-2 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Describe las características del tipo de producto">{{ old('description', $productType->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <!-- Foto -->
                                <div>
                                    <x-input-label for="photo" :value="__('Foto del Producto')" class="dark:text-gray-200" />
                                    <div class="mt-2 flex items-center gap-4">
                                        <div id="photo-preview" class="w-20 h-20 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white overflow-hidden border-2 border-gray-200 dark:border-gray-600">
                                            @if($productType->photo)
                                                <img id="preview-image" src="{{ Storage::url($productType->photo) }}" alt="{{ $productType->name }}" class="w-full h-full object-cover">
                                            @else
                                                <svg id="placeholder-icon" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                                <img id="preview-image" src="" alt="" class="w-full h-full object-cover hidden">
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <input id="photo" class="block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none" type="file" name="photo" accept="image/*">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG (MAX. 2MB). Dejar en blanco para mantener la foto actual.</p>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                                </div>

                                <!-- Proveedor -->
                                <div>
                                    <x-input-label for="supplier_id" :value="__('Proveedor')" class="dark:text-gray-200" />
                                    <select id="supplier_id" name="supplier_id" class="block mt-2 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Seleccionar proveedor (opcional)</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $productType->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Información de Registro -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información de Registro</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Fecha de Creación</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                        {{ $productType->created_at->format('d/m/Y h:i A') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Última Actualización</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                        {{ $productType->updated_at->format('d/m/Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end gap-4 pt-4">
                            <a href="{{ route('product-types.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <x-primary-button>
                                Actualizar Tipo de Producto
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview de imagen
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImage = document.getElementById('preview-image');
                    const placeholderIcon = document.getElementById('placeholder-icon');
                    
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    if (placeholderIcon) {
                        placeholderIcon.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
