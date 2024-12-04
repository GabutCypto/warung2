<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Product') }}
        </h2>
    </x-slot>

    <!-- Background for Content -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Category Button -->
            <div class="mb-6 flex justify-end">
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center py-3 px-6 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 transition duration-300 ease-in-out shadow-lg hover:shadow-xl">
                    <span class="mr-2">{{ __('Add Product') }}</span>
                    <!-- Optional Icon if desired -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </a>
            </div>
            

            <div class="bg-white p-8 shadow-lg rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    {{ __('Product List') }}
                </h3>

                @forelse ($products as $product)
                    <div class="flex items-center justify-between bg-white border border-gray-200 p-4 rounded-lg mb-4 shadow-sm hover:shadow-xl transition-shadow duration-300 ease-in-out">
                        <!-- Icon and Name -->
                        <div class="flex items-center gap-4">
                            <img src="{{ Storage::url($product->photo) }}" alt="{{ $product->photo }}" 
                                 class="w-16 h-16 rounded-lg object-cover border border-gray-300 shadow-md">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h4>
                            <p>
                                Rp {{ $product->price }}
                            </p>
                            </div>
                            <p>
                                {{ $product->category->name }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-x-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="py-2 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="py-2 px-5 rounded-full text-white bg-red-700 hover:bg-red-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-500 text-lg font-semibold">
                            {{ __('No categories found.') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
