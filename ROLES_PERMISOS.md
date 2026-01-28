# Sistema de Roles y Permisos

## Descripción General

Esta aplicación implementa un sistema simple de roles basado en enum para controlar el acceso a diferentes módulos y funcionalidades.

## Roles Disponibles

### 1. Super Admin (`super_admin`)
**Color:** Morado
- **Acceso Completo:** Todos los módulos y funcionalidades
- **Permisos Especiales:** 
  - Gestión de usuarios (crear, editar, eliminar, asignar roles)
  - Acceso a todos los módulos administrativos
  - Gestión completa de inventario y bodega

**Pantallas accesibles:**
- ✅ Dashboard
- ✅ Empleados (employees)
- ✅ Asistencia (attendance)
- ✅ Contactos (contacts)
- ✅ Usuarios (users) *EXCLUSIVO*
- ✅ Proveedores (suppliers)
- ✅ Recepciones (receptions)
- ✅ Inventario (inventory)
- ✅ Bodegas (warehouses)
- ✅ Tipos de Textiles (textile-types)
- ✅ Tipos de Productos (product-types)

---

### 2. Administrador (`admin`)
**Color:** Azul
- **Acceso:** Todos los módulos excepto gestión de usuarios
- **Limitaciones:** No puede crear, editar o eliminar usuarios

**Pantallas accesibles:**
- ✅ Dashboard
- ✅ Empleados (employees)
- ✅ Asistencia (attendance)
- ✅ Contactos (contacts)
- ❌ Usuarios (users) *RESTRINGIDO*
- ✅ Proveedores (suppliers)
- ✅ Recepciones (receptions)
- ✅ Inventario (inventory)
- ✅ Bodegas (warehouses)
- ✅ Tipos de Textiles (textile-types)
- ✅ Tipos de Productos (product-types)

---

### 3. Gerente de Bodega (`gerente_bodega`)
**Color:** Verde
- **Acceso:** Módulos relacionados con inventario y bodega
- **Enfoque:** Gestión operativa de bodega

**Pantallas accesibles:**
- ✅ Dashboard
- ❌ Empleados (employees)
- ❌ Asistencia (attendance)
- ❌ Contactos (contacts)
- ❌ Usuarios (users)
- ❌ Proveedores (suppliers)
- ✅ Recepciones (receptions)
- ✅ Inventario (inventory)
- ✅ Bodegas (warehouses)
- ✅ Tipos de Textiles (textile-types)
- ✅ Tipos de Productos (product-types)

---

### 4. Supervisor (`supervisor`)
**Color:** Amarillo
- **Acceso:** Solo visualización de módulos de inventario
- **Limitaciones:** Acceso de solo lectura (implementar en versión futura)

**Pantallas accesibles:**
- ✅ Dashboard
- ❌ Empleados (employees)
- ✅ Asistencia (attendance) *CONSULTA*
- ❌ Contactos (contacts)
- ❌ Usuarios (users)
- ❌ Proveedores (suppliers)
- ✅ Recepciones (receptions) *CONSULTA*
- ✅ Inventario (inventory) *CONSULTA*
- ✅ Bodegas (warehouses) *CONSULTA*
- ✅ Tipos de Textiles (textile-types) *CONSULTA*
- ✅ Tipos de Productos (product-types) *CONSULTA*

---

### 5. Empleado (`empleado`)
**Color:** Gris
- **Acceso:** Solo dashboard
- **Limitaciones:** Acceso mínimo, ideal para empleados operativos

**Pantallas accesibles:**
- ✅ Dashboard
- ❌ Empleados (employees)
- ❌ Asistencia (attendance)
- ❌ Contactos (contacts)
- ❌ Usuarios (users)
- ❌ Proveedores (suppliers)
- ❌ Recepciones (receptions)
- ❌ Inventario (inventory)
- ❌ Bodegas (warehouses)
- ❌ Tipos de Textiles (textile-types)
- ❌ Tipos de Productos (product-types)

---

## Implementación Técnica

### Archivos Modificados/Creados

1. **Migración:** `database/migrations/2026_01_12_163756_add_role_to_users_table.php`
   - Agrega columna `role` tipo enum a la tabla `users`
   - Valores: super_admin, admin, gerente_bodega, supervisor, empleado
   - Valor por defecto: empleado

2. **Modelo User:** `app/Models/User.php`
   - Métodos helper para verificar roles:
     - `isSuperAdmin()`
     - `isAdmin()`
     - `isGerenteBodega()`
     - `isSupervisor()`
     - `isEmpleado()`
     - `hasRole(...$roles)`
     - `hasAnyRole(...$roles)`
     - `canAccessModule($module)`
   - Accessor `role_name` para obtener nombre en español

3. **Middleware:** `app/Http/Middleware/CheckRole.php`
   - Verifica que el usuario tenga uno de los roles requeridos
   - Redirige con error 403 si no tiene permisos

