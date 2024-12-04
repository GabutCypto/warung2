<?php

namespace App\Http\Controllers;

use App\Models\ProductTransaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class ProductTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $user = Auth::user();

        if($user->hasRole('buyer')){
            $product_transactions = $user->product_transactions()->orderBy('id', 'DESC')->get();
        }
        else{
            $product_transactions = ProductTransaction::orderBy('id', 'DESC')->get();
        }
        
        return view('admin.product_transactions.index', [
            'product_transactions' => $product_transactions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'notes' => 'required|string|max:65535',
        'proof' => 'required|image|mimes:png,jpg,jpeg'
    ]);

    DB::beginTransaction();

    try {
        $subTotalRupiah = 0;
        $deliveryFeeRupiah = 1000;

        $cartItems = $user->carts;

        // Menghitung subtotal harga produk
        foreach ($cartItems as $item) {
            $subTotalRupiah += $item->product->price;
        }

        // Menghitung pajak dan asuransi
        $taxRupiah = (int)round(11 * $subTotalRupiah / 100); // 11% pajak
        $insuranceRupiah = (int)round(23 * $subTotalRupiah / 100); // 23% asuransi

        // Menghitung grand total
        $grandTotalRupiah = $subTotalRupiah + $taxRupiah + $insuranceRupiah + $deliveryFeeRupiah;

        $validated['user_id'] = $user->id;
        $validated['total_amount'] = $grandTotalRupiah;
        $validated['is_paid'] = false;

        // Menyimpan bukti pembayaran
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('payment_proofs', 'public');
            $validated['proof'] = $proofPath;
        }

        // Menyimpan transaksi baru
        $newTransaction = ProductTransaction::create($validated);

        // Menyimpan detail transaksi untuk setiap item di keranjang
        foreach ($cartItems as $item) {
            TransactionDetail::create([
                'product_transaction_id' => $newTransaction->id,
                'product_id' => $item->product->id,
                'price' => $item->product->price,
            ]);

            // Menghapus item dari keranjang setelah transaksi
            $item->delete();
        }

        DB::commit();

        return redirect()->route('product_transactions.index');
    } catch (\Exception $e) {
        DB::rollBack();
        $error = ValidationException::withMessages([
            'system_error' => ['System error! ' . $e->getMessage()],
        ]);
        throw $error;
    }
}


    /**
     * Display the specified resource.
     */
    public function show(ProductTransaction $productTransaction)
    {
        //
        $productTransaction = ProductTransaction::with('transactionDetails.product')->find($productTransaction->id);
        return view('admin.product_transactions.details', [
            'productTransaction' => $productTransaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductTransaction $productTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductTransaction $productTransaction)
    {
        //
        $productTransaction->update([
            'is_paid' => true,
        ]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductTransaction $productTransaction)
    {
        //
    }
}