@extends('admin.layout')

@section('title', 'Admin Pesanan')
@section('header', 'Manajemen Pesanan')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-lg font-bold">{{ $editOrder ? 'Edit Pesanan' : 'Tambah Pesanan' }}</h3>
        <form method="POST" action="{{ $editOrder ? route('admin.orders.update', $editOrder) : route('admin.orders.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @if ($editOrder)
                @method('PUT')
            @endif

            <div>
                <label class="mb-1 block text-sm text-[#7a6050]">Pembeli</label>
                <select name="buyer_id" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                    <option value="">Pilih pembeli</option>
                    @foreach ($buyers as $buyer)
                        <option value="{{ $buyer->id }}" @selected(old('buyer_id', $editOrder->buyer_id ?? null) == $buyer->id)>{{ $buyer->name }} ({{ $buyer->email }})</option>
                    @endforeach
                </select>
                @error('buyer_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm text-[#7a6050]">Produk</label>
                <select name="product_id" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                    <option value="">Pilih produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id', $editOrder->product_id ?? null) == $product->id)>{{ $product->name }} - Rp{{ number_format($product->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
                @error('product_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm text-[#7a6050]">Jumlah</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', $editOrder->quantity ?? 1) }}" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                @error('quantity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm text-[#7a6050]">Status</label>
                <select name="status" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                    @foreach (['pending', 'paid', 'shipped', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $editOrder->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="rounded-lg bg-[#c97e3a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#a06020]">
                    {{ $editOrder ? 'Update Pesanan' : 'Simpan Pesanan' }}
                </button>
                @if ($editOrder)
                    <a href="{{ route('admin.orders') }}" class="rounded-lg border border-[#d8c7b8] px-4 py-2 text-sm font-semibold hover:bg-[#f5eee7]">Batal</a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#e7ddd2] bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-[#f3ebe3] text-left text-[#5a4030]">
                <tr>
                    <th class="px-4 py-3 font-semibold">ID</th>
                    <th class="px-4 py-3 font-semibold">Pembeli</th>
                    <th class="px-4 py-3 font-semibold">Produk</th>
                    <th class="px-4 py-3 font-semibold">Qty</th>
                    <th class="px-4 py-3 font-semibold">Total</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Tanggal Dibuat</th>
                    <th class="px-4 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f0e6dc]">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-3">{{ $order->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $order->buyer->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $order->product->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $order->quantity }}</td>
                        <td class="px-4 py-3">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ ucfirst($order->status) }}</td>
                        <td class="px-4 py-3">{{ $order->created_at?->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.orders', ['edit' => $order->id]) }}" class="rounded border border-[#d8c7b8] px-2 py-1 text-xs hover:bg-[#f5eee7]">Edit</a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-[#9a8070]">Belum ada data pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endsection
