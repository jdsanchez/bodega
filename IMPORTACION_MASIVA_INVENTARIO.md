# Importación Masiva de Inventario

## 📋 Descripción
El módulo de inventario ahora permite la importación masiva de productos mediante archivos CSV con el siguiente formato:

## 📄 Formato del Archivo CSV

### Encabezados Requeridos
```
CODIGO_PRODUCTO,DESCRIPCION,UNIDAD_DE_MEDIDA,CANTIDAD,COSTO_UNITARIO,COSTO_TOTAL,MALETA O ROLLO,TELA,COLOR
```

### Descripción de Campos

| Campo | Descripción | Requerido | Ejemplo |
|-------|-------------|-----------|---------|
| **CODIGO_PRODUCTO** | Código único del producto (SKU) | No* | ALG-001 |
| **DESCRIPCION** | Descripción del producto | ✅ Sí | Tela de Algodón Premium |
| **UNIDAD_DE_MEDIDA** | Unidad (metros, unidades, kg, etc.) | No | metros |
| **CANTIDAD** | Cantidad en stock | ✅ Sí | 100 |
| **COSTO_UNITARIO** | Precio por unidad | ✅ Sí | 25.50 |
| **COSTO_TOTAL** | Costo total (se calcula automático si no se proporciona) | No | 2550.00 |
| **MALETA O ROLLO** | Identificador de maleta o rollo | No | ROLLO-A1 |
| **TELA** | Tipo de tela/textil | No | Algodón |
| **COLOR** | Color del producto | No | Blanco |

*Si no se proporciona CODIGO_PRODUCTO, se generará automáticamente.

## 📝 Ejemplo de Archivo CSV

```csv
CODIGO_PRODUCTO,DESCRIPCION,UNIDAD_DE_MEDIDA,CANTIDAD,COSTO_UNITARIO,COSTO_TOTAL,MALETA O ROLLO,TELA,COLOR
ALG-001,Tela de Algodón Premium,metros,100,25.50,2550.00,ROLLO-A1,Algodón,Blanco
SED-002,Seda Natural,metros,50,85.00,4250.00,MALETA-B3,Seda,Rojo
POL-003,Poliéster Estampado,metros,200,15.75,3150.00,ROLLO-C5,Poliéster,Azul
LIN-004,Lino Fino,metros,75,45.00,3375.00,MALETA-A2,Lino,Beige
```

## 🚀 Cómo Usar la Importación

### Opción 1: Descargar Plantilla

1. Ve al módulo de **Inventario**
2. Haz clic en **"Descargar Plantilla CSV"**
3. Se descargará un archivo con ejemplos que puedes editar
4. Llena el archivo con tus productos

### Opción 2: Crear tu propio archivo

1. Abre Excel o Google Sheets
2. Crea una hoja con los encabezados especificados arriba
3. Llena los datos de tus productos
4. Guarda como **CSV (delimitado por comas)**

### Importar el Archivo

1. Ve al módulo de **Inventario**
2. Haz clic en **"Importar desde CSV"**
3. Selecciona tu archivo CSV
4. Haz clic en **"Importar"**
5. El sistema mostrará un resumen con:
   - ✅ Productos importados exitosamente
   - ❌ Errores encontrados (si los hay)

## ⚠️ Notas Importantes

### Warehouse (Bodega)
- Si no especificas una bodega, se usará la primera bodega activa del sistema
- Asegúrate de tener al menos una bodega activa antes de importar

### Tipo de Producto
- Se asignará automáticamente el primer tipo de producto disponible
- Puedes editar esto después de la importación

### Tipo de Textil (TELA)
- El sistema busca automáticamente el tipo de textil por nombre
- Debe existir previamente en el catálogo de "Tipos de Textiles"
- Si no existe, el campo quedará vacío

### SKU Único
- Si proporcionas CODIGO_PRODUCTO, debe ser único
- Si ya existe, la importación de esa fila fallará
- Si no lo proporcionas, se generará automáticamente

### Cálculo de COSTO_TOTAL
- Si proporcionas COSTO_TOTAL, se usará ese valor
- Si no, se calculará automáticamente: `CANTIDAD × COSTO_UNITARIO`

## 🔧 Campos Agregados en la Base de Datos

Se agregaron dos nuevos campos a la tabla de inventarios:

- **maleta_rollo**: Para identificar la maleta o rollo donde se encuentra el producto
- **color**: Para especificar el color del producto

## ✅ Validaciones

El sistema valida:
- ✅ Campos requeridos presentes
- ✅ Valores numéricos válidos
- ✅ SKU único (si se proporciona)
- ✅ Formato correcto del archivo CSV

## 📊 Exportación

También puedes **exportar** tu inventario actual:
- Haz clic en "Exportar a CSV"
- Se descargará un archivo con todos los productos
- Incluye los campos: MALETA O ROLLO y COLOR

## 🐛 Solución de Problemas

### Error: "Faltan campos requeridos"
- Verifica que el archivo tenga los encabezados correctos
- Asegúrate de que DESCRIPCION, CANTIDAD y COSTO_UNITARIO tengan valores

### Error: "No hay bodegas activas"
- Crea al menos una bodega activa en el sistema
- Ve a **Módulo de Bodegas → Crear Nueva Bodega**

### Error: "SKU duplicado"
- El código de producto ya existe
- Usa un código diferente o edita el producto existente

## 💡 Consejos

1. **Prueba con pocos productos primero** para verificar el formato
2. **Revisa los errores** si la importación falla parcialmente
3. **Guarda siempre una copia** de tu archivo CSV
4. **Usa Excel o LibreOffice** para editar archivos CSV cómodamente
5. **Verifica la codificación UTF-8** si hay problemas con tildes o caracteres especiales
