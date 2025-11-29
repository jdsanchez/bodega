<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Recepción') }} - {{ $reception->reception_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('receptions.update', $reception) }}" method="POST" x-data="receptionForm()">
                        @csrf
                        @method('PUT')

                        <!-- Header Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Reception Number (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Número de Recepción
                                </label>
                                <div class="mt-1 px-3 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-gray-900 dark:text-gray-100">
                                    {{ $reception->reception_number }}
                                </div>
                            </div>

                            <!-- Status (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Estado
                                </label>
                                <div class="mt-1 px-3 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $reception->status_color }}">
                                        {{ $reception->status_label }}
                                    </span>
                                </div>
                            </div>

                            <!-- Warehouse -->
                            <div>
                                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Bodega *
                                </label>
                                <select name="warehouse_id" id="warehouse_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione una bodega</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $reception->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Proveedor *
                                </label>
                                <select name="supplier_id" id="supplier_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione un proveedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $reception->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->business_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Invoice Number -->
                            <div>
                                <label for="invoice_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Número de Factura
                                </label>
                                <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $reception->invoice_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('invoice_number')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Purchase Order -->
                            <div>
                                <label for="purchase_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Orden de Compra
                                </label>
                                <input type="text" name="purchase_order" id="purchase_order" value="{{ old('purchase_order', $reception->purchase_order) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('purchase_order')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Expected Date -->
                            <div>
                                <label for="expected_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Fecha Esperada *
                                </label>
                                <input type="date" name="expected_date" id="expected_date" 
                                    value="{{ old('expected_date', $reception->expected_date->format('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('expected_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Notas
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $reception->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Items Section -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Productos</h3>
                                <button type="button" @click="addItem()"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    + Agregar Producto
                                </button>
                            </div>

                            <div class="space-y-4">
                                @foreach($reception->items as $existingIndex => $existingItem)
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700"
                                    x-data="{ itemId: {{ $existingItem->id }} }">
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Producto {{ $existingIndex + 1 }}</h4>
                                        <input type="hidden" name="existing_items[{{ $existingItem->id }}][id]" value="{{ $existingItem->id }}">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Product Type -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Producto</label>
                                            <select name="existing_items[{{ $existingItem->id }}][product_type_id]"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Seleccione un tipo</option>
                                                @foreach($productTypes as $type)
                                                    <option value="{{ $type->id }}" {{ $existingItem->product_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Textile Type -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Textil</label>
                                            <select name="existing_items[{{ $existingItem->id }}][textile_type_id]"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Seleccione un tipo</option>
                                                @foreach($textileTypes as $type)
                                                    <option value="{{ $type->id }}" {{ $existingItem->textile_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Product Name -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Producto *</label>
                                            <input type="text" name="existing_items[{{ $existingItem->id }}][product_name]"
                                                value="{{ $existingItem->product_name }}" required
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <!-- SKU -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
                                            <input type="text" name="existing_items[{{ $existingItem->id }}][sku]"
                                                value="{{ $existingItem->sku }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <!-- Barcode -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código de Barras</label>
                                            <input type="text" name="existing_items[{{ $existingItem->id }}][barcode]"
                                                value="{{ $existingItem->barcode }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <!-- Expected Quantity -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad Esperada *</label>
                                            <input type="number" name="existing_items[{{ $existingItem->id }}][expected_quantity]"
                                                value="{{ $existingItem->expected_quantity }}" required min="0" step="0.01"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <!-- Unit -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unidad *</label>
                                            <input type="text" name="existing_items[{{ $existingItem->id }}][unit]"
                                                value="{{ $existingItem->unit }}" required placeholder="ej: kg, m, unidades"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <!-- Unit Price -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio Unitario *</label>
                                            <input type="number" name="existing_items[{{ $existingItem->id }}][unit_price]"
                                                value="{{ $existingItem->unit_price }}" required min="0" step="0.01"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <!-- Notes -->
                                        <div class="md:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notas del Producto</label>
                                            <textarea name="existing_items[{{ $existingItem->id }}][notes]" rows="2"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $existingItem->notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <!-- New Items -->
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border-2 border-blue-200 dark:border-blue-800">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-600 text-white">NUEVO</span>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="'Producto ' + ({{ count($reception->items) }} + index + 1)"></h4>
                                            </div>
                                            <button type="button" @click="removeItem(index)"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Producto</label>
                                                <select :name="'items[' + index + '][product_type_id]'" x-model="item.product_type_id"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="">Seleccione un tipo</option>
                                                    @foreach($productTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Textil</label>
                                                <select :name="'items[' + index + '][textile_type_id]'" x-model="item.textile_type_id"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="">Seleccione un tipo</option>
                                                    @foreach($textileTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Producto *</label>
                                                <input type="text" :name="'items[' + index + '][product_name]'" x-model="item.product_name" required
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
                                                <input type="text" :name="'items[' + index + '][sku]'" x-model="item.sku"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código de Barras</label>
                                                <input type="text" :name="'items[' + index + '][barcode]'" x-model="item.barcode"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad Esperada *</label>
                                                <input type="number" :name="'items[' + index + '][expected_quantity]'" x-model="item.expected_quantity" required min="0" step="0.01"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unidad *</label>
                                                <input type="text" :name="'items[' + index + '][unit]'" x-model="item.unit" required placeholder="ej: kg, m, unidades"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio Unitario *</label>
                                                <input type="number" :name="'items[' + index + '][unit_price]'" x-model="item.unit_price" required min="0" step="0.01"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <div class="md:col-span-3">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notas del Producto</label>
                                                <textarea :name="'items[' + index + '][notes]'" x-model="item.notes" rows="2"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-4 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <a href="{{ route('receptions.show', $reception) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Actualizar Recepción
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function receptionForm() {
            return {
                items: [],
                addItem() {
                    this.items.push({
                        product_type_id: '',
                        textile_type_id: '',
                        product_name: '',
                        sku: '',
                        barcode: '',
                        expected_quantity: 0,
                        unit: '',
                        unit_price: 0,
                        notes: ''
                    });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout>
