<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    public function index(){
        $products = Product::with('category')->orderBy('id', 'DESC')->take(6)->get();
        $categories = Category::all();
        return view('front.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function details(Product $product){
        return view('front.details', [
            'product' => $product,
        ]);
    }

    public function search(Request $request)
{
    $keyword = $request->input('keyword');
    
    // Debug untuk melihat keyword yang dimasukkan
    dd($keyword);

    // Menambahkan LOWER untuk memastikan pencarian tidak terpengaruh kapitalisasi
    $products = Product::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($keyword) . '%'])->get();

    // Debug untuk melihat hasil pencarian produk
    dd($products);

    return view('front.search', [
        'products' => $products,
        'keyword' => $keyword,
    ]);
}

}