<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('users.index') }}" class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit member') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-200">
                <div class="p-8">
                    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Preview -->
                        <div class="flex items-center space-x-4 pb-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-semibold text-xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Full name')" class="dark:text-gray-200" />
                            <x-text-input id="name" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="text" name="name" :value="old('name', $user->name)" required autofocus placeholder="Enter full name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email address')" class="dark:text-gray-200" />
                            <x-text-input id="email" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="email" name="email" :value="old('email', $user->email)" required placeholder="email@example.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div>
                            <x-input-label for="role" :value="__('Role')" class="dark:text-gray-200" />
                            <select id="role" name="role" class="block mt-2 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                @foreach($roles as $roleKey => $roleName)
                                    <option value="{{ $roleKey }}" {{ old('role', $user->role) == $roleKey ? 'selected' : '' }}>{{ $roleName }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Password (Optional) -->
                        <div>
                            <x-input-label for="password" :value="__('New password (optional)')" class="dark:text-gray-200" />
                            <x-text-input id="password" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="password" name="password" placeholder="Leave blank to keep current password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Only fill this if you want to change the password</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm new password')" class="dark:text-gray-200" />
                            <x-text-input id="password_confirmation" class="block mt-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" type="password" name="password_confirmation" placeholder="Confirm new password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button class="px-6">
                                {{ __('Save changes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
