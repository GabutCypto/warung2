<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ Auth::user()->hasRole('owner') ? __('Orderan') : __('Pesanan') }}
        </h2>
    </x-slot>

    <!-- Background for Content -->
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-lg rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">
                    {{ __('Orderan List') }}
                </h3>

                <!-- Transactions List -->
                @forelse ($product_transactions as $transaction)
                    <div class="flex items-center justify-between bg-gray-50 border border-gray-200 p-5 rounded-lg mb-4 shadow-sm hover:shadow-lg transition duration-300">
                        <!-- Transaction Info -->
                        <div class="flex flex-col gap-2">
                            <p class="text-sm text-gray-500">{{ __('Total Transaksi') }}</p>
                            <p class="text-lg font-semibold text-gray-800">Rp {{ $transaction->total_amount }}</p>

                            <p class="text-sm text-gray-500">{{ __('Tanggal Transaksi') }}</p>
                            <p class="text-lg text-gray-700">{{ $transaction->created_at }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            @if($transaction->is_paid)
                                <span class="py-2 px-5 rounded-full text-white bg-green-500 text-sm font-semibold">
                                    {{ __('SUCCESS') }}
                                </span>
                            @else
                                <span class="py-2 px-5 rounded-full text-white bg-orange-500 text-sm font-semibold">
                                    {{ __('Pending') }}
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <a href="{{ route('product_transactions.show', $transaction) }}" class="py-2 px-5 rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition duration-300 shadow-md">
                            {{ __('View Details') }}
                        </a>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-500 text-lg font-semibold">
                            {{ __('Tidak ada orderan') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
