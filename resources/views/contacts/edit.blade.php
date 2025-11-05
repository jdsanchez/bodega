<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Contacto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('contacts.update', $contact) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Información General -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información General</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre -->
                                <div class="md:col-span-2">
                                    <x-input-label for="name" :value="__('Nombre Completo')" class="dark:text-gray-200" />
                                    <x-text-input id="name" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="name" :value="old('name', $contact->name)" required autofocus placeholder="Ej: Juan Pérez López" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Foto -->
                                <div class="md:col-span-2">
                                    <x-input-label for="photo" :value="__('Foto')" class="dark:text-gray-200" />
                                    <div class="mt-2 flex items-center gap-4">
                                        <div id="photo-preview" class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold overflow-hidden">
                                            @if($contact->photo)
                                                <img id="preview-image" src="{{ Storage::url($contact->photo) }}" alt="{{ $contact->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span id="initials-placeholder">{{ $contact->initials }}</span>
                                                <img id="preview-image" src="" alt="" class="w-full h-full object-cover hidden">
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <input id="photo" class="block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none" type="file" name="photo" accept="image/*">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG (MAX. 2MB). Dejar vacío para mantener la foto actual.</p>
                                        </div>
                                    </div>
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
                                    <x-text-input id="phone" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="phone" :value="old('phone', $contact->phone)" placeholder="2234-5678" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <!-- WhatsApp -->
                                <div>
                                    <x-input-label for="whatsapp" :value="__('WhatsApp')" class="dark:text-gray-200" />
                                    <x-text-input id="whatsapp" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="whatsapp" :value="old('whatsapp', $contact->whatsapp)" placeholder="5555-1234" />
                                    <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <x-input-label for="email" :value="__('Correo Electrónico')" class="dark:text-gray-200" />
                                    <x-text-input id="email" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="email" name="email" :value="old('email', $contact->email)" placeholder="contacto@ejemplo.com" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Website -->
                                <div class="md:col-span-2">
                                    <x-input-label for="website" :value="__('Sitio Web')" class="dark:text-gray-200" />
                                    <x-text-input id="website" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="url" name="website" :value="old('website', $contact->website)" placeholder="https://www.ejemplo.com" />
                                    <x-input-error :messages="$errors->get('website')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Dirección y Ubicación -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Dirección y Ubicación</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Dirección -->
                                <div>
                                    <x-input-label for="address" :value="__('Dirección')" class="dark:text-gray-200" />
                                    <textarea id="address" name="address" rows="3" class="block mt-2 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Dirección completa">{{ old('address', $contact->address) }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <!-- Ubicación Maps/Waze -->
                                <div>
                                    <x-input-label for="maps_url" :value="__('Ubicación (Google Maps o Waze)')" class="dark:text-gray-200" />
                                    <x-text-input id="maps_url" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="url" name="maps_url" :value="old('maps_url', $contact->maps_url)" placeholder="https://maps.google.com/..." />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">URL de Google Maps o Waze para navegación</p>
                                    <x-input-error :messages="$errors->get('maps_url')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Documentos -->
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Documentos de Identificación</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- NIT -->
                                <div>
                                    <x-input-label for="nit" :value="__('NIT')" class="dark:text-gray-200" />
                                    <x-text-input id="nit" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="nit" :value="old('nit', $contact->nit)" placeholder="12345678-9" />
                                    <x-input-error :messages="$errors->get('nit')" class="mt-2" />
                                </div>

                                <!-- DPI -->
                                <div>
                                    <x-input-label for="dpi" :value="__('DPI')" class="dark:text-gray-200" />
                                    <x-text-input id="dpi" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="dpi" :value="old('dpi', $contact->dpi)" placeholder="1234 56789 0101" />
                                    <x-input-error :messages="$errors->get('dpi')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4 pt-4">
                            <a href="{{ route('contacts.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <x-primary-button class="px-6">
                                {{ __('Actualizar Contacto') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Preview de foto
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('preview-image');
                    const initials = document.getElementById('initials-placeholder');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if (initials) initials.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Mostrar iniciales en el placeholder si cambia el nombre
        document.getElementById('name').addEventListener('input', function(e) {
            const name = e.target.value.trim();
            const initialsEl = document.getElementById('initials-placeholder');
            if (name && initialsEl && !document.getElementById('photo').files.length) {
                const words = name.split(' ');
                let initials = '';
                words.forEach(word => {
                    if (word && initials.length < 2) {
                        initials += word[0].toUpperCase();
                    }
                });
                initialsEl.textContent = initials || '?';
            }
        });
    </script>
    @endpush
</x-app-layout>
