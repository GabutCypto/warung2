<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Detail Transaksi') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-12 px-6 sm:px-8 lg:px-12">
        <!-- Daftar Item -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
            <h3 class="text-2xl font-bold text-gray-800">{{ __('Item dalam Transaksi') }}</h3>
            <ul class="mt-4 space-y-4">
                @foreach ($productTransaction->transactionDetails as $detail)
                    <li class="flex justify-between items-center">
                        <div class="flex items-center">
                            <img src="{{ Storage::url($detail->product->photo) }}" alt="{{ $detail->product->name }}" class="w-16 h-16 rounded-md object-cover mr-4">
                            <span class="font-medium text-gray-800">{{ $detail->product->name }}</span>
                        </div>
                        <span class="text-gray-600">Rp {{ $productTransaction->total_amount }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Informasi Transaksi -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
            <h3 class="text-2xl font-bold text-gray-800">{{ __('Ringkasan Transaksi') }}</h3>
            <div class="mt-4">
                <div class="flex justify-between">
                    <p class="text-gray-600">Tanggal</p>
                    <p class="font-semibold text-gray-800">{{ $productTransaction->created_at }}</p>
                </div>
                <div class="flex justify-between mt-2">
                    <p class="text-gray-600">Total Harga</p>
                    <p class="font-semibold text-gray-800">Rp {{ $productTransaction->total_amount }}</p>
                </div>
                <div class="flex justify-between mt-2">
                    <p class="text-gray-600">Status Pembayaran</p>
                    <p class="font-semibold text-gray-800">
                        <span class="py-1 px-3 rounded-full text-sm {{ $productTransaction->is_paid ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                            {{ $productTransaction->is_paid ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Informasi Pengiriman -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
            <h3 class="text-2xl font-bold text-gray-800">{{ __('Informasi Pengiriman') }}</h3>
            <div class="mt-4">
                <p class="text-gray-600">Nama Penerima</p>
                <p class="font-semibold text-gray-800">{{ $productTransaction->name }}</p>
                <p class="text-gray-600 mt-2">Catatan</p>
                <p class="font-semibold text-gray-800">{{ $productTransaction->notes }}</p>
            </div>
        </div>

        <!-- Bukti Pembayaran -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
            <h3 class="text-2xl font-bold text-gray-800">{{ __('Bukti Pembayaran') }}</h3>
            @if($productTransaction->proof)
                <div class="mt-4">
                    <img src="{{ Storage::url($productTransaction->proof) }}" alt="Bukti Pembayaran" class="w-full h-auto rounded-md shadow-sm">
                </div>
            @else
                <p class="text-gray-600 mt-4">Belum ada bukti pembayaran.</p>
            @endif
        </div>

        <!-- Tindakan -->
        <div class="flex justify-end space-x-4">
            @role('owner')
                @if($productTransaction->is_paid)
                    <a href="https://wa.me/?text=Halo, saya ingin menghubungi Anda mengenai pesanan saya."
                       class="bg-green-500 text-white py-2 px-4 rounded-md text-sm hover:bg-green-600">
                        Hubungi via WhatsApp
                    </a>
                @else
                    <form method="POST" action="{{ route('product_transactions.update', $productTransaction) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" 
                                class="bg-blue-500 text-white py-2 px-4 rounded-md text-sm hover:bg-blue-600">
                            Setujui Pembayaran
                        </button>
                    </form>
                @endif
            @endrole
        </div>
    </div>
</x-app-layout>
