<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Category') }}
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
                
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <x-input-label for="name" :value="__('Name')" class="text-lg font-semibold text-gray-700" />
                        <x-text-input 
                            id="name" 
                            class="block mt-1 w-full border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" 
                            type="text" 
                            name="name" 
                            value="{{ $category->name }}" 
                            required 
                            autofocus 
                            autocomplete="name" 
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Icon -->
                    <div class="mb-6">
                        <x-input-label for="icon" :value="__('Icon')" class="text-lg font-semibold text-gray-700" />
                        <div class="mb-4">
                            <!-- Display current icon -->
                            <img src="{{ Storage::url($category->icon) }}" alt="{{ $category->name }}" class="w-32 h-32 object-cover rounded-lg">
                        </div>
                        <x-text-input 
                            id="icon" 
                            class="block w-full mt-1 border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" 
                            type="file" 
                            name="icon" 
                            autocomplete="icon" 
                        />
                        <x-input-error :messages="$errors->get('icon')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between mt-4">
                        <!-- Update Button -->
                        <x-primary-button class="py-3 px-6 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Update Category') }}
                        </x-primary-button>

                        <!-- Cancel Button -->
                        <a href="{{ route('admin.categories.index') }}" class="py-3 px-6 rounded-full text-gray-700 bg-gray-200 hover:bg-gray-300 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