4. **Rutas:** `routes/web.php`
   - Protección de rutas con middleware `role:`
   - Ejemplo: `->middleware('role:super_admin,admin')`

5. **Vistas:**
   - `resources/views/layouts/navigation.blade.php` - Menú con restricciones por rol
   - `resources/views/users/index.blade.php` - Lista de usuarios con badges de roles
   - `resources/views/users/create.blade.php` - Formulario con selector de roles
   - `resources/views/users/edit.blade.php` - Edición con selector de roles

### Uso en Código

#### En Rutas
```php
Route::resource('users', UserController::class)
    ->middleware('role:super_admin');

Route::resource('employees', EmployeeController::class)
    ->middleware('role:super_admin,admin');
```

#### En Controladores
```php
if (!auth()->user()->hasRole('super_admin', 'admin')) {
    abort(403, 'No tienes permisos para esta acción.');
}
```

#### En Blade
```blade
@if(auth()->user()->isSuperAdmin())
    <!-- Contenido solo para super admin -->
@endif

@if(auth()->user()->hasAnyRole(['super_admin', 'admin']))
    <!-- Contenido para admin y super admin -->
@endif
```

---

## Asignación de Roles

### Asignar rol al crear usuario
1. Iniciar sesión como Super Admin
2. Ir a Admin > Usuarios
3. Click en "Add member"
4. Completar formulario y seleccionar rol
5. Guardar

### Cambiar rol de usuario existente
1. Iniciar sesión como Super Admin
2. Ir a Admin > Usuarios
3. Click en el icono de editar (lápiz) del usuario
4. Cambiar rol en el selector
5. Guardar cambios

### Asignar rol por Tinker (línea de comandos)
```bash
php artisan tinker
App\Models\User::where('email', 'usuario@ejemplo.com')->update(['role' => 'admin']);
```

---

## Consideraciones de Seguridad

1. **Solo Super Admin puede gestionar usuarios:** Evita que administradores regulares se auto-promocionen
2. **Middleware en rutas:** Toda ruta sensible debe estar protegida con middleware `role:`
3. **Validación en formularios:** El campo role se valida con regla `in:` para prevenir valores no válidos
4. **Navegación oculta:** Los menús se ocultan según rol, pero esto NO es suficiente - siempre validar en backend
5. **Protección en controladores:** Además del middleware de rutas, validar permisos en métodos críticos

---

## Migración a Producción

### Pasos para desplegar en producción

1. **Subir archivos actualizados al servidor:**
   - Via SSH/FTP: Subir archivos modificados
   - Via Git: `git pull origin branch-name`

2. **Ejecutar migración:**
   ```bash
   cd ~/bodega.trapolimpieza.com
   php artisan migrate --force
   ```

3. **Asignar rol super_admin al usuario admin:**
   ```bash
   php artisan tinker
   App\Models\User::where('email', 'admin@bodegapeniel.com')->update(['role' => 'super_admin']);
   exit
   ```

4. **Limpiar cachés:**
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Verificar permisos:**
   - Iniciar sesión en https://bodega.trapolimpieza.com
   - Verificar que el menú muestre las opciones correctas
   - Probar acceso a módulo de Usuarios
   - Crear un usuario de prueba con rol diferente
   - Probar restricciones de acceso

---

## Problemas Conocidos y Soluciones

### Error: "Column 'role' not found"
**Solución:** Ejecutar migración: `php artisan migrate`

### Error: "Call to undefined method hasRole()"
**Solución:** Verificar que el modelo User tenga los métodos helper agregados

### Usuario no puede acceder a ningún módulo
**Solución:** Verificar que el usuario tenga un rol asignado (no NULL):
```bash
php artisan tinker
$user = App\Models\User::find(1);
echo $user->role; // Debe mostrar un rol válido
```

### Menú no se oculta según rol
**Solución:** Limpiar cache de vistas: `php artisan view:clear`

---

## Expansión Futura

### Funcionalidades a implementar:

1. **Permisos granulares:** 
   - Separar "ver", "crear", "editar", "eliminar" por módulo
   - Implementar tabla `permissions` y `role_permissions`

2. **Audit Log:**
   - Registrar cambios de roles
   - Registrar acciones por usuario

3. **Notificaciones:**
   - Email cuando se cambia rol de usuario
   - Alerta cuando se intenta acceder sin permisos

4. **UI Mejorada:**
   - Panel de gestión de roles visual
   - Matriz de permisos editable

5. **Migrar a Spatie Permission:**
   - Si se requiere mayor flexibilidad
   - Cuando se necesiten múltiples roles por usuario

---

## Contacto y Soporte

Para dudas o problemas con el sistema de roles:
1. Revisar esta documentación
2. Verificar logs en `storage/logs/laravel.log`
3. Contactar al administrador del sistema

---

**Última actualización:** Enero 2026
**Versión del sistema:** 1.0
