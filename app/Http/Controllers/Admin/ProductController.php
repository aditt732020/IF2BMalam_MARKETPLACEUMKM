<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $editProduct = null;
        if ($request->filled('edit')) {
            $editProduct = Product::find($request->integer('edit'));
        }

        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('shop_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Product::categories();

        return view('admin.products', compact('products', 'editProduct', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', array_keys(Product::categories())),
            'shop_name' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        Product::create($validated);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', array_keys(Product::categories())),
            'shop_name' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $product->update($validated);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus.');
    }
}
