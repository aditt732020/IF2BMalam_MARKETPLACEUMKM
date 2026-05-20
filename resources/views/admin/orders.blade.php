@extends('admin.layout')

@section('title', 'Pesanan - Admin KopiNusantara')
@section('header', 'Manajemen Pesanan')
@section('subtitle', 'Kelola pesanan dari checkout pembeli — ubah status atau perbarui detail pesanan')

@section('content')
    @include('admin.partials.alert')

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('admin.orders') }}"
           class="rounded-full px-4 py-1.5 text-xs font-semibold transition {{ !request('status') ? 'bg-[#c57d38] text-white' : 'border border-[#e7ddd2] bg-white text-[#5a4030] hover:bg-[#f5eee7]' }}">
            Semua
        </a>
        @foreach ($statuses as $key => $label)
            <a href="{{ route('admin.orders', ['status' => $key]) }}"
               class="rounded-full px-4 py-1.5 text-xs font-semibold transition {{ request('status') === $key ? 'bg-[#c57d38] text-white' : 'border border-[#e7ddd2] bg-white text-[#5a4030] hover:bg-[#f5eee7]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($editOrder)
        <div class="mb-6 rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm sm:p-6">
            <h3 class="mb-1 text-base font-bold text-[#3a2010]">Edit Pesanan #{{ $editOrder->id }}</h3>
            <p class="mb-5 text-xs text-[#9a8070]">Perbarui detail atau status pesanan yang masuk dari toko pembeli.</p>
            <form method="POST" action="{{ route('admin.orders.update', $editOrder) }}" class="grid gap-4 md:grid-cols-2">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Pembeli</label>
                    <select name="buyer_id" class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none">
                        @foreach ($buyers as $buyer)
                            <option value="{{ $buyer->id }}" @selected(old('buyer_id', $editOrder->buyer_id) == $buyer->id)>{{ $buyer->name }} ({{ $buyer->email }})</option>
                        @endforeach
                    </select>
                    @error('buyer_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Produk</label>
                    <select name="product_id" class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id', $editOrder->product_id) == $product->id)>{{ $product->name }} — Rp{{ number_format($product->price, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                    @error('product_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Jumlah</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', $editOrder->quantity) }}"
                           class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none">
                    @error('quantity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Status Pesanan</label>
                    <select name="status" class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none">
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $editOrder->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 md:col-span-2">
                    <button type="submit" class="rounded-xl bg-[#c57d38] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#a66528]">Simpan Perubahan</button>
                    <a href="{{ route('admin.orders', request()->only('status')) }}" class="rounded-xl border border-[#d8c7b8] px-5 py-2.5 text-sm font-semibold hover:bg-[#f5eee7]">Batal</a>
                </div>
            </form>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-[#e7ddd2] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#fdf9f4] text-left text-xs uppercase tracking-wider text-[#9a8070]">
                    <tr>
                        <th class="px-4 py-3 font-semibold sm:px-6">ID</th>
                        <th class="px-4 py-3 font-semibold">Pembeli</th>
                        <th class="px-4 py-3 font-semibold">Produk</th>
                        <th class="px-4 py-3 font-semibold">Qty</th>
                        <th class="px-4 py-3 font-semibold">Total</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e6dc]">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-[#fdf9f4]/50 {{ $editOrder?->id === $order->id ? 'bg-[#fdf3e7]/40' : '' }}">
                            <td class="px-4 py-3 font-bold sm:px-6">#{{ $order->id }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-[#3a2010]">{{ $order->buyer->name ?? '-' }}</p>
                                <p class="text-xs text-[#9a8070]">{{ $order->buyer->email ?? '' }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $order->product->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $order->quantity }}</td>
                            <td class="px-4 py-3 font-bold text-[#c57d38]">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">@include('admin.partials.status-badge', ['order' => $order])</td>
                            <td class="px-4 py-3 text-xs text-[#9a8070]">{{ $order->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.orders', array_filter(['edit' => $order->id, 'status' => request('status')])) }}" class="rounded-lg border border-[#d8c7b8] px-2.5 py-1 text-xs font-semibold hover:bg-[#f5eee7]">Edit</a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-2.5 py-1 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-[#9a8070]">
                                @if (request('status'))
                                    Belum ada pesanan dengan status ini.
                                @else
                                    Belum ada pesanan. Pesanan baru muncul otomatis saat pembeli checkout di toko.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $orders->links('admin.partials.pagination', ['label' => 'pesanan']) }}
@endsection
