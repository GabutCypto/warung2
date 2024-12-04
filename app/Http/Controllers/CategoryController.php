<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:png,jpg,svg,jpeg',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('category_icons', 'public');
                $validated['icon'] = $iconPath;
            }
            $validated['slug'] = Str::slug($request->name);

            $newCategory = Category::create($validated);

            DB::commit();

            return redirect()->route('admin.categories.index');
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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'icon' => 'sometimes|image|mimes:png,jpg,svg,jpeg',
        ]);

        DB::beginTransaction();

        try {
            // Cek apakah ada file icon baru yang diupload
            if ($request->hasFile('icon')) {
                // Hapus foto lama jika ada
                if ($category->icon) {
                    Storage::delete('public/' . $category->icon);
                }

                // Simpan foto baru
                $iconPath = $request->file('icon')->store('category_icons', 'public');
                $validated['icon'] = $iconPath;
            }

            // Set slug
            $validated['slug'] = Str::slug($request->name);

            // Update kategori
            $category->update($validated);

            DB::commit();

            return redirect()->route('admin.categories.index');
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
    public function destroy(Category $category)
    {
        try {
            // Hapus foto kategori dari storage jika ada
            if ($category->icon) {
                Storage::delete('public/' . $category->icon);
            }

            // Hapus kategori
            $category->delete();
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