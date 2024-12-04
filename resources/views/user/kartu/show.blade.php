<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kartu Detail') }}
        </h2>
    </x-slot>

    <!-- Background for Content -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Menu untuk Owner dan Warung Owner -->
            @role('owner|warung_owner')
                <div class="flex justify-end mb-6">
                    <x-nav-link :href="route('product.purchase.create')" :active="request()->routeIs('product.purchase.create')" class="py-2 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none transition duration-300">
                        {{ __('Beli Produk') }}
                    </x-nav-link>
                </div>
            @endrole

            <!-- Detail Kartu -->
            <div class="bg-white p-8 shadow-lg rounded-lg mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    {{ __('Kartu Detail') }}
                </h3>

                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ Storage::url($kartu->photo) }}" alt="{{ $kartu->name }}" class="w-32 h-32 rounded-lg object-cover border border-gray-300 shadow-md">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">{{ $kartu->name }}</h4>
                        <p class="text-sm text-gray-500">{{ __('Created on') }}: {{ $kartu->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="text-lg text-gray-800 mb-4">
                    <strong>{{ __('About') }}:</strong> {{ $kartu->about }}
                </div>
                
                <!-- Pemilik Kartu -->
                <div class="text-lg text-gray-800 mb-4">
                    <strong>{{ __('Owner') }}:</strong> {{ $owner->name }}
                </div>

                <!-- Menampilkan Saldo jika yang mengakses adalah Owner -->
                @role('owner')
                    <div class="mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800">
                            {{ __('Current Balance of the Owner:') }} 
                            <span class="text-xl font-semibold text-green-600">
                                Rp {{ number_format($owner->topups()->where('is_paid', true)->sum('amount'), 0, ',', '.') }}
                            </span>
                        </h3>
                    </div>
                @endrole
            </div>

            <!-- Daftar Kartu yang Dimiliki oleh Pemilik -->
            <div class="bg-white p-8 shadow-lg rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    {{ __('Other Cards by') }} {{ $owner->name }}
                </h3>

                @forelse ($kartusByOwner as $ownedKartu)
                    <div class="flex items-center justify-between bg-white border border-gray-200 p-4 rounded-lg mb-4 shadow-sm hover:shadow-xl transition-shadow duration-300 ease-in-out">
                        <!-- Photo and Name -->
                        <div class="flex items-center gap-4">
                            <img src="{{ Storage::url($ownedKartu->photo) }}" alt="{{ $ownedKartu->name }}" class="w-16 h-16 rounded-lg object-cover border border-gray-300 shadow-md">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $ownedKartu->name }}</h4>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-500 text-lg font-semibold">
                            {{ __('No other cards found.') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
