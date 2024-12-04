<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ Auth::user()->hasRole('owner') ? __('Topup Requests') : __('My Topups') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-8">

                <!-- Menampilkan Total Saldo hanya untuk user biasa (bukan owner) -->
                @unless(Auth::user()->hasRole('owner'))
                    <div class="mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800">
                            {{ __('Current Balance:') }} 
                            <span class="text-xl font-semibold text-green-600">
                                Rp {{ number_format(Auth::user()->topups()->where('is_paid', true)->sum('amount'), 0, ',', '.') }}
                            </span>
                        </h3>
                    </div>
                @endunless

                <!-- Tampilan untuk Owner -->
                @role('owner')
                    <h3 class="text-3xl font-semibold text-gray-800 mb-8 border-b pb-4">
                        {{ __('Topup Requests') }}
                    </h3>

                    @forelse ($topups as $topup)
                        <div class="flex items-center justify-between bg-white border border-gray-200 p-6 rounded-lg mb-6 shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                            <div class="flex flex-col gap-2 w-3/5">
                                <p class="text-sm text-gray-500">{{ __('Amount') }}</p>
                                <p class="text-xl font-semibold text-gray-900">Rp {{ number_format($topup->amount, 0, ',', '.') }}</p>

                                <p class="text-sm text-gray-500">{{ __('Requested at') }}</p>
                                <p class="text-md text-gray-700">{{ $topup->created_at->format('d M Y H:i') }}</p>
                            </div>

                            <div class="flex flex-col justify-center items-end w-1/4">
                                @if($topup->is_paid)
                                    <span class="py-2 px-5 rounded-full text-white bg-green-500 text-sm font-semibold">
                                        {{ __('SUCCESS') }}
                                    </span>
                                @else
                                    <span class="py-2 px-5 rounded-full text-white bg-yellow-400 text-sm font-semibold">
                                        {{ __('Pending') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Link to Detail Page -->
                            <div class="flex justify-end w-1/4">
                                <a href="{{ route('topups.show', $topup) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    {{ __('Lihat Detail') }}
                                </a>
                            </div>

                            @if(!$topup->is_paid)
                                <form method="POST" action="{{ route('topups.update', $topup) }}" class="flex items-center justify-end w-full">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="py-2 px-5 ml-2 rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition duration-300">
                                        {{ __('Approve Payment') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-500 text-lg font-semibold">{{ __('No topup requests') }}</p>
                        </div>
                    @endforelse

                <!-- Tampilan untuk Buyer -->
                @else
                    <h3 class="text-3xl font-semibold text-gray-800 mb-8 border-b pb-4">
                        {{ __('Create New Topup') }}
                    </h3>

                    <form method="POST" action="{{ route('topups.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Topup Amount') }}</label>
                            <input type="number" id="amount" name="amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Rp 0" required>
                        </div>

                        <div class="mb-4">
                            <label for="proof" class="block text-sm font-medium text-gray-700">{{ __('Payment Proof') }}</label>
                            <input type="file" id="proof" name="proof" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="py-3 px-6 rounded-full text-white bg-green-600 hover:bg-green-700 focus:outline-none transition duration-300">
                                {{ __('Submit Topup') }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-800">{{ __('My Topups') }}</h3>

                        @forelse ($topups as $topup)
                            <div class="flex items-center justify-between bg-white border border-gray-200 p-6 rounded-lg mb-6 shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                                <div class="flex flex-col gap-2 w-3/5">
                                    <p class="text-sm text-gray-500">{{ __('Amount') }}</p>
                                    <p class="text-xl font-semibold text-gray-900">Rp {{ number_format($topup->amount, 0, ',', '.') }}</p>

                                    <p class="text-sm text-gray-500">{{ __('Requested at') }}</p>
                                    <p class="text-md text-gray-700">{{ $topup->created_at->format('d M Y H:i') }}</p>
                                </div>

                                <div class="flex flex-col justify-center items-end w-1/4">
                                    @if($topup->is_paid)
                                        <span class="py-2 px-5 rounded-full text-white bg-green-500 text-sm font-semibold">
                                            {{ __('SUCCESS') }}
                                        </span>
                                    @else
                                        <span class="py-2 px-5 rounded-full text-white bg-yellow-400 text-sm font-semibold">
                                            {{ __('Pending') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-gray-500 text-lg font-semibold">{{ __('No topups found') }}</p>
                            </div>
                        @endforelse
                    </div>
                @endrole
            </div>
        </div>
    </div>
</x-app-layout>