<?php

// app/Http/Controllers/TopupController.php

namespace App\Http\Controllers;

use App\Models\Topup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class TopupController extends Controller
{
    // Menampilkan form untuk membuat permohonan topup
    public function create()
    {
        return view('topups.create');  // Mengarahkan ke halaman pembuatan topup
    }

    // Menyimpan permohonan topup
    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'amount' => 'required|numeric|min:1000',
        'proof' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:2048', // Batasan file
    ]);

    // Simpan bukti pembayaran jika ada
    if ($request->hasFile('proof')) {
        $filePath = $request->file('proof')->store('payment_proofs', 'public');
    } else {
        $filePath = null;  // Jika tidak ada bukti pembayaran
    }

    // Simpan data topup
    Topup::create([
        'user_id' => auth()->id(),  // Relasi dengan pengguna yang login
        'amount' => $request->amount,
        'payment_proof' => $filePath,
        'is_paid' => false,  // Status awal adalah belum dibayar
    ]);

    return redirect()->route('topups.index')->with('success', 'Topup request submitted successfully.');
}

    // Menampilkan daftar topup untuk user atau owner
    public function index()
    {
        $user = Auth::user();

        // Jika role adalah 'owner', tampilkan semua topup
        // Jika role adalah 'buyer', tampilkan hanya topup milik user tersebut
        if ($user->hasRole('owner')) {
            $topups = Topup::orderBy('created_at', 'desc')->get();
        } else {
            $topups = $user->topups()->orderBy('created_at', 'desc')->get();
        }

        return view('topups.index', compact('topups'));
    }

    // Mengupdate status topup menjadi 'sukses' setelah diverifikasi oleh owner
    public function update(Request $request, Topup $topup)
{
    // Pastikan hanya owner yang bisa mengubah status topup
    if (Auth::user()->hasRole('owner')) {
        
        // Periksa apakah topup belum disetujui (is_paid == false)
        if (!$topup->is_paid) {
            $topup->is_paid = true;  // Ubah status topup menjadi sukses
            $topup->save();

            return redirect()->route('topups.index')->with('success', 'Topup approved successfully!');
        }

        // Jika sudah disetujui sebelumnya
        return redirect()->route('topups.index')->with('info', 'Topup has already been approved.');
    }

    return redirect()->route('topups.index')->with('error', 'You do not have permission to approve this topup.');
}


    public function show(Topup $topup)
{
    // Pastikan topup yang diminta milik pengguna yang sedang login atau owner
    if (Auth::user()->id !== $topup->user_id && !Auth::user()->hasRole('owner')) {
        return redirect()->route('topups.index')->with('error', 'You are not authorized to view this topup.');
    }

    return view('topups.show', compact('topup'));
}
}