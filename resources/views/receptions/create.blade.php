<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nueva Recepción de Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('receptions.store') }}" method="POST" x-data="receptionForm()">
                        @csrf

                        <!-- Header Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Warehouse -->
                            <div>
                                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Bodega *
                                </label>
                                <select name="warehouse_id" id="warehouse_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione una bodega</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
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
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                                <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number') }}"
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
                                <input type="text" name="purchase_order" id="purchase_order" value="{{ old('purchase_order') }}"
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
                                <input type="date" name="expected_date" id="expected_date" value="{{ old('expected_date') }}" required
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
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
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
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="'Producto ' + (index + 1)"></h4>
                                            <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Product Type -->
                                            <div>
                                                <label :for="'product_type_id_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Tipo de Producto
                                                </label>
                                                <select :name="'items[' + index + '][product_type_id]'" :id="'product_type_id_' + index"
                                                    x-model="item.product_type_id"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="">Seleccione un tipo</option>
                                                    @foreach($productTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Textile Type -->
                                            <div>
                                                <label :for="'textile_type_id_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Tipo de Textil
                                                </label>
                                                <select :name="'items[' + index + '][textile_type_id]'" :id="'textile_type_id_' + index"
                                                    x-model="item.textile_type_id"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="">Seleccione un tipo</option>
                                                    @foreach($textileTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Product Name -->
                                            <div>
                                                <label :for="'product_name_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Nombre del Producto *
                                                </label>
                                                <input type="text" :name="'items[' + index + '][product_name]'" :id="'product_name_' + index"
                                                    x-model="item.product_name" required
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <!-- SKU -->
                                            <div>
                                                <label :for="'sku_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    SKU
                                                </label>
                                                <input type="text" :name="'items[' + index + '][sku]'" :id="'sku_' + index"
                                                    x-model="item.sku"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <!-- Barcode -->
                                            <div>
                                                <label :for="'barcode_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Código de Barras
                                                </label>
                                                <input type="text" :name="'items[' + index + '][barcode]'" :id="'barcode_' + index"
                                                    x-model="item.barcode"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <!-- Expected Quantity -->
                                            <div>
                                                <label :for="'expected_quantity_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Cantidad Esperada *
                                                </label>
                                                <input type="number" :name="'items[' + index + '][expected_quantity]'" :id="'expected_quantity_' + index"
                                                    x-model="item.expected_quantity" required min="0" step="0.01"
                                                    @input="calculateItemTotal(index)"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <!-- Unit -->
                                            <div>
                                                <label :for="'unit_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Unidad *
                                                </label>
                                                <input type="text" :name="'items[' + index + '][unit]'" :id="'unit_' + index"
                                                    x-model="item.unit" required placeholder="ej: kg, m, unidades"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <!-- Unit Price -->
                                            <div>
                                                <label :for="'unit_price_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Precio Unitario *
                                                </label>
                                                <input type="number" :name="'items[' + index + '][unit_price]'" :id="'unit_price_' + index"
                                                    x-model="item.unit_price" required min="0" step="0.01"
                                                    @input="calculateItemTotal(index)"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <!-- Total Price -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Precio Total
                                                </label>
                                                <div class="mt-1 px-3 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-gray-700 dark:text-gray-300"
                                                    x-text="formatMoney(item.total_price)">
                                                </div>
                                            </div>

                                            <!-- Notes -->
                                            <div class="md:col-span-3">
                                                <label :for="'item_notes_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Notas del Producto
                                                </label>
                                                <textarea :name="'items[' + index + '][notes]'" :id="'item_notes_' + index"
                                                    x-model="item.notes" rows="2"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Total Summary -->
                            <div class="mt-6 bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-medium text-gray-900 dark:text-gray-100">Total Esperado:</span>
                                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" x-text="formatMoney(calculateTotal())"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-4 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <a href="{{ route('receptions.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Crear Recepción
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
                items: [
                    {
                        product_type_id: '',
                        textile_type_id: '',
                        product_name: '',
                        sku: '',
                        barcode: '',
                        expected_quantity: 0,
                        unit: '',
                        unit_price: 0,
                        total_price: 0,
                        notes: ''
                    }
                ],
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
                        total_price: 0,
                        notes: ''
                    });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                calculateItemTotal(index) {
                    const item = this.items[index];
                    item.total_price = (parseFloat(item.expected_quantity) || 0) * (parseFloat(item.unit_price) || 0);
                },
                calculateTotal() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.total_price) || 0), 0);
                },
                formatMoney(value) {
                    return 'Q' + parseFloat(value || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                }
            }
        }
    </script>
</x-app-layout>
