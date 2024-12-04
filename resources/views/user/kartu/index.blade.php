<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Kartu') }}
        </h2>
    </x-slot>

    <!-- Background for Content -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Kartu Button (only for non-owner) -->
            @if (!Auth::user()->hasRole('owner'))
                <div class="mb-6 flex justify-end">
                    <a href="{{ route('user.kartu.create') }}" 
                       class="inline-flex items-center py-3 px-6 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 transition duration-300 ease-in-out shadow-lg hover:shadow-xl">
                        <span class="mr-2">{{ __('Add Kartu') }}</span>
                        <!-- Optional Icon if desired -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>
                </div>
            @endif

            <div class="bg-white p-8 shadow-lg rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    {{ __('Kartu List') }}
                </h3>

                @if (Auth::user()->hasRole('owner'))
                    <!-- Search Form for Owner -->
                    <form method="GET" action="{{ route('user.kartu.index') }}" class="mb-6">
                        <div class="flex items-center">
                            <input type="text" name="search" value="{{ request()->search }}" 
                                   class="w-full py-3 px-6 rounded-full border border-gray-300 focus:ring-4 focus:outline-none focus:ring-indigo-300" 
                                   placeholder="{{ __('Search Kartu...') }}">
                            <button type="submit" 
                                    class="ml-4 py-3 px-6 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 transition duration-300 ease-in-out">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </form>
                @endif

                @forelse ($kartus as $kartu)
                    <div class="flex items-center justify-between bg-white border border-gray-200 p-4 rounded-lg mb-4 shadow-sm hover:shadow-xl transition-shadow duration-300 ease-in-out">
                        <!-- Photo and Name -->
                        <div class="flex items-center gap-4">
                            <img src="{{ Storage::url($kartu->photo) }}" alt="{{ $kartu->name }}" 
                                 class="w-16 h-16 rounded-lg object-cover border border-gray-300 shadow-md">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $kartu->name }}</h4>
                        </div>

                        <!-- Action Buttons -->
                        @if (Auth::id() === $kartu->user_id) <!-- Only show Edit/Delete for the card owner -->
                            <div class="flex items-center gap-x-3">
                                <a href="{{ route('user.kartu.edit', $kartu) }}" 
                                   class="py-2 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('user.kartu.destroy', $kartu) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="py-2 px-5 rounded-full text-white bg-red-700 hover:bg-red-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                                        Delete
                                    </button>
                                </form>
                                <!-- Print Button for Card Owner -->
                                <a href="{{ route('user.kartu.print', $kartu) }}" 
                                   class="py-2 px-5 rounded-full text-white bg-blue-700 hover:bg-blue-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                                    Print Kartu
                                </a>
                            </div>
                        @elseif (Auth::user()->hasRole('owner'))
                            <!-- For owner, show only details link -->
                            <div class="flex items-center gap-x-3">
                                <a href="{{ route('user.kartu.show', $kartu) }}" 
                                   class="py-2 px-5 rounded-full text-white bg-green-700 hover:bg-green-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                                    View Details
                                </a>
                            </div>
                        @else
                            <div class="flex items-center gap-x-3">
                                <!-- For other users, show only details link -->
                                <a href="{{ route('user.kartu.show', $kartu) }}" 
                                   class="py-2 px-5 rounded-full text-white bg-green-700 hover:bg-green-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                                    View Details
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-500 text-lg font-semibold">
                            {{ __('No kartu found.') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
