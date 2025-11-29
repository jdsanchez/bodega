# Módulo de Recepción de Productos

## Descripción General
Sistema completo para gestionar la recepción de productos desde proveedores hacia bodegas, con seguimiento de cantidades esperadas vs recibidas, condiciones de productos, y creación automática de registros de inventario.

## Características Principales

### 1. Gestión de Recepciones
- **Creación**: Registrar nuevas recepciones con múltiples productos
- **Edición**: Modificar recepciones pendientes o en revisión
- **Visualización**: Ver detalles completos con historial de auditoría
- **Filtrado**: Por bodega, proveedor, estado, fecha y búsqueda de texto

### 2. Flujo de Trabajo (Status)
1. **Pendiente**: Recepción creada, esperando llegada de productos
2. **En Revisión**: Productos en proceso de verificación
3. **Recibida**: Todos los productos recibidos completamente en buenas condiciones
4. **Parcial**: Algunos productos no se recibieron en las cantidades esperadas
5. **Rechazada**: Recepción rechazada con razón documentada

### 3. Proceso de Recepción
1. Crear recepción con productos esperados
2. Al llegar los productos, usar "Recibir Productos"
3. Ingresar cantidades realmente recibidas por producto
4. Seleccionar condición (Bueno, Dañado, Defectuoso, Incompleto)
5. Asignar ubicación en bodega
6. Procesar recepción → **Auto-creación de inventario**

### 4. Auto-Creación de Inventario
Cuando una recepción se marca como "recibida" o "parcial":
- Se crean automáticamente registros en la tabla `inventories`
- Solo para productos con condición "bueno"
- Cantidad de inventario = cantidad recibida
- Link bidireccional: `reception_items.inventory_id` ↔ `inventories.id`
- Auditoría completa: quién recibió, quién aprobó, fechas

### 5. Información Capturada

#### Encabezado de Recepción
- Número de recepción (auto-generado: REC-YYYYMMDD-0001)
- Bodega destino
- Proveedor
- Número de factura
- Orden de compra
- Fecha esperada / Fecha de recepción
- Estado
- Totales esperados vs recibidos
- Notas generales
- Razón de rechazo (si aplica)

#### Líneas de Productos
- Tipo de producto / Tipo de textil (opcional)
- Nombre del producto
- SKU / Código de barras
- Cantidad esperada vs recibida (con unidad de medida)
- Precio unitario / Precio total
- Condición (Bueno, Dañado, Defectuoso, Incompleto)
- Ubicación en bodega
- Notas por producto
- Link a inventario creado

### 6. Estadísticas del Dashboard
- Pendientes: Recepciones esperando procesamiento
- En Revisión: Recepciones siendo verificadas
- Recibidas: Recepciones completadas este mes
- Valor Total Recibido: Suma de productos recibidos este mes

### 7. Auditoría y Trazabilidad
- **created_by**: Usuario que creó la recepción
- **received_by**: Usuario que procesó la recepción
- **approved_by**: Usuario que aprobó (auto al cambiar a recibida)
- **created_at**: Fecha de creación
- **reception_date**: Fecha de procesamiento
- **approved_at**: Fecha de aprobación
- **updated_at**: Última modificación

## Estructura de Archivos

### Migraciones
- `2025_11_29_151044_create_receptions_table.php` (296ms)
- `2025_11_29_151114_create_reception_items_table.php` (211ms)

### Modelos
- `app/Models/Reception.php` (212 líneas)
  - Boot events para auto-generar número y crear inventario
  - Relaciones: warehouse, supplier, items, creator, receiver, approver
  - Scopes: byWarehouse, bySupplier, byStatus, pending, received
  - Métodos: calculateTotals(), canBeApproved(), canBeRejected()
  - Accessors: status_label, status_color, completion_percentage, formatted_*

