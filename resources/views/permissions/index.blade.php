<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Gestión de Permisos') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Haz clic en los switches para activar/desactivar permisos
                </p>
            </div>
            <div class="flex gap-3">
                <button onclick="resetPermissions()" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Restaurar Por Defecto
                </button>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Gestionar Usuarios
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Leyenda de Roles -->
            <div class="mb-6 grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="bg-purple-50 dark:bg-purple-900/20 border-2 border-purple-200 dark:border-purple-700 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                        <span class="text-sm font-semibold text-purple-900 dark:text-purple-200">Super Admin</span>
                    </div>
                    <p class="text-xs text-purple-700 dark:text-purple-300 mt-1">Acceso total</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-700 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm font-semibold text-blue-900 dark:text-blue-200">Administrador</span>
                    </div>
                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">Todo menos usuarios</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 border-2 border-green-200 dark:border-green-700 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm font-semibold text-green-900 dark:text-green-200">Gerente Bodega</span>
                    </div>
                    <p class="text-xs text-green-700 dark:text-green-300 mt-1">Solo inventario</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-200 dark:border-yellow-700 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                        <span class="text-sm font-semibold text-yellow-900 dark:text-yellow-200">Supervisor</span>
                    </div>
                    <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Solo lectura</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-200">Empleado</span>
                    </div>
                    <p class="text-xs text-gray-700 dark:text-gray-300 mt-1">Acceso mínimo</p>
                </div>
            </div>

            <!-- Matriz de Permisos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Matriz de Permisos por Módulo
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                        Módulo
                                    </th>
                                    @foreach($roles as $roleKey => $roleName)
                                        <th class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider
                                            {{ $roleKey === 'super_admin' ? 'text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-900/20' : '' }}
                                            {{ $roleKey === 'admin' ? 'text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : '' }}
                                            {{ $roleKey === 'gerente_bodega' ? 'text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20' : '' }}
                                            {{ $roleKey === 'supervisor' ? 'text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/20' : '' }}
                                            {{ $roleKey === 'empleado' ? 'text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700' : '' }}">
                                            <div class="flex items-center justify-center">
                                                <div class="w-2 h-2 rounded-full mr-2
                                                    {{ $roleKey === 'super_admin' ? 'bg-purple-500' : '' }}
                                                    {{ $roleKey === 'admin' ? 'bg-blue-500' : '' }}
                                                    {{ $roleKey === 'gerente_bodega' ? 'bg-green-500' : '' }}
                                                    {{ $roleKey === 'supervisor' ? 'bg-yellow-500' : '' }}
                                                    {{ $roleKey === 'empleado' ? 'bg-gray-500' : '' }}"></div>
                                                <span class="hidden lg:inline">{{ $roleName }}</span>
                                                <span class="lg:hidden">{{ substr($roleName, 0, 10) }}</span>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($modules as $module)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $module->module_icon }}"></path>
                                                </svg>
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $module->module_name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $module->module_key }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        @foreach($roles as $roleKey => $roleName)
                                            <td class="px-4 py-4 text-center
                                                {{ $roleKey === 'super_admin' ? 'bg-purple-50/50 dark:bg-purple-900/10' : '' }}
                                                {{ $roleKey === 'admin' ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}
                                                {{ $roleKey === 'gerente_bodega' ? 'bg-green-50/50 dark:bg-green-900/10' : '' }}
                                                {{ $roleKey === 'supervisor' ? 'bg-yellow-50/50 dark:bg-yellow-900/10' : '' }}
                                                {{ $roleKey === 'empleado' ? 'bg-gray-50/50 dark:bg-gray-700/50' : '' }}">
                                                <div class="flex items-center justify-center">
                                                    <!-- Toggle Switch -->
                                                    <label class="relative inline-block w-11 h-6 cursor-pointer">
                                                        <input type="checkbox" 
                                                               class="opacity-0 w-0 h-0 permission-toggle" 
                                                               data-module-id="{{ $module->id }}"
                                                               data-role="{{ $roleKey }}"
                                                               {{ $module->$roleKey ? 'checked' : '' }}
                                                               onchange="togglePermission(this)">
                                                        <span class="toggle-slider absolute cursor-pointer top-0 left-0 right-0 bottom-0 bg-gray-300 dark:bg-gray-600 rounded-full transition-all duration-300 before:absolute before:content-[''] before:h-5 before:w-5 before:left-0.5 before:bottom-0.5 before:bg-white before:rounded-full before:transition-all before:duration-300"></span>
                                                    </label>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">📝 Notas Importantes:</h4>
                            <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                <li>• Los permisos se aplican inmediatamente al cambiar el rol de un usuario</li>
                                <li>• El usuario debe cerrar sesión y volver a iniciar para ver los cambios</li>
                                <li>• Solo Super Admin puede gestionar usuarios y asignar roles</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">🔒 Seguridad:</h4>
                            <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                <li>• Los permisos están validados en el backend (no solo en la UI)</li>
                                <li>• Accesos no autorizados generan error 403</li>
                                <li>• Los cambios de rol quedan registrados en el sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de estadísticas -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-5 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Módulos</p>
                            <p class="text-3xl font-bold mt-1">{{ count($modules) }}</p>
                        </div>
                        <svg class="w-12 h-12 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-5 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Roles</p>
                            <p class="text-3xl font-bold mt-1">{{ count($roles) }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-5 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Módulos Admin</p>
                            <p class="text-3xl font-bold mt-1">{{ collect($modules)->filter(fn($m) => is_array($m['permissions']) && in_array('admin', $m['permissions']))->count() }}</p>
                        </div>
                        <svg class="w-12 h-12 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-5 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Módulos Bodega</p>
                            <p class="text-3xl font-bold mt-1">{{ collect($modules)->filter(fn($m) => is_array($m['permissions']) && in_array('gerente_bodega', $m['permissions']))->count() }}</p>
                        </div>
                        <svg class="w-12 h-12 text-orange-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notificación Toast -->
    <div id="toast" class="fixed bottom-4 right-4 transform translate-y-32 transition-transform duration-300 z-50">
        <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span id="toast-message">Permiso actualizado</span>
        </div>
    </div>

    <script>
        // CSRF Token para peticiones AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Función para toggle de permisos
        function togglePermission(checkbox) {
            const moduleId = checkbox.dataset.moduleId;
            const role = checkbox.dataset.role;
            const value = checkbox.checked;

            // Deshabilitar el checkbox mientras se procesa
            checkbox.disabled = true;

            fetch('{{ route('permissions.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    module_id: moduleId,
                    role: role,
                    value: value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('✓ Permiso actualizado correctamente', 'success');
                } else {
                    showToast('✗ Error al actualizar permiso', 'error');
                    checkbox.checked = !value; // Revertir
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('✗ Error de conexión', 'error');
                checkbox.checked = !value; // Revertir
            })
            .finally(() => {
                checkbox.disabled = false;
            });
        }

        // Función para restaurar permisos por defecto
        function resetPermissions() {
            if (!confirm('¿Estás seguro de restaurar los permisos a sus valores por defecto? Esta acción no se puede deshacer.')) {
                return;
            }

            fetch('{{ route('permissions.reset') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('✓ Permisos restaurados correctamente', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast('✗ Error al restaurar permisos', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('✗ Error de conexión', 'error');
            });
        }

        // Función para mostrar notificaciones
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const toastDiv = toast.querySelector('div');
            
            toastMessage.textContent = message;
            
            // Cambiar color según el tipo
            if (type === 'success') {
                toastDiv.className = 'bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3';
            } else {
                toastDiv.className = 'bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3';
            }
            
            // Mostrar
            toast.style.transform = 'translateY(0)';
            
            // Ocultar después de 3 segundos
            setTimeout(() => {
                toast.style.transform = 'translateY(8rem)';
            }, 3000);
        }
    </script>
</x-app-layout>
