<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('employees.index') }}" class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Nuevo Empleado') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-200">
                <div class="p-8">
                    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Información Personal -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información Personal</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre -->
                                <div>
                                    <x-input-label for="first_name" :value="__('Nombre')" class="dark:text-gray-200" />
                                    <x-text-input id="first_name" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="first_name" :value="old('first_name')" required placeholder="Juan" />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                </div>

                                <!-- Apellido -->
                                <div>
                                    <x-input-label for="last_name" :value="__('Apellido')" class="dark:text-gray-200" />
                                    <x-text-input id="last_name" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="last_name" :value="old('last_name')" required placeholder="Pérez" />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>

                                <!-- DPI -->
                                <div>
                                    <x-input-label for="dpi" :value="__('DPI')" class="dark:text-gray-200" />
                                    <x-text-input id="dpi" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="dpi" :value="old('dpi')" required placeholder="1234567890101" />
                                    <x-input-error :messages="$errors->get('dpi')" class="mt-2" />
                                </div>

                                <!-- NIT -->
                                <div>
                                    <x-input-label for="nit" :value="__('NIT')" class="dark:text-gray-200" />
                                    <x-text-input id="nit" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="nit" :value="old('nit')" placeholder="1234567-8" />
                                    <x-input-error :messages="$errors->get('nit')" class="mt-2" />
                                </div>

                                <!-- Fecha de Nacimiento -->
                                <div>
                                    <x-input-label for="birth_date" :value="__('Fecha de Nacimiento')" class="dark:text-gray-200" />
                                    <x-text-input id="birth_date" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="date" name="birth_date" :value="old('birth_date')" />
                                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                </div>

                                <!-- Foto -->
                                <div>
                                    <x-input-label for="photo" :value="__('Foto')" class="dark:text-gray-200" />
                                    <input id="photo" class="block mt-2 w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none" type="file" name="photo" accept="image/*">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG (MAX. 2MB)</p>
                                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información de Contacto</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Teléfono -->
                                <div>
                                    <x-input-label for="phone" :value="__('Teléfono')" class="dark:text-gray-200" />
                                    <x-text-input id="phone" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="phone" :value="old('phone')" placeholder="2234-5678" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <!-- Móvil -->
                                <div>
                                    <x-input-label for="mobile" :value="__('Móvil')" class="dark:text-gray-200" />
                                    <x-text-input id="mobile" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="mobile" :value="old('mobile')" placeholder="5555-1234" />
                                    <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
                                </div>

                                <!-- Dirección -->
                                <div class="md:col-span-2">
                                    <x-input-label for="address" :value="__('Dirección')" class="dark:text-gray-200" />
                                    <textarea id="address" name="address" rows="3" class="block mt-2 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Dirección completa">{{ old('address') }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Información Laboral -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información Laboral</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Puesto -->
                                <div>
                                    <x-input-label for="role" :value="__('Puesto')" class="dark:text-gray-200" />
                                    <select id="role" name="role" required class="block mt-2 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Seleccionar puesto</option>
                                        @foreach($roles as $key => $value)
                                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                </div>

                                <!-- Jefe Directo -->
                                <div>
                                    <x-input-label for="supervisor_id" :value="__('Jefe Directo / Encargado')" class="dark:text-gray-200" />
                                    <select id="supervisor_id" name="supervisor_id" class="block mt-2 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Sin supervisor</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                                {{ $supervisor->full_name }} - {{ $supervisor->role_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('supervisor_id')" class="mt-2" />
                                </div>

                                <!-- Fecha de Contratación -->
                                <div>
                                    <x-input-label for="hire_date" :value="__('Fecha de Contratación')" class="dark:text-gray-200" />
                                    <x-text-input id="hire_date" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="date" name="hire_date" :value="old('hire_date')" required />
                                    <x-input-error :messages="$errors->get('hire_date')" class="mt-2" />
                                </div>

                                <!-- Fecha de Inicio -->
                                <div>
                                    <x-input-label for="start_date" :value="__('Fecha de Inicio')" class="dark:text-gray-200" />
                                    <x-text-input id="start_date" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="date" name="start_date" :value="old('start_date')" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Fecha en que comenzó a trabajar</p>
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4 pt-4">
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <x-primary-button class="px-6">
                                {{ __('Guardar Empleado') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
