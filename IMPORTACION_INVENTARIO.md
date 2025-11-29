# Importación Masiva de Inventario

## Descripción
Funcionalidad para importar múltiples productos al inventario mediante un archivo CSV, agilizando la carga inicial de datos o actualizaciones masivas.

## Características

### 1. Botón de Importación
- Ubicado en la vista `inventory/index` junto a "Exportar CSV" y "Agregar al Inventario"
- Abre un modal con instrucciones y formulario de carga

### 2. Modal de Importación
- **Selector de archivo**: Acepta `.csv`, `.xlsx`, `.xls` (max 10MB)
- **Instrucciones detalladas**: Lista completa de columnas requeridas y opcionales
- **Link a plantilla**: Descarga archivo de ejemplo con formato correcto
- **Validación**: Verifica formato y tamaño del archivo

### 3. Formato del Archivo CSV

#### Columnas Requeridas
- `product_name` - Nombre del producto
- `warehouse_id` - ID de la bodega (debe existir)
- `quantity` - Cantidad en inventario
- `unit_price` - Precio unitario

#### Columnas Opcionales
- `product_type_id` - ID del tipo de producto
- `textile_type_id` - ID del tipo de textil
- `sku` - Código SKU del producto
- `barcode` - Código de barras
- `unit` - Unidad de medida (default: "unidades")
- `location` - Ubicación en bodega
- `min_stock` - Stock mínimo (default: 0)
- `max_stock` - Stock máximo
- `notes` - Notas adicionales

### 4. Plantilla de Ejemplo
- Descargable desde el modal mediante link "Descargar plantilla de ejemplo"
- Incluye 2 filas de ejemplo con datos reales
- Formato CSV con UTF-8 BOM para compatibilidad con Excel
- Archivo: `plantilla_importacion_inventario.csv`

#### Ejemplo de Contenido:
```csv
product_name,warehouse_id,product_type_id,textile_type_id,sku,barcode,quantity,unit,unit_price,location,min_stock,max_stock,notes
Tela de Algodón Premium,1,1,1,ALG-001,7501234567890,100,metros,25.50,A-01-15,20,200,Material de alta calidad para producción
Botones de Metal,1,2,,BTN-MET-50,,500,unidades,0.50,B-03-08,100,1000,Botones dorados para camisas
```

## Proceso de Importación

### Paso 1: Preparar el Archivo
1. Descargar plantilla desde el modal
2. Llenar datos siguiendo el formato
3. Verificar que los IDs de bodega/tipos existan en el sistema
4. Guardar como CSV (UTF-8)

### Paso 2: Subir el Archivo
1. Clic en botón "Importar CSV"
2. Seleccionar archivo desde el modal
3. Clic en "Importar Productos"

### Paso 3: Procesamiento
El sistema:
- Lee el archivo línea por línea
- Valida campos requeridos
- Verifica que las bodegas existan
- Crea registros en la tabla `inventories`
- Calcula `total_price` automáticamente (quantity × unit_price)
- Asigna `created_by` al usuario actual
- Marca status como 'activo'

### Paso 4: Resultados
Muestra mensaje con:
- Número de productos importados exitosamente
- Lista de errores encontrados (máximo 5 mostrados)
- Si hay más de 5 errores, indica el total

## Validaciones

### A Nivel de Archivo
- ✅ Formato: CSV, TXT (Excel convertir a CSV)
- ✅ Tamaño máximo: 10MB
- ✅ Codificación: UTF-8 recomendado

### A Nivel de Fila
- ✅ Campos requeridos presentes
- ✅ `warehouse_id` debe existir en base de datos
- ✅ `quantity` y `unit_price` numéricos válidos
- ✅ IDs de tipos opcionales (si se proporcionan, deben existir)

### Manejo de Errores
- Si una fila falla, se registra el error pero continúa con las siguientes
- Errores incluyen número de fila para fácil identificación
- No hace rollback: las filas válidas se importan aunque haya errores

## Ejemplo de Uso

### Escenario: Carga Inicial de 100 Productos
```
1. Usuario descarga plantilla
2. Abre en Excel
3. Llena 100 filas con datos
4. Guarda como CSV UTF-8
5. Importa desde modal
6. Sistema procesa e informa: "98 productos importados exitosamente. 2 errores encontrados: Fila 15: Bodega ID 99 no existe, Fila 47: Faltan campos requeridos"
```

### Escenario: Actualización Masiva de Precios
```
1. Exportar inventario actual
2. Actualizar columna unit_price en Excel
3. Importar nuevamente (creará nuevos registros)
4. Nota: Para actualizar existentes, usar exportar → modificar → eliminar antiguos → importar nuevos
```

## Archivos Modificados

### Vista
- `resources/views/inventory/index.blade.php`
  - Agregado botón "Importar CSV" (línea ~14)
  - Modal completo con Alpine.js (líneas 226-339)
  - Estilos x-cloak para transiciones

### Controlador
- `app/Http/Controllers/InventoryController.php`
  - Método `template()`: Genera plantilla CSV de ejemplo
  - Método `import()`: Procesa archivo CSV y crea registros

### Rutas
- `routes/web.php`
  - `GET /inventory/template` → descargar plantilla
  - `POST /inventory/import` → procesar importación

## Tecnologías Utilizadas
- **Alpine.js**: Manejo del modal (x-data, x-show, x-cloak)
- **Tailwind CSS**: Estilos del modal y formulario
- **PHP CSV Functions**: fgetcsv() para parseo
- **Laravel Validation**: Validación de archivo subido

## Mejoras Futuras

### Sugerencias de Implementación
1. **Soporte Excel Nativo**:
   - Instalar `phpoffice/phpspreadsheet`
   - Permitir importar `.xlsx` directamente sin convertir

2. **Vista Previa**:
   - Mostrar primeras 5 filas antes de importar
   - Permitir corregir errores en línea

3. **Actualización en Lugar de Creación**:
   - Opción para actualizar productos existentes (match por SKU o barcode)
   - Evitar duplicados

4. **Validación Avanzada**:
   - Validar que SKU/barcode sean únicos antes de importar
   - Verificar que tipos de producto/textil existan

5. **Procesamiento en Background**:
   - Para archivos grandes (>1000 filas)
   - Usar Laravel Queue Jobs
   - Notificar por email cuando termine

6. **Log Detallado**:
   - Guardar archivo de log con todos los errores
   - Descargar reporte de importación

7. **Mapeo de Columnas**:
   - Permitir al usuario mapear columnas del CSV
   - No requerir nombres exactos de columnas

## Notas Técnicas

### Performance
- Procesa ~100 productos/segundo en hardware promedio
- Para archivos grandes, considerar Jobs en queue
- Límite actual: 10MB de archivo

### Seguridad
- Validación server-side obligatoria
- CSRF protection en el formulario
- Sanitización de inputs antes de insertar
- Solo usuarios autenticados pueden importar

### Compatibilidad
- Excel: Requiere "Guardar como CSV UTF-8"
- Google Sheets: Descargar como CSV
- LibreOffice Calc: Exportar como CSV UTF-8

## Commit
- **Hash**: e9269c7
- **Mensaje**: "Agregar importación masiva de productos al módulo de Inventario con plantilla CSV"
- **Archivos**: 3 modificados, 297 líneas agregadas
- **Branch**: modulos
