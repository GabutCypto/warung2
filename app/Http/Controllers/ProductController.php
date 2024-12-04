<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::with('category')->orderBy('id', 'DESC')->get();
        return view('admin.products.index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.products.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'about' => 'required|string',
            'category_id' => 'required|integer',
            'price' => 'required|integer',
            'photo' => 'required|image|mimes:png,jpg,svg,jpeg',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('product_photos', 'public');
                $validated['photo'] = $photoPath;
            }
            $validated['slug'] = Str::slug($request->name);

            $newProduct = Product::create($validated);

            DB::commit();

            return redirect()->route('admin.products.index');
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
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        $categories = Category::all();
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'about' => 'required|string',
            'category_id' => 'required|integer',
            'price' => 'required|integer',
            'photo' => 'sometimes|image|mimes:png,jpg,svg,jpeg',
        ]);
    
        DB::beginTransaction();
    
        try {
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($product->photo) {
                    Storage::delete('public/' . $product->photo);
                }
    
                // Simpan foto baru
                $photoPath = $request->file('photo')->store('product_photos', 'public');
                $validated['photo'] = $photoPath;
            }
    
            // Generate slug dari name
            $validated['slug'] = Str::slug($validated['name']);
    
            // Update produk
            $product->update($validated);
    
            DB::commit();
    
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'system_error' => 'System error: ' . $e->getMessage(),
            ]);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        try {
            // Hapus foto kategori dari storage jika ada
            if ($product->photo) {
                Storage::delete('public/' . $product->photo);
            }

            // Hapus kategori
            $product->delete();
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