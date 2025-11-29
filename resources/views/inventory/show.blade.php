<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalles del Producto
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('inventory.edit', $inventory) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    Editar
                </a>
                <a href="{{ route('inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Información Principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Datos Básicos -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información del Producto</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventory->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">SKU</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $inventory->sku }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Producto</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventory->productType->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Bodega</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventory->warehouse->name }}</p>
                                    @if($inventory->warehouse->manager)
                                        <p class="text-xs text-gray-500">Encargado: {{ $inventory->warehouse->manager->name }}</p>
                                    @endif
                                </div>

                                @if($inventory->textileType)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Textil</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventory->textileType->name }}</p>
                                </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Cantidad</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventory->quantity }} {{ $inventory->unit }}</p>
                                    @if($inventory->is_low_stock)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⚠️ Stock bajo
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Precio Unitario</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventory->unit_price_formatted }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Precio Total</label>
                                    <p class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">{{ $inventory->total_price_formatted }}</p>
                                </div>

                                @if($inventory->barcode)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Código de Barras</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $inventory->barcode }}</p>
                                </div>
                                @endif

                                @if($inventory->location)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Ubicación</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventory->location }}</p>
                                </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Estado</label>
                                    <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $inventory->status_color }}">
                                        {{ $inventory->status_label }}
                                    </span>
                                </div>
                            </div>

                            @if($inventory->notes)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notas</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventory->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Fotos -->
                    @if($inventory->photos && count($inventory->photos) > 0)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Fotos del Producto</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($inventory->photos as $photo)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($photo) }}" alt="Foto del producto" class="w-full h-48 object-cover rounded-lg">
                                        <a href="{{ Storage::url($photo) }}" target="_blank" class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Auditoría -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Historial de Cambios</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Creado por</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $inventory->creator->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $inventory->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                @if($inventory->updater)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Última modificación</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $inventory->updater->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $inventory->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                @endif

                                @if($inventory->deleted_at)
                                <div>
                                    <label class="block text-sm font-medium text-red-500">Eliminado por</label>
                                    <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                                        {{ $inventory->deleter->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-red-500">
                                        {{ $inventory->deleted_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Acciones</h3>
                            
                            <div class="space-y-2">
                                <a href="{{ route('inventory.edit', $inventory) }}" class="block w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                    Editar Producto
                                </a>

                                <form action="{{ route('inventory.destroy', $inventory) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                                        Eliminar Producto
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