- `app/Models/ReceptionItem.php` (120 líneas)
  - Boot events para cálculo automático de total_price
  - Relaciones: reception, productType, textileType, inventory
  - Accessors: condition_label, condition_color, quantity_difference, is_complete

### Controlador
- `app/Http/Controllers/ReceptionController.php` (246 líneas)
  - **index**: Lista con filtros y estadísticas
  - **create**: Formulario para nueva recepción
  - **store**: Crear recepción con transacción
  - **show**: Ver detalles + audit trail
  - **edit**: Editar recepción pendiente
  - **update**: Actualizar existentes + agregar nuevos productos
  - **destroy**: Eliminar recepciones no recibidas
  - **receive**: Formulario de verificación
  - **processReceipt**: Procesar cantidades recibidas → crear inventario
  - **reject**: Rechazar recepción con razón

### Vistas (5 archivos Blade)
1. **index.blade.php**: Lista con filtros, estadísticas, tabla paginada
2. **create.blade.php**: Formulario con productos dinámicos (Alpine.js)
3. **edit.blade.php**: Editar encabezado + productos existentes + agregar nuevos
4. **show.blade.php**: Detalle completo + audit trail + acciones contextuales
5. **receive.blade.php**: Formulario de verificación + modal de rechazo

### Rutas
```php
Route::resource('receptions', ReceptionController::class);
Route::get('/receptions/{reception}/receive', [ReceptionController::class, 'receive'])->name('receptions.receive');
Route::post('/receptions/{reception}/process', [ReceptionController::class, 'processReceipt'])->name('receptions.process');
Route::post('/receptions/{reception}/reject', [ReceptionController::class, 'reject'])->name('receptions.reject');
```

### Navegación
- Desktop: "Inventario Bodega" dropdown → "Recepciones" (primer item)
- Mobile: "Inventario Bodega" collapsible → "Recepciones" (primer item)

## Integración con Otros Módulos

### Dependencias
- **Warehouses**: Bodega destino de los productos
- **Suppliers**: Proveedor de los productos
- **ProductTypes**: Clasificación opcional de productos
- **TextileTypes**: Tipo de textil opcional
- **Users**: Auditoría (creador, receptor, aprobador)

### Crea Automáticamente
- **Inventories**: Al aprobar recepción, crea items en inventario
  - Solo productos con condición "bueno"
  - Cantidad = received_quantity
  - Link bidireccional via inventory_id

## Modelo de Datos

### Tabla: receptions
```
- id (PK)
- warehouse_id (FK → warehouses)
- supplier_id (FK → suppliers)
- reception_number (unique, auto-generado)
- invoice_number
- purchase_order
- expected_date
- reception_date
- status (enum: pendiente, en_revision, recibida, parcial, rechazada)
- notes
- rejection_reason
- total_expected (decimal)
- total_received (decimal)
- created_by (FK → users)
- received_by (FK → users)
- approved_by (FK → users)
- approved_at (timestamp)
- timestamps
```

### Tabla: reception_items
```
- id (PK)
- reception_id (FK → receptions)
- product_type_id (FK → product_types, nullable)
- textile_type_id (FK → textile_types, nullable)
- product_name
- sku
- barcode
- expected_quantity (decimal)
- received_quantity (decimal)
- unit
- unit_price (decimal)
- total_price (decimal)
- condition (enum: bueno, dañado, defectuoso, incompleto)
- location
- notes
- inventory_id (FK → inventories, nullable)
- timestamps
```

## Validaciones

### Crear/Editar Recepción
- warehouse_id: requerido, debe existir en warehouses
- supplier_id: requerido, debe existir en suppliers
- expected_date: requerido, formato fecha
- items: al menos 1 producto requerido
- product_name: requerido por item
- expected_quantity: requerido, numérico, mínimo 0
- unit: requerido (ej: kg, m, unidades)
- unit_price: requerido, numérico, mínimo 0

### Procesar Recepción
- received_quantity: requerido, numérico, mínimo 0
- condition: requerido, valores: bueno, dañado, defectuoso, incompleto
- location: requerido para productos recibidos

