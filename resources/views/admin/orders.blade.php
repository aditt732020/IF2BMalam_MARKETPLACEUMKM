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

                @if ($editOrder->payment_reference)
                    <div class="md:col-span-2 rounded-xl border border-[#e7ddd2] bg-[#fdf9f4] p-4">
                        <p class="mb-2 text-xs font-bold uppercase text-[#7a6050]">Informasi Pembayaran</p>
                        <div class="grid gap-2 text-sm md:grid-cols-2">
                            <p><span class="text-[#9a8070]">Referensi:</span> <span class="font-semibold">{{ $editOrder->payment_reference }}</span></p>
                            <p><span class="text-[#9a8070]">Metode:</span> <span class="font-semibold">{{ $editOrder->payment_method ?? 'QRIS' }}</span></p>
                            <p><span class="text-[#9a8070]">Status Bukti:</span>
                                <span class="font-semibold">
                                    @if ($editOrder->isAwaitingPaymentVerification())
                                        Menunggu verifikasi
                                    @elseif ($editOrder->payment_rejection_reason)
                                        Ditolak
                                    @elseif ($editOrder->payment_verified_at)
                                        Terverifikasi
                                    @elseif ($editOrder->hasPaymentProof())
                                        Bukti terunggah
                                    @else
                                        Belum ada bukti
                                    @endif
                                </span>
                            </p>
                            @if ($editOrder->payment_proof_uploaded_at)
                                <p><span class="text-[#9a8070]">Diunggah:</span> <span class="font-semibold">{{ $editOrder->payment_proof_uploaded_at->format('d M Y H:i') }}</span></p>
                            @endif
                        </div>
                        @if ($editOrder->payment_rejection_reason)
                            <p class="mt-2 text-xs text-red-600">Alasan penolakan: {{ $editOrder->payment_rejection_reason }}</p>
                        @endif
                        @if ($editOrder->payment_proof_path)
                            <a href="{{ Storage::disk('public')->url($editOrder->payment_proof_path) }}" target="_blank" class="mt-3 inline-block">
                                <img src="{{ Storage::disk('public')->url($editOrder->payment_proof_path) }}" alt="Bukti Pembayaran" class="h-32 rounded-lg border border-[#e0d8cc] object-cover">
                            </a>
                        @endif
                        @if ($editOrder->isAwaitingPaymentVerification())
                            <div class="mt-4 flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('admin.orders.verify-payment', $editOrder) }}">
                                    @csrf
                                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700"
                                        onclick="return confirm('Verifikasi pembayaran pesanan ini?')">Verifikasi Pembayaran</button>
                                </form>
                                <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')"
                                    class="rounded-xl border border-red-200 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Tolak Bukti</button>
                            </div>
                            <form id="reject-form" method="POST" action="{{ route('admin.orders.reject-payment', $editOrder) }}" class="mt-3 hidden space-y-2">
                                @csrf
                                <textarea name="payment_rejection_reason" rows="2" required placeholder="Alasan penolakan bukti pembayaran..."
                                    class="w-full rounded-xl border border-[#e0d8cc] px-3 py-2 text-sm focus:border-[#c57d38] focus:outline-none"></textarea>
                                <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-700">Kirim Penolakan</button>
                            </form>
                        @endif
                    </div>
                @endif
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
                        <th class="px-4 py-3 font-semibold">Bukti Bayar</th>
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
                            <td class="px-4 py-3">
                                @if ($order->isAwaitingPaymentVerification())
                                    <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700">Menunggu verifikasi</span>
                                @elseif ($order->payment_rejection_reason)
                                    <span class="inline-flex rounded-full bg-red-50 px-2.5 py-1 text-[10px] font-bold text-red-700">Ditolak</span>
                                @elseif ($order->hasPaymentProof())
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700">Sudah upload</span>
                                @else
                                    <span class="text-xs text-[#9a8070]">-</span>
                                @endif
                            </td>
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
                            <td colspan="9" class="px-6 py-12 text-center text-[#9a8070]">
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
