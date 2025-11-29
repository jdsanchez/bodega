<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalle de Recepción') }} - {{ $reception->reception_number }}
            </h2>
            <div class="flex gap-2">
                @if($reception->status === 'pendiente' || $reception->status === 'en_revision')
                    <a href="{{ route('receptions.receive', $reception) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Recibir Productos
                    </a>
                @endif
                @if($reception->status === 'pendiente')
                    <a href="{{ route('receptions.edit', $reception) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Editar
                    </a>
                @endif
                <a href="{{ route('receptions.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Reception Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información de la Recepción</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Número de Recepción</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $reception->reception_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Estado</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $reception->status_color }}">
                                {{ $reception->status_label }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Porcentaje de Completitud</p>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $reception->completion_percentage }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($reception->completion_percentage, 1) }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Bodega</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $reception->warehouse->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Proveedor</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $reception->supplier->business_name }}</p>
                        </div>
                        @if($reception->invoice_number)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Número de Factura</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $reception->invoice_number }}</p>
                        </div>
                        @endif
                        @if($reception->purchase_order)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Orden de Compra</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $reception->purchase_order }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Esperada</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $reception->expected_date->format('d/m/Y') }}</p>
                        </div>
                        @if($reception->reception_date)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Recepción</p>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $reception->reception_date->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Esperado</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $reception->formatted_total_expected }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Recibido</p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $reception->formatted_total_received }}</p>
                        </div>
                    </div>

                    @if($reception->notes)
                    <div class="mt-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Notas</p>
                        <p class="text-base text-gray-900 dark:text-gray-100 mt-1">{{ $reception->notes }}</p>
                    </div>
                    @endif

                    @if($reception->rejection_reason)
                    <div class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm font-medium text-red-800 dark:text-red-400">Razón de Rechazo</p>
                        <p class="text-base text-red-700 dark:text-red-300 mt-1">{{ $reception->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Reception Items -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Productos</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKU/Código</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cant. Esperada</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cant. Recibida</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Condición</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">P. Unitario</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($reception->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->product_name }}</p>
                                            @if($item->productType)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->productType->name }}</p>
                                            @endif
                                            @if($item->textileType)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->textileType->name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->sku)
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $item->sku }}</p>
                                        @endif
                                        @if($item->barcode)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->barcode }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($item->expected_quantity, 2) }} {{ $item->unit }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-medium {{ $item->is_complete ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                            {{ number_format($item->received_quantity, 2) }} {{ $item->unit }}
                                        </span>
                                        @if(!$item->is_complete)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            ({{ $item->quantity_difference > 0 ? '+' : '' }}{{ number_format($item->quantity_difference, 2) }})
                                        </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->condition)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $item->condition_color }}">
                                            {{ $item->condition_label }}
                                        </span>
                                        @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-100">Q{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-100">Q{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @if($item->notes)
                                <tr>
                                    <td colspan="7" class="px-6 py-2 bg-gray-50 dark:bg-gray-900/50">
                                        <p class="text-xs text-gray-600 dark:text-gray-400"><strong>Nota:</strong> {{ $item->notes }}</p>
                                    </td>
                                </tr>
                                @endif
                                @if($item->location)
                                <tr>
                                    <td colspan="7" class="px-6 py-2 bg-blue-50 dark:bg-blue-900/20">
                                        <p class="text-xs text-blue-700 dark:text-blue-400"><strong>Ubicación:</strong> {{ $item->location }}</p>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No hay productos en esta recepción.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Audit Trail -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Historial de Auditoría</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Recepción Creada</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Por: {{ $reception->creator->name }} • {{ $reception->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($reception->received_by)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Productos Recibidos</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Por: {{ $reception->receiver->name }} • {{ $reception->reception_date ? $reception->reception_date->format('d/m/Y H:i') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($reception->approved_by)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Recepción Aprobada e Inventario Creado</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Por: {{ $reception->approver->name }} • {{ $reception->approved_at ? $reception->approved_at->format('d/m/Y H:i') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Última Actualización</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $reception->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
