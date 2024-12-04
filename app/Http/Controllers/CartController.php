<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $user = Auth::user();
    
    // Ambil saldo pengguna berdasarkan topup yang sudah dibayar
    $userCredits = \App\Models\Topup::where('user_id', $user->id)
                                    ->where('is_paid', true)
                                    ->sum('amount');

    // Ambil cart item pengguna
    $my_carts = $user->carts()->with('product')->get();

    // Mengirim saldo dan cart ke view
    return view('front.carts', [
        'my_carts' => $my_carts,
        'userCredits' => $userCredits,
    ]);
}

public function processPayment(Request $request)
{
    $user = Auth::user();

    // Ambil saldo pengguna yang sudah dibayar
    $userCredits = \App\Models\Topup::where('user_id', $user->id)
                                    ->where('is_paid', true)
                                    ->sum('amount');

    // Total pembayaran (misalnya, dari total cart)
    $totalPembayaran = $request->input('total_pembayaran'); // Pastikan total pembayaran dari form

    // Cek apakah saldo cukup
    if ($userCredits >= $totalPembayaran) {
        // Proses pembayaran dan kurangi saldo
        \App\Models\Topup::create([
            'user_id' => $user->id,
            'amount' => -$totalPembayaran, // Saldo dikurangi
            'is_paid' => true,  // Pembayaran berhasil
            'payment_proof' => null, // Tidak perlu bukti pembayaran jika menggunakan saldo
        ]);

        return redirect()->route('payment.success')->with('success', 'Pembayaran berhasil!');
    } else {
        // Jika saldo tidak cukup
        return redirect()->route('payment.failure')->with('error', 'Saldo Anda tidak mencukupi!');
    }
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
    public function store( $productId )
    {
        //
        $user = Auth::user();

        $existingCartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingCartItem) {
            return redirect()->route('carts.index');
        }

        DB::beginTransaction();

        try {
            $cart = Cart::updateOrCreate([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);

            $cart->save();

            DB::commit();

            return redirect()->route('carts.index')
                ->with('success', 'Item added to cart.');
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
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
        try {
            if ($cart->icon) {
                Storage::delete('public/' . $cart->photo);
            }

            $cart->delete();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System error!' . $e->getMessage()],
            ]);
            throw $error;
        }
    }
}