<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['image_url'] = $request->file('image')->store('products', 'public');
        unset($validated['image']);

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
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($product->image_url);
            $validated['image_url'] = $request->file('image')->store('products', 'public');
        }

        unset($validated['image']);
        $product->update($validated);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->deleteStoredImage($product->image_url);
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus.');
    }

    private function deleteStoredImage(?string $imageUrl): void
    {
        if (! $imageUrl || $this->isExternalUrl($imageUrl)) {
            return;
        }

        Storage::disk('public')->delete($imageUrl);
    }

    private function isExternalUrl(string $value): bool
    {
        return str_starts_with($value, 'http://') || str_starts_with($value, 'https://');
    }
}
