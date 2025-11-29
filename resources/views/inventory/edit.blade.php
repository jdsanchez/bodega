<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Producto del Inventario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('inventory.update', $inventory) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre del Producto -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Producto *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $inventory->name) }}" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo de Producto -->
                            <div>
                                <label for="product_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Producto *</label>
                                <select id="product_type_id" name="product_type_id" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($productTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('product_type_id', $inventory->product_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_type_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bodega -->
                            <div>
                                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bodega *</label>
                                <select id="warehouse_id" name="warehouse_id" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Seleccione una bodega</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $inventory->warehouse_id) == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo de Textil (Opcional) -->
                            <div>
                                <label for="textile_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Textil</label>
                                <select id="textile_type_id" name="textile_type_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Ninguno</option>
                                    @foreach($textileTypes as $textile)
                                        <option value="{{ $textile->id }}" {{ old('textile_type_id', $inventory->textile_type_id) == $textile->id ? 'selected' : '' }}>{{ $textile->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- SKU -->
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU *</label>
                                <input type="text" id="sku" name="sku" value="{{ old('sku', $inventory->sku) }}" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @error('sku')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cantidad -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad *</label>
                                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $inventory->quantity) }}" step="0.01" min="0" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unidad -->
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unidad *</label>
                                <select id="unit" name="unit" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="unidades" {{ old('unit', $inventory->unit) == 'unidades' ? 'selected' : '' }}>Unidades</option>
                                    <option value="metros" {{ old('unit', $inventory->unit) == 'metros' ? 'selected' : '' }}>Metros</option>
                                    <option value="kilogramos" {{ old('unit', $inventory->unit) == 'kilogramos' ? 'selected' : '' }}>Kilogramos</option>
                                    <option value="litros" {{ old('unit', $inventory->unit) == 'litros' ? 'selected' : '' }}>Litros</option>
                                    <option value="cajas" {{ old('unit', $inventory->unit) == 'cajas' ? 'selected' : '' }}>Cajas</option>
                                </select>
                            </div>

                            <!-- Precio Unitario -->
                            <div>
                                <label for="unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio Unitario *</label>
                                <input type="number" id="unit_price" name="unit_price" value="{{ old('unit_price', $inventory->unit_price) }}" step="0.01" min="0" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @error('unit_price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Código de Barras -->
                            <div>
                                <label for="barcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código de Barras</label>
                                <input type="text" id="barcode" name="barcode" value="{{ old('barcode', $inventory->barcode) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @error('barcode')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ubicación -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ubicación en Bodega</label>
                                <input type="text" id="location" name="location" value="{{ old('location', $inventory->location) }}" placeholder="Ej: Estante A-3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado *</label>
                                <select id="status" name="status" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="disponible" {{ old('status', $inventory->status) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="reservado" {{ old('status', $inventory->status) == 'reservado' ? 'selected' : '' }}>Reservado</option>
                                    <option value="agotado" {{ old('status', $inventory->status) == 'agotado' ? 'selected' : '' }}>Agotado</option>
                                    <option value="en_transito" {{ old('status', $inventory->status) == 'en_transito' ? 'selected' : '' }}>En Tránsito</option>
                                    <option value="dañado" {{ old('status', $inventory->status) == 'dañado' ? 'selected' : '' }}>Dañado</option>
                                </select>
                            </div>

                            <!-- Notas -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notas</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $inventory->notes) }}</textarea>
                            </div>

                            <!-- Fotos -->
                            <div class="md:col-span-2">
                                <label for="photos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fotos del Producto</label>
                                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Puede seleccionar múltiples fotos (JPEG, PNG, JPG, WEBP, máx. 2MB cada una)</p>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 transition">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none transition">
                                Actualizar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
