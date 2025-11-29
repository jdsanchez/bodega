<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Recibir Productos') }} - {{ $reception->reception_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Reception Info Summary -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Bodega</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $reception->warehouse->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Proveedor</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $reception->supplier->business_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Esperada</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $reception->expected_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Estado</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $reception->status_color }}">
                                {{ $reception->status_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receive Form -->
            <form action="{{ route('receptions.process', $reception) }}" method="POST" x-data="receiveForm()">
                @csrf

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Verificar Productos Recibidos</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                            Ingrese las cantidades realmente recibidas, la condición de cada producto y su ubicación en la bodega.
                        </p>

                        <div class="space-y-6">
                            @foreach($reception->items as $index => $item)
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="mb-4">
                                    <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $item->product_name }}</h4>
                                    <div class="mt-1 flex flex-wrap gap-2 text-sm text-gray-600 dark:text-gray-400">
                                        @if($item->sku)
                                        <span>SKU: {{ $item->sku }}</span>
                                        @endif
                                        @if($item->barcode)
                                        <span>• Código: {{ $item->barcode }}</span>
                                        @endif
                                        @if($item->productType)
                                        <span>• {{ $item->productType->name }}</span>
                                        @endif
                                    </div>
                                </div>

                                <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <!-- Expected Quantity (Read-only) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Cantidad Esperada
                                        </label>
                                        <div class="mt-1 px-3 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-gray-900 dark:text-gray-100">
                                            {{ number_format($item->expected_quantity, 2) }} {{ $item->unit }}
                                        </div>
                                    </div>

                                    <!-- Received Quantity -->
                                    <div>
                                        <label for="received_quantity_{{ $item->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Cantidad Recibida *
                                        </label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input type="number" 
                                                name="items[{{ $item->id }}][received_quantity]" 
                                                id="received_quantity_{{ $item->id }}"
                                                value="{{ old('items.'.$item->id.'.received_quantity', $item->received_quantity ?: $item->expected_quantity) }}"
                                                required min="0" step="0.01"
                                                x-model="items[{{ $index }}].received_quantity"
                                                @input="calculateDifference({{ $index }}, {{ $item->expected_quantity }})"
                                                class="flex-1 rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-sm">
                                                {{ $item->unit }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-xs" :class="items[{{ $index }}].difference === 0 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400'">
                                            <span x-text="items[{{ $index }}].difference === 0 ? 'Completo' : (items[{{ $index }}].difference > 0 ? 'Excedente: +' + items[{{ $index }}].difference.toFixed(2) : 'Faltante: ' + items[{{ $index }}].difference.toFixed(2))"></span>
                                        </p>
                                        @error('items.'.$item->id.'.received_quantity')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Condition -->
                                    <div>
                                        <label for="condition_{{ $item->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Condición *
                                        </label>
                                        <select name="items[{{ $item->id }}][condition]" 
                                            id="condition_{{ $item->id }}" 
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Seleccione...</option>
                                            <option value="bueno" {{ old('items.'.$item->id.'.condition', $item->condition) == 'bueno' ? 'selected' : '' }}>Bueno</option>
                                            <option value="dañado" {{ old('items.'.$item->id.'.condition', $item->condition) == 'dañado' ? 'selected' : '' }}>Dañado</option>
                                            <option value="defectuoso" {{ old('items.'.$item->id.'.condition', $item->condition) == 'defectuoso' ? 'selected' : '' }}>Defectuoso</option>
                                            <option value="incompleto" {{ old('items.'.$item->id.'.condition', $item->condition) == 'incompleto' ? 'selected' : '' }}>Incompleto</option>
                                        </select>
                                        @error('items.'.$item->id.'.condition')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Location -->
                                    <div>
                                        <label for="location_{{ $item->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Ubicación *
                                        </label>
                                        <input type="text" 
                                            name="items[{{ $item->id }}][location]" 
                                            id="location_{{ $item->id }}"
                                            value="{{ old('items.'.$item->id.'.location', $item->location) }}"
                                            required
                                            placeholder="ej: A-01-15"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('items.'.$item->id.'.location')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Item Notes -->
                                <div class="mt-4">
                                    <label for="notes_{{ $item->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Notas del Producto
                                    </label>
                                    <textarea name="items[{{ $item->id }}][notes]" 
                                        id="notes_{{ $item->id }}"
                                        rows="2"
                                        placeholder="Observaciones sobre el estado, embalaje, etc."
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('items.'.$item->id.'.notes', $item->notes) }}</textarea>
                                    @error('items.'.$item->id.'.notes')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- General Notes -->
                        <div class="mt-6">
                            <label for="reception_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Notas Generales de la Recepción
                            </label>
                            <textarea name="reception_notes" 
                                id="reception_notes"
                                rows="3"
                                placeholder="Observaciones generales sobre la recepción..."
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('reception_notes', $reception->notes) }}</textarea>
                            @error('reception_notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('receptions.show', $reception) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Cancelar
                            </a>

                            <div class="flex gap-3">
                                <!-- Reject Button -->
                                <button type="button" @click="showRejectModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Rechazar Recepción
                                </button>

                                <!-- Process Button -->
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Procesar Recepción
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Reject Modal -->
            <div x-data="{ showRejectModal: false }" x-show="showRejectModal" 
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto" 
                aria-labelledby="modal-title" 
                role="dialog" 
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showRejectModal" 
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                        aria-hidden="true"
                        @click="showRejectModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showRejectModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form action="{{ route('receptions.reject', $reception) }}" method="POST">
                            @csrf
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                            Rechazar Recepción
                                        </h3>
                                        <div class="mt-4">
                                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Razón del Rechazo *
                                            </label>
                                            <textarea name="rejection_reason" 
                                                id="rejection_reason"
                                                rows="4"
                                                required
                                                placeholder="Explique por qué se está rechazando esta recepción..."
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Confirmar Rechazo
                                </button>
                                <button type="button" @click="showRejectModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function receiveForm() {
            return {
                showRejectModal: false,
                items: [
                    @foreach($reception->items as $index => $item)
                    {
                        received_quantity: {{ $item->received_quantity ?: $item->expected_quantity }},
                        difference: 0
                    }{{ $loop->last ? '' : ',' }}
                    @endforeach
                ],
                calculateDifference(index, expected) {
                    const received = parseFloat(this.items[index].received_quantity) || 0;
                    this.items[index].difference = received - expected;
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
