@extends('admin.layout')

@section('title', 'Admin Produk')
@section('header', 'Manajemen Produk')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-lg font-bold">{{ $editProduct ? 'Edit Produk' : 'Tambah Produk' }}</h3>
        <form method="POST" action="{{ $editProduct ? route('admin.products.update', $editProduct) : route('admin.products.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @if ($editProduct)
                @method('PUT')
            @endif

            <div>
                <label class="mb-1 block text-sm text-[#7a6050]">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $editProduct->name ?? '') }}" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm text-[#7a6050]">Harga</label>
                <input type="number" name="price" min="0" value="{{ old('price', $editProduct->price ?? 0) }}" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm text-[#7a6050]">Stok</label>
                <input type="number" name="stock" min="0" value="{{ old('stock', $editProduct->stock ?? 0) }}" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-2 pt-6">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editProduct->is_active ?? true))>
                <label class="text-sm text-[#7a6050]">Produk aktif</label>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm text-[#7a6050]">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">{{ old('description', $editProduct->description ?? '') }}</textarea>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="rounded-lg bg-[#c97e3a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#a06020]">
                    {{ $editProduct ? 'Update Produk' : 'Simpan Produk' }}
                </button>
                @if ($editProduct)
                    <a href="{{ route('admin.products') }}" class="rounded-lg border border-[#d8c7b8] px-4 py-2 text-sm font-semibold hover:bg-[#f5eee7]">Batal</a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#e7ddd2] bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-[#f3ebe3] text-left text-[#5a4030]">
                <tr>
                    <th class="px-4 py-3 font-semibold">ID</th>
                    <th class="px-4 py-3 font-semibold">Nama Produk</th>
                    <th class="px-4 py-3 font-semibold">Harga</th>
                    <th class="px-4 py-3 font-semibold">Stok</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Tanggal Dibuat</th>
                    <th class="px-4 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f0e6dc]">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3">{{ $product->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $product->stock }}</td>
                        <td class="px-4 py-3">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td class="px-4 py-3">{{ $product->created_at?->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.products', ['edit' => $product->id]) }}" class="rounded border border-[#d8c7b8] px-2 py-1 text-xs hover:bg-[#f5eee7]">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-[#9a8070]">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
