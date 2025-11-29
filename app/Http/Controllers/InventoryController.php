<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\ProductType;
use App\Models\Warehouse;
use App\Models\TextileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::with(['productType', 'warehouse', 'textileType', 'creator', 'updater']);

        // Filtro por búsqueda general (nombre, SKU, código de barras, ubicación)
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtro por bodega
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // Filtro por tipo de producto
        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

        // Filtro por tipo de textil
        if ($request->filled('textile_type_id')) {
            $query->where('textile_type_id', $request->textile_type_id);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por rango de precio
        if ($request->filled('min_price')) {
            $query->where('unit_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('unit_price', '<=', $request->max_price);
        }

        // Filtro por stock bajo
        if ($request->filled('low_stock') && $request->low_stock) {
            $query->lowStock();
        }

        // Ordenamiento
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $inventories = $query->paginate(15)->withQueryString();

        // Datos para los filtros
        $warehouses = Warehouse::orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $textileTypes = TextileType::orderBy('name')->get();

        // Estadísticas
        $stats = [
            'total_items' => Inventory::count(),
            'total_value' => Inventory::sum('total_price'),
            'low_stock_count' => Inventory::lowStock()->count(),
            'out_of_stock_count' => Inventory::outOfStock()->count(),
        ];

        return view('inventory.index', compact(
            'inventories',
            'warehouses',
            'productTypes',
            'textileTypes',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::active()->orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $textileTypes = TextileType::active()->orderBy('name')->get();

        return view('inventory.create', compact('warehouses', 'productTypes', 'textileTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_type_id' => 'required|exists:product_types,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'textile_type_id' => 'nullable|exists:textile_types,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|unique:inventories,barcode|max:255',
            'sku' => 'required|string|unique:inventories,sku|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:disponible,reservado,agotado,en_transito,dañado',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Procesar múltiples fotos
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('inventory', 'public');
                $photos[] = $path;
            }
        }
        $validated['photos'] = $photos;

        try {
            $inventory = Inventory::create($validated);

            return redirect()
                ->route('inventory.show', $inventory)
                ->with('success', 'Producto agregado al inventario exitosamente.');
        } catch (\Exception $e) {
            // Eliminar fotos si hubo error
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo);
            }

            return back()
                ->withInput()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        $inventory->load([
            'productType',
            'warehouse.manager',
            'textileType',
            'creator',
            'updater',
            'deleter'
        ]);

        return view('inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $warehouses = Warehouse::active()->orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        $textileTypes = TextileType::active()->orderBy('name')->get();

        return view('inventory.edit', compact('inventory', 'warehouses', 'productTypes', 'textileTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'product_type_id' => 'required|exists:product_types,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'textile_type_id' => 'nullable|exists:textile_types,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|max:255|unique:inventories,barcode,' . $inventory->id,
            'sku' => 'required|string|max:255|unique:inventories,sku,' . $inventory->id,
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:disponible,reservado,agotado,en_transito,dañado',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_photos' => 'nullable|array',
            'remove_photos.*' => 'string',
        ]);

        try {
            // Manejar fotos existentes
            $currentPhotos = $inventory->photos ?? [];

            // Eliminar fotos marcadas para remover
            if ($request->filled('remove_photos')) {
                foreach ($request->remove_photos as $photoToRemove) {
                    if (in_array($photoToRemove, $currentPhotos)) {
                        Storage::disk('public')->delete($photoToRemove);
                        $currentPhotos = array_values(array_diff($currentPhotos, [$photoToRemove]));
                    }
                }
            }

            // Agregar nuevas fotos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('inventory', 'public');
                    $currentPhotos[] = $path;
                }
            }

            $validated['photos'] = $currentPhotos;

            $inventory->update($validated);

            return redirect()
                ->route('inventory.show', $inventory)
                ->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        try {
            // El soft delete se maneja automáticamente en el modelo
            $inventory->delete();

            return redirect()
                ->route('inventory.index')
                ->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Restaurar un producto eliminado
     */
    public function restore($id)
    {
        $inventory = Inventory::withTrashed()->findOrFail($id);
        $inventory->restore();

        return redirect()
            ->route('inventory.show', $inventory)
            ->with('success', 'Producto restaurado exitosamente.');
    }

    /**
     * Eliminar permanentemente un producto
     */
    public function forceDelete($id)
    {
        $inventory = Inventory::withTrashed()->findOrFail($id);

        // Eliminar todas las fotos
        if ($inventory->photos) {
            foreach ($inventory->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $inventory->forceDelete();

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Producto eliminado permanentemente.');
    }

    /**
     * Exportar inventario a CSV
     */
    public function export(Request $request)
    {
        $query = Inventory::with(['productType', 'warehouse', 'textileType']);

        // Aplicar los mismos filtros que en index
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inventories = $query->get();

        $filename = 'inventario_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($inventories) {
            $file = fopen('php://output', 'w');
            
            // Encabezados
            fputcsv($file, [
                'SKU',
                'Nombre',
                'Tipo de Producto',
                'Bodega',
                'Tipo de Textil',
                'Cantidad',
                'Unidad',
                'Precio Unitario',
                'Precio Total',
                'Código de Barras',
                'Ubicación',
                'Estado',
                'Notas',
                'Creado por',
                'Fecha de Creación',
            ]);

            // Datos
            foreach ($inventories as $item) {
                fputcsv($file, [
                    $item->sku,
                    $item->name,
                    $item->productType->name ?? 'N/A',
                    $item->warehouse->name ?? 'N/A',
                    $item->textileType->name ?? 'N/A',
                    $item->quantity,
                    $item->unit,
                    $item->unit_price,
                    $item->total_price,
                    $item->barcode ?? '',
                    $item->location ?? '',
                    $item->status_label,
                    $item->creator->name ?? 'N/A',
                    $item->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Descargar plantilla CSV para importación masiva
     */
    public function template()
    {
        $filename = 'plantilla_importacion_inventario.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($file, [
                'product_name',
                'warehouse_id',
                'product_type_id',
                'textile_type_id',
                'sku',
                'barcode',
                'quantity',
                'unit',
                'unit_price',
                'location',
                'min_stock',
                'max_stock',
                'notes'
            ]);

            // Fila de ejemplo
            fputcsv($file, [
                'Tela de Algodón Premium',
                '1',
                '1',
                '1',
                'ALG-001',
                '7501234567890',
                '100',
                'metros',
                '25.50',
                'A-01-15',
                '20',
                '200',
                'Material de alta calidad para producción'
            ]);

            // Otra fila de ejemplo
            fputcsv($file, [
                'Botones de Metal',
                '1',
                '2',
                '',
                'BTN-MET-50',
                '',
                '500',
                'unidades',
                '0.50',
                'B-03-08',
                '100',
                '1000',
                'Botones dorados para camisas'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Importar productos masivamente desde CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            $imported = 0;
            $errors = [];
            $rowNumber = 1;

            if (in_array($extension, ['csv', 'txt'])) {
                // Procesar CSV
                if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                    // Leer encabezados
                    $headers = fgetcsv($handle);
                    $rowNumber++;

                    while (($data = fgetcsv($handle)) !== false) {
                        try {
                            $row = array_combine($headers, $data);
                            
                            // Validar campos requeridos
                            if (empty($row['product_name']) || empty($row['warehouse_id']) || empty($row['quantity']) || empty($row['unit_price'])) {
                                $errors[] = "Fila $rowNumber: Faltan campos requeridos (product_name, warehouse_id, quantity, unit_price)";
                                $rowNumber++;
                                continue;
                            }

                            // Validar que la bodega existe
                            $warehouse = Warehouse::find($row['warehouse_id']);
                            if (!$warehouse) {
                                $errors[] = "Fila $rowNumber: Bodega ID {$row['warehouse_id']} no existe";
                                $rowNumber++;
                                continue;
                            }

                            // Crear el producto en inventario
                            Inventory::create([
                                'product_name' => $row['product_name'],
                                'warehouse_id' => $row['warehouse_id'],
                                'product_type_id' => !empty($row['product_type_id']) ? $row['product_type_id'] : null,
                                'textile_type_id' => !empty($row['textile_type_id']) ? $row['textile_type_id'] : null,
                                'sku' => $row['sku'] ?? null,
                                'barcode' => $row['barcode'] ?? null,
                                'quantity' => $row['quantity'],
                                'unit' => $row['unit'] ?? 'unidades',
                                'unit_price' => $row['unit_price'],
                                'total_price' => $row['quantity'] * $row['unit_price'],
                                'location' => $row['location'] ?? null,
                                'min_stock' => !empty($row['min_stock']) ? $row['min_stock'] : 0,
                                'max_stock' => !empty($row['max_stock']) ? $row['max_stock'] : null,
                                'notes' => $row['notes'] ?? null,
                                'status' => 'activo',
                                'created_by' => auth()->id(),
                            ]);

                            $imported++;
                        } catch (\Exception $e) {
                            $errors[] = "Fila $rowNumber: " . $e->getMessage();
                        }
                        
                        $rowNumber++;
                    }

                    fclose($handle);
                }
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                // Para Excel, necesitaríamos una librería como PhpSpreadsheet
                // Por ahora, sugerimos convertir a CSV
                return back()->with('error', 'Por favor, convierta el archivo Excel a formato CSV para importar.');
            }

            $message = "$imported productos importados exitosamente.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " errores encontrados: " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= "... y " . (count($errors) - 5) . " más.";
                }
            }

            return redirect()->route('inventory.index')->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }
}
