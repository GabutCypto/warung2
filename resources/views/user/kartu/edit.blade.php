<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kartu') }}
        </h2>
    </x-slot>

    <!-- Background for Content -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-lg rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    {{ __('Edit Kartu: ') }} {{ $kartu->name }}
                </h3>

                <!-- Form Edit Kartu -->
                <form action="{{ route('user.kartu.update', $kartu) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nama Kartu -->
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">
                            {{ __('Kartu Name') }}
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $kartu->name) }}" 
                               class="w-full py-3 px-6 rounded-lg border border-gray-300 focus:ring-4 focus:outline-none focus:ring-indigo-300 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Kartu -->
                    <div class="mb-6">
                        <label for="photo" class="block text-gray-700 text-sm font-semibold mb-2">
                            {{ __('Upload Kartu Photo') }}
                        </label>
                        <input type="file" id="photo" name="photo" 
                               class="w-full py-3 px-6 rounded-lg border border-gray-300 focus:ring-4 focus:outline-none focus:ring-indigo-300 @error('photo') border-red-500 @enderror">
                        @error('photo')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        @if ($kartu->photo)
                            <div class="mt-4">
                                <p class="text-gray-600">{{ __('Current Photo:') }}</p>
                                <img src="{{ Storage::url($kartu->photo) }}" alt="{{ $kartu->name }}" class="w-32 h-32 object-cover rounded-lg border border-gray-300 mt-2">
                            </div>
                        @endif
                    </div>

                    <!-- Catatan -->
                    <div class="mb-6">
                        <label for="notes" class="block text-gray-700 text-sm font-semibold mb-2">
                            {{ __('Notes') }}
                        </label>
                        <textarea id="notes" name="notes" rows="4" 
                                  class="w-full py-3 px-6 rounded-lg border border-gray-300 focus:ring-4 focus:outline-none focus:ring-indigo-300 @error('notes') border-red-500 @enderror">{{ old('notes', $kartu->notes) }}</textarea>
                        @error('notes')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="py-3 px-6 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Update Kartu') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
