@extends('admin.layout')

@section('title', 'Produk Kopi - Admin KopiNusantara')
@section('header', 'Manajemen Produk Kopi')
@section('subtitle', 'Kelola katalog produk sesuai kategori di toko pembeli')

@section('content')
    @include('admin.partials.alert')

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.products') }}" class="flex flex-1 flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk atau toko UMKM..."
                   class="min-w-[200px] flex-1 rounded-xl border border-[#e0d8cc] bg-white px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
            <select name="category" class="rounded-xl border border-[#e0d8cc] bg-white px-3 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none">
                <option value="">Semua kategori</option>
                @foreach ($categories as $key => $label)
                    <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-xl bg-[#3a2010] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#5a4030]">Cari</button>
            @if (request()->hasAny(['search', 'category']))
                <a href="{{ route('admin.products') }}" class="rounded-xl border border-[#d8c7b8] px-4 py-2.5 text-sm font-semibold hover:bg-[#f5eee7]">Reset</a>
            @endif
        </form>
    </div>

    <div class="mb-6 rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm sm:p-6">
        <h3 class="mb-1 text-base font-bold text-[#3a2010]">{{ $editProduct ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
        <p class="mb-5 text-xs text-[#9a8070]">Isi informasi produk seperti yang ditampilkan di halaman pembeli (nama, kategori, toko UMKM, gambar).</p>
        <form method="POST" action="{{ $editProduct ? route('admin.products.update', $editProduct) : route('admin.products.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @if ($editProduct) @method('PUT') @endif

            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $editProduct->name ?? '') }}"
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Kategori</label>
                <select name="category" class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none">
                    @foreach ($categories as $key => $label)
                        <option value="{{ $key }}" @selected(old('category', $editProduct->category ?? 'biji_kopi') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Nama Toko UMKM</label>
                <input type="text" name="shop_name" value="{{ old('shop_name', $editProduct->shop_name ?? '') }}" placeholder="Contoh: Rumah Kopi Aceh"
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('shop_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">URL Gambar Produk</label>
                <input type="url" name="image_url" value="{{ old('image_url', $editProduct->image_url ?? '') }}" placeholder="https://..."
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('image_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Harga (Rp)</label>
                <input type="number" name="price" min="0" value="{{ old('price', $editProduct->price ?? 0) }}"
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Stok</label>
                <input type="number" name="stock" min="0" value="{{ old('stock', $editProduct->stock ?? 0) }}"
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-2 md:col-span-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $editProduct->is_active ?? true)) class="rounded border-[#e0d8cc] text-[#c57d38] focus:ring-[#c57d38]">
                <label for="is_active" class="text-sm font-medium text-[#5a4030]">Tampilkan di toko pembeli (produk aktif)</label>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Deskripsi Produk</label>
                <textarea name="description" rows="3" class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">{{ old('description', $editProduct->description ?? '') }}</textarea>
            </div>
            <div class="flex gap-2 md:col-span-2">
                <button type="submit" class="rounded-xl bg-[#c57d38] px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#a66528]">
                    {{ $editProduct ? 'Simpan Perubahan' : 'Tambah Produk' }}
                </button>
                @if ($editProduct)
                    <a href="{{ route('admin.products') }}" class="rounded-xl border border-[#d8c7b8] px-5 py-2.5 text-sm font-semibold hover:bg-[#f5eee7]">Batal</a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#e7ddd2] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#fdf9f4] text-left text-xs uppercase tracking-wider text-[#9a8070]">
                    <tr>
                        <th class="px-4 py-3 font-semibold sm:px-6">Produk</th>
                        <th class="px-4 py-3 font-semibold">Kategori</th>
                        <th class="px-4 py-3 font-semibold">Toko UMKM</th>
                        <th class="px-4 py-3 font-semibold">Harga</th>
                        <th class="px-4 py-3 font-semibold">Stok</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e6dc]">
                    @forelse ($products as $product)
                        <tr class="hover:bg-[#fdf9f4]/50">
                            <td class="px-4 py-3 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-[#f3ebe3]">
                                        @if ($product->resolveImageUrl())
                                            <img src="{{ $product->resolveImageUrl() }}" alt="{{ $product->name }}" class="h-full w-full object-cover" loading="lazy"
                                                 onerror="this.src='https://images.unsplash.com/photo-1507133750040-4a8f57021571?w=200&h=200&fit=crop&q=80'">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-[#c57d38] font-bold text-lg">K</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#3a2010]">{{ $product->name }}</p>
                                        <p class="text-xs text-[#9a8070]">ID #{{ $product->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-[#fdf3e7] px-2.5 py-0.5 text-xs font-semibold text-[#936232]">{{ $product->category_label }}</span>
                            </td>
                            <td class="px-4 py-3 text-[#5a4030]">{{ $product->shop_name ?? '-' }}</td>
                            <td class="px-4 py-3 font-semibold text-[#c57d38]">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ $product->stock < 10 ? 'font-bold text-red-600' : '' }}">{{ $product->stock }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($product->is_active)
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Aktif</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.products', ['edit' => $product->id]) }}" class="rounded-lg border border-[#d8c7b8] px-2.5 py-1 text-xs font-semibold hover:bg-[#f5eee7]">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-2.5 py-1 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-[#9a8070]">Belum ada produk. Tambahkan produk kopi pertama Anda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $products->links('admin.partials.pagination', ['label' => 'produk']) }}
@endsection
