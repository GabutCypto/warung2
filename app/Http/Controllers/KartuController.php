<?php

namespace App\Http\Controllers;

use App\Models\Kartu;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class KartuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $user = Auth::user();
    
    // Ambil parameter pencarian dari request
    $search = $request->get('search');

    // Jika user adalah 'owner', tampilkan semua kartu (tidak hanya milik user)
    if ($user->hasRole('owner')) {
        // Jika ada parameter pencarian, filter berdasarkan nama
        $kartus = Kartu::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('id', 'DESC')
        ->get();
    } else {
        // Tampilkan hanya kartu milik pengguna yang sedang login
        $kartus = Kartu::where('user_id', Auth::id())
        ->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('id', 'DESC')
        ->get();
    }

    return view('user.kartu.index', [
        'kartus' => $kartus
    ]);
}

public function print(Kartu $kartu)
{
    // Pastikan hanya pemilik kartu yang bisa mencetak
    if ($kartu->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    return view('user.kartu.print', compact('kartu'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.kartu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:png,jpg,svg,jpeg',
            'about' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Handle the photo upload
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('kartu_photos', 'public');
                $validated['photo'] = $photoPath;
            }

            // Create the slug
            $validated['slug'] = Str::slug($request->name);

            // Menambahkan user_id ke data yang disimpan
            $validated['user_id'] = Auth::id(); // Ambil ID pengguna yang sedang login

            // Create the new kartu
            $newKartu = Kartu::create($validated);

            DB::commit();

            return redirect()->route('user.kartu.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System error!' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kartu $kartu)
{
    // Mendapatkan pemilik kartu
    $owner = $kartu->user;  // Mendapatkan pemilik kartu yang sedang dilihat

    // Mendapatkan semua kartu yang dimiliki oleh pemilik tersebut
    $kartusByOwner = Kartu::where('user_id', $owner->id)->get();

    return view('user.kartu.show', [
        'kartu' => $kartu,           // Detail kartu yang sedang dilihat
        'owner' => $owner,           // Pemilik kartu
        'kartusByOwner' => $kartusByOwner  // Daftar kartu yang dimiliki pemilik kartu ini
    ]);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kartu $kartu)
    {
        return view('user.kartu.edit', [
            'kartu' => $kartu
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kartu $kartu)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'photo' => 'sometimes|image|mimes:png,jpg,svg,jpeg',
            'about' => 'sometimes|string',
        ]);

        DB::beginTransaction();

        try {
            // Handle the photo upload if there's a new file
            if ($request->hasFile('photo')) {
                // Delete the old photo if it exists
                if ($kartu->photo) {
                    Storage::delete('public/' . $kartu->photo);
                }

                // Store the new photo
                $photoPath = $request->file('photo')->store('kartu_photos', 'public');
                $validated['photo'] = $photoPath;
            }

            // Set the slug for the kartu
            $validated['slug'] = Str::slug($request->name);

            // Update the kartu record
            $kartu->update($validated);

            DB::commit();

            return redirect()->route('user.kartu.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System error!' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kartu $kartu)
    {
        try {
            // Delete the photo from storage if it exists
            if ($kartu->photo) {
                Storage::delete('public/' . $kartu->photo);
            }

            // Delete the kartu
            $kartu->delete();

            return redirect()->route('user.kartu.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System error!' . $e->getMessage()],
            ]);
            throw $error;
        }
    }
}