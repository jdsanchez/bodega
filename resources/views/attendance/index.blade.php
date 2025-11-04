<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Asistencia del Día') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('attendance.report') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Reportes
                </a>
                <a href="{{ route('attendance.history') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Historial
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Fecha actual -->
            <div class="mb-6 text-center">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $today->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" id="current-time"></p>
            </div>

            <!-- Notificación -->
            <div id="notification" class="hidden mb-6 mx-4 sm:mx-0">
                <div class="rounded-lg p-4 flex items-center justify-between shadow-lg">
                    <div class="flex items-center">
                        <div id="notification-icon" class="flex-shrink-0"></div>
                        <div class="ml-3">
                            <p id="notification-title" class="text-sm font-medium"></p>
                            <p id="notification-message" class="text-sm"></p>
                        </div>
                    </div>
                    <button onclick="closeNotification()" class="flex-shrink-0 ml-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Grid de empleados -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8">
                @if($employees->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($employees as $employee)
                            <div class="employee-card text-center">
                                <button 
                                    onclick="openModal({{ $employee->id }}, '{{ $employee->full_name }}')"
                                    class="w-full group focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-600 rounded-2xl transition-all duration-200 hover:scale-105"
                                >
                                    <!-- Foto del empleado -->
                                    <div class="relative mx-auto mb-3">
                                        @if($employee->photo)
                                            <img src="{{ Storage::url($employee->photo) }}" 
                                                 alt="{{ $employee->full_name }}" 
                                                 class="w-32 h-32 rounded-2xl object-cover border-4 border-gray-300 dark:border-gray-600 group-hover:border-indigo-500 dark:group-hover:border-indigo-400 transition-colors duration-200 shadow-lg">
                                        @else
                                            <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-gray-300 dark:border-gray-600 group-hover:border-indigo-500 dark:group-hover:border-indigo-400 transition-colors duration-200 shadow-lg">
                                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                            </div>
                                        @endif
                                        
                                        <!-- Indicador de estado -->
                                        <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-md" id="status-{{ $employee->id }}">
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Nombre del empleado -->
                                    <div class="px-2">
                                        <p class="font-bold text-sm text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">
                                            {{ $employee->first_name }}
                                        </p>
                                        <p class="font-bold text-sm text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">
                                            {{ $employee->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $employee->role_name }}
                                        </p>
                                    </div>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay empleados activos</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Agrega empleados para comenzar a registrar asistencias.</p>
                    </div>
                @endif
            </div>

            <!-- Modal de selección -->
            <div id="attendanceModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50" onclick="closeModal(event)">
                <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800" onclick="event.stopPropagation()">
                    <div class="mt-3">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" id="modalEmployeeName"></h3>
                            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Hora actual -->
                        <div class="mb-6 text-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Hora actual</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100" id="modalCurrentTime"></p>
                        </div>

                        <!-- Opciones de registro -->
                        <div class="space-y-3">
                            <button 
                                onclick="selectAttendanceType('check_in')"
                                class="w-full flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 border-2 border-green-200 dark:border-green-800 rounded-lg transition-all duration-150 group"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 text-left">
                                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">Entrada</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Registrar llegada</p>
                                    </div>
                                </div>
                                <svg class="w-6 h-6 text-green-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <button 
                                onclick="selectAttendanceType('lunch_out')"
                                class="w-full flex items-center justify-between p-4 bg-orange-50 dark:bg-orange-900/30 hover:bg-orange-100 dark:hover:bg-orange-900/50 border-2 border-orange-200 dark:border-orange-800 rounded-lg transition-all duration-150 group"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 text-left">
                                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">Salida a Almuerzo</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Hora de comida</p>
                                    </div>
                                </div>
                                <svg class="w-6 h-6 text-orange-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <button 
                                onclick="selectAttendanceType('lunch_in')"
                                class="w-full flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 border-2 border-blue-200 dark:border-blue-800 rounded-lg transition-all duration-150 group"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 text-left">
                                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">Regreso de Almuerzo</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Vuelta al trabajo</p>
                                    </div>
                                </div>
                                <svg class="w-6 h-6 text-blue-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <button 
                                onclick="selectAttendanceType('check_out')"
                                class="w-full flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 border-2 border-red-200 dark:border-red-800 rounded-lg transition-all duration-150 group"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 text-left">
                                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">Salida</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Fin de jornada</p>
                                    </div>
                                </div>
                                <svg class="w-6 h-6 text-red-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Botón cancelar -->
                        <div class="mt-6">
                            <button 
                                onclick="closeModal()"
                                class="w-full px-4 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-colors duration-150"
                            >
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedEmployeeId = null;
        let selectedEmployeeName = null;
        let modalTimeInterval = null;

        // Actualizar hora actual
        function updateTime() {
            const now = new Date();
            const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            document.getElementById('current-time').textContent = now.toLocaleTimeString('es-GT', options);
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Actualizar hora del modal
        function updateModalTime() {
            const now = new Date();
            const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const modalTimeEl = document.getElementById('modalCurrentTime');
            if (modalTimeEl) {
                modalTimeEl.textContent = now.toLocaleTimeString('es-GT', options);
            }
        }

        // Abrir modal
        function openModal(employeeId, employeeName) {
            selectedEmployeeId = employeeId;
            selectedEmployeeName = employeeName;
            
            document.getElementById('modalEmployeeName').textContent = employeeName;
            document.getElementById('attendanceModal').classList.remove('hidden');
            
            // Iniciar actualización de hora en el modal
            updateModalTime();
            modalTimeInterval = setInterval(updateModalTime, 1000);
        }

        // Cerrar modal
        function closeModal(event) {
            if (event && event.target !== event.currentTarget) return;
            
            document.getElementById('attendanceModal').classList.add('hidden');
            selectedEmployeeId = null;
            selectedEmployeeName = null;
            
            // Detener actualización de hora
            if (modalTimeInterval) {
                clearInterval(modalTimeInterval);
                modalTimeInterval = null;
            }
        }

        // Seleccionar tipo de asistencia
        async function selectAttendanceType(type) {
            if (!selectedEmployeeId) return;

            // Guardar los valores antes de cerrar el modal
            const empId = selectedEmployeeId;
            const empName = selectedEmployeeName;
            
            closeModal();
            await registerAttendance(empId, empName, type);
        }

        // Registrar asistencia
        async function registerAttendance(employeeId, employeeName, type) {
            try {
                const response = await fetch(`/attendance/check-in/${employeeId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ type: type })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.type, employeeName, data.message);
                    updateEmployeeStatus(employeeId, data.type);
                } else {
                    showNotification('error', employeeName, data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', employeeName, 'Error al registrar la asistencia');
            }
        }

        // Mostrar notificación
        function showNotification(type, employee, message) {
            const notification = document.getElementById('notification');
            const icon = document.getElementById('notification-icon');
            const title = document.getElementById('notification-title');
            const messageEl = document.getElementById('notification-message');

            // Configurar colores y iconos según el tipo
            const configs = {
                'check_in': {
                    bg: 'bg-green-100 dark:bg-green-900',
                    text: 'text-green-800 dark:text-green-200',
                    icon: '<svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                    title: '✓ Entrada Registrada'
                },
                'lunch_out': {
                    bg: 'bg-orange-100 dark:bg-orange-900',
                    text: 'text-orange-800 dark:text-orange-200',
                    icon: '<svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>',
                    title: '🍽️ Salida a Almuerzo'
                },
                'lunch_in': {
                    bg: 'bg-blue-100 dark:bg-blue-900',
                    text: 'text-blue-800 dark:text-blue-200',
                    icon: '<svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path></svg>',
                    title: '↩️ Regreso de Almuerzo'
                },
                'check_out': {
                    bg: 'bg-red-100 dark:bg-red-900',
                    text: 'text-red-800 dark:text-red-200',
                    icon: '<svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
                    title: '👋 Salida Registrada'
                },
                'complete': {
                    bg: 'bg-gray-100 dark:bg-gray-700',
                    text: 'text-gray-800 dark:text-gray-200',
                    icon: '<svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
                    title: 'ℹ️ Registro Completo'
                },
                'error': {
                    bg: 'bg-red-100 dark:bg-red-900',
                    text: 'text-red-800 dark:text-red-200',
                    icon: '<svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                    title: '❌ Error'
                }
            };

            const config = configs[type] || configs['error'];
            
            notification.className = `mb-6 mx-4 sm:mx-0 ${config.bg} rounded-lg p-4 flex items-center justify-between shadow-lg`;
            title.className = `text-sm font-medium ${config.text}`;
            messageEl.className = `text-sm ${config.text}`;
            icon.innerHTML = config.icon;
            title.textContent = config.title;
            messageEl.textContent = `${employee}: ${message}`;

            notification.classList.remove('hidden');

            // Auto-cerrar después de 5 segundos
            setTimeout(() => {
                closeNotification();
            }, 5000);
        }

        function closeNotification() {
            document.getElementById('notification').classList.add('hidden');
        }

        function updateEmployeeStatus(employeeId, type) {
            const statusEl = document.getElementById(`status-${employeeId}`);
            if (!statusEl) return;

            const colors = {
                'check_in': 'bg-green-500',
                'lunch_out': 'bg-orange-500',
                'lunch_in': 'bg-blue-500',
                'check_out': 'bg-red-500',
                'complete': 'bg-gray-500'
            };

            const icons = {
                'check_in': '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
                'lunch_out': '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"></path></svg>',
                'lunch_in': '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path></svg>',
                'check_out': '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
                'complete': '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            };

            statusEl.className = `absolute -bottom-2 -right-2 w-10 h-10 ${colors[type] || 'bg-gray-500'} rounded-full flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-md`;
            statusEl.innerHTML = icons[type] || icons['complete'];
        }
    </script>
</x-app-layout>