### Rechazar Recepción
- rejection_reason: requerido, texto

## Reglas de Negocio

1. **Solo recepciones "pendiente" o "en_revision" pueden editarse**
2. **Solo recepciones "pendiente" o "en_revision" pueden procesarse**
3. **Recepciones "recibida" no pueden eliminarse** (tienen inventario asociado)
4. **Número de recepción auto-generado**: REC-YYYYMMDD-NNNN
5. **Status automático al procesar**:
   - Si todas las cantidades recibidas = esperadas → "recibida"
   - Si alguna cantidad recibida < esperada → "parcial"
6. **Inventario se crea solo si**:
   - Status cambia a "recibida" (100% completo)
   - Condición del producto = "bueno"
7. **Cálculo de totales automático**:
   - total_price = received_quantity × unit_price
   - Actualiza totales de recepción en cada cambio

## Tecnologías Usadas
- **Backend**: Laravel 12.36.1 (PHP 8.2.12)
- **Frontend**: Blade templates + Tailwind CSS + Alpine.js
- **Base de Datos**: MySQL (via XAMPP)
- **Validación**: Form Requests inline en controller
- **Transacciones**: DB::beginTransaction() para operaciones multi-tabla

## Pruebas Sugeridas

### Flujo Completo
1. Crear nueva recepción con 3 productos
2. Ver lista de recepciones (debe aparecer como "Pendiente")
3. Abrir detalle de recepción
4. Clic en "Recibir Productos"
5. Ingresar cantidades recibidas (2 productos completos, 1 parcial)
6. Seleccionar condiciones (2 buenos, 1 dañado)
7. Asignar ubicaciones
8. Procesar recepción
9. Verificar status = "parcial"
10. Verificar que se crearon 2 registros en inventario (solo "buenos")
11. Verificar link bidireccional (inventory_id en reception_item)

### Casos Edge
- Intentar editar recepción "recibida" (debe fallar)
- Intentar eliminar recepción "recibida" (debe fallar)
- Rechazar recepción sin razón (debe validar)
- Crear recepción sin productos (debe validar)
- Recibir cantidad mayor a esperada (permitido, marca excedente)
- Recibir 0 productos (permitido, marca faltante)

## Mejoras Futuras Sugeridas

1. **Validación avanzada**:
   - Form Request classes separadas
   - Validaciones custom para cantidades

2. **Autorización**:
   - Policies: quién puede aprobar recepciones
   - Roles: receptor vs aprobador

3. **Notificaciones**:
   - Email cuando recepción llega
   - Notificación push para recepciones pendientes

4. **Reportes**:
   - Reporte de recepciones por período
   - Análisis de proveedor (puntualidad, calidad)
   - Productos más dañados/defectuosos

5. **Integración**:
   - Link a órdenes de compra (módulo futuro)
   - Devoluciones a proveedor
   - Transferencias entre bodegas

6. **UX**:
   - Escaneo de código de barras
   - Fotos de productos dañados
   - Firma digital del receptor

7. **Testing**:
   - Unit tests para auto-inventario
   - Feature tests para flujo completo
   - Browser tests (Dusk)

## Autor y Fecha
- **Desarrollado**: 2025-11-29
- **Commit**: e4a6961
- **Branch**: modulos
- **Archivos**: 12 archivos, 2095 líneas agregadas

## Notas Técnicas

### Performance
- Índices en: warehouse_id, supplier_id, status, reception_date
- Índice compuesto: (warehouse_id, status) para filtros comunes
- Eager loading en vistas para evitar N+1 queries

### Seguridad
- CSRF protection en todos los forms
- Validación server-side obligatoria
- Middleware auth en todas las rutas
- Sanitización de inputs

### Mantenibilidad
- Código bien documentado
- Nombres descriptivos en español
- Separación de concerns (Model boot events)
- Transacciones para integridad de datos
