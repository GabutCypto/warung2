<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Kartu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-lg rounded-lg">

                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="py-3 w-full rounded-3xl bg-red-500 text-white mb-4">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                
                <form method="POST" action="{{ route('user.kartu.store') }}" enctype="multipart/form-data">
                    @csrf
            
                    <!-- Name -->
                    <div class="mb-6">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
            
                    <!-- About -->
                    <div class="mt-4 mb-6">
                        <x-input-label for="about" :value="__('About')" />
                        <textarea id="about" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500" name="about" required>{{ old('about') }}</textarea>
                        <x-input-error :messages="$errors->get('about')" class="mt-2" />
                    </div>
            
                    <!-- Photo -->
                    <div class="mt-4 mb-6">
                        <x-input-label for="photo" :value="__('Photo')" />
                        <x-text-input id="photo" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500" type="file" name="photo" required />
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>
            
                    <!-- Buttons -->
                    <div class="flex items-center justify-between mt-4">
                        <!-- Add Button -->
                        <x-primary-button class="py-3 px-6 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Add New Kartu') }}
                        </x-primary-button>

                        <!-- Cancel Button -->
                        <a href="{{ route('user.kartu.index') }}" class="py-3 px-6 rounded-full text-gray-700 bg-gray-200 hover:bg-gray-300 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
