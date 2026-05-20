@extends('admin.layout')

@section('title', 'Pembeli - Admin KopiNusantara')
@section('header', 'Kelola Pembeli')
@section('subtitle', 'Lihat dan kelola data pembeli terdaftar — akun baru dibuat melalui halaman registrasi toko')

@section('content')
    @include('admin.partials.alert')

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.buyers') }}" class="flex flex-1 flex-wrap gap-2">
            @if (request('edit'))
                <input type="hidden" name="edit" value="{{ request('edit') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, nomor HP, atau alamat..."
                   class="min-w-[220px] flex-1 rounded-xl border border-[#e0d8cc] bg-white px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
            <button type="submit" class="rounded-xl bg-[#3a2010] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#5a4030]">Cari</button>
            @if (request('search'))
                <a href="{{ route('admin.buyers', request()->only('edit')) }}" class="rounded-xl border border-[#d8c7b8] px-4 py-2.5 text-sm font-semibold hover:bg-[#f5eee7]">Reset</a>
            @endif
        </form>
        <p class="text-xs text-[#9a8070]">Total: <span class="font-bold text-[#3a2010]">{{ $buyers->total() }}</span> pembeli</p>
    </div>

    @if ($editBuyer)
        <div class="mb-6 rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm sm:p-6">
            <h3 class="mb-1 text-base font-bold text-[#3a2010]">Edit Pembeli</h3>
            <p class="mb-5 text-xs text-[#9a8070]">Perbarui profil pembeli untuk keperluan pengiriman dan transaksi.</p>
            <form method="POST" action="{{ route('admin.buyers.update', $editBuyer) }}" class="grid gap-4 md:grid-cols-2">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $editBuyer->name) }}"
                           class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Email</label>
                    <input type="email" name="email" value="{{ old('email', $editBuyer->email) }}"
                           class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $editBuyer->phone) }}" placeholder="0812-xxxx-xxxx"
                           class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Password baru (opsional)</label>
                    <input type="password" name="password"
                           class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Alamat Pengiriman</label>
                    <textarea name="address" rows="2" class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">{{ old('address', $editBuyer->address) }}</textarea>
                </div>
                <div class="flex gap-2 md:col-span-2">
                    <button type="submit" class="rounded-xl bg-[#c57d38] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#a66528]">Simpan Perubahan</button>
                    <a href="{{ route('admin.buyers', request()->only('search')) }}" class="rounded-xl border border-[#d8c7b8] px-5 py-2.5 text-sm font-semibold hover:bg-[#f5eee7]">Batal</a>
                </div>
            </form>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-[#e7ddd2] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#fdf9f4] text-left text-xs uppercase tracking-wider text-[#9a8070]">
                    <tr>
                        <th class="px-4 py-3 font-semibold sm:px-6">Pembeli</th>
                        <th class="px-4 py-3 font-semibold">Telepon</th>
                        <th class="px-4 py-3 font-semibold">Alamat</th>
                        <th class="px-4 py-3 font-semibold">Pesanan</th>
                        <th class="px-4 py-3 font-semibold">Daftar</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e6dc]">
                    @forelse ($buyers as $buyer)
                        <tr class="hover:bg-[#fdf9f4]/50 {{ $editBuyer?->id === $buyer->id ? 'bg-[#fdf3e7]/40' : '' }}">
                            <td class="px-4 py-3 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#c57d38] text-sm font-bold text-white">
                                        {{ strtoupper(substr($buyer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#3a2010]">{{ $buyer->name }}</p>
                                        <p class="text-xs text-[#9a8070]">{{ $buyer->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $buyer->phone ?? '-' }}</td>
                            <td class="px-4 py-3 max-w-[220px] text-xs text-[#5a4030]" title="{{ $buyer->address }}">{{ $buyer->address ? Str::limit($buyer->address, 50) : '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-[#fdf3e7] px-2.5 py-0.5 text-xs font-bold text-[#936232]">{{ $buyer->orders_count }} pesanan</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-[#9a8070]">{{ $buyer->created_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.buyers', array_filter(['edit' => $buyer->id, 'search' => request('search')])) }}"
                                       class="rounded-lg border border-[#d8c7b8] px-2.5 py-1 text-xs font-semibold hover:bg-[#f5eee7]">Edit</a>
                                    <form action="{{ route('admin.buyers.destroy', $buyer) }}" method="POST" onsubmit="return confirm('Hapus pembeli ini? Semua pesanan terkait juga akan terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-2.5 py-1 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#9a8070]">
                                @if (request('search'))
                                    Tidak ada pembeli yang cocok dengan pencarian &ldquo;{{ request('search') }}&rdquo;.
                                @else
                                    Belum ada pembeli terdaftar. Pembeli mendaftar sendiri melalui halaman <a href="{{ route('register') }}" class="font-semibold text-[#c57d38] hover:underline" target="_blank">Registrasi</a> toko.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $buyers->links('admin.partials.pagination', ['label' => 'pembeli']) }}
@endsection
