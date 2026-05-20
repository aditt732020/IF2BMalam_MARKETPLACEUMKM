@extends('admin.layout')

@section('title', 'UMKM Penjual - Admin KopiNusantara')
@section('header', 'Manajemen UMKM Penjual')
@section('subtitle', 'Kelola toko UMKM kopi nusantara — sesuai bagian UMKM unggulan di toko pembeli')

@section('content')
    @include('admin.partials.alert')

    <div class="mb-6 rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm sm:p-6">
        <h3 class="mb-1 text-base font-bold text-[#3a2010]">{{ $editSeller ? 'Edit UMKM Penjual' : 'Tambah UMKM Penjual' }}</h3>
        <p class="mb-5 text-xs text-[#9a8070]">Daftarkan penjual UMKM dengan nama toko, wilayah asal, dan kontak.</p>
        <form method="POST" action="{{ $editSeller ? route('admin.sellers.update', $editSeller) : route('admin.sellers.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @if ($editSeller) @method('PUT') @endif

            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Nama Toko / Penjual</label>
                <input type="text" name="name" value="{{ old('name', $editSeller->name ?? '') }}" placeholder="Contoh: Rumah Kopi Aceh"
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Wilayah / Daerah Asal</label>
                <input type="text" name="region" value="{{ old('region', $editSeller->region ?? '') }}" placeholder="Contoh: Aceh Tengah"
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('region') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Email</label>
                <input type="email" name="email" value="{{ old('email', $editSeller->email ?? '') }}"
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $editSeller->phone ?? '') }}"
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Alamat Toko</label>
                <textarea name="address" rows="2" class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">{{ old('address', $editSeller->address ?? '') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">{{ $editSeller ? 'Password baru (opsional)' : 'Password' }}</label>
                <input type="password" name="password"
                       class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2 md:col-span-2">
                <button type="submit" class="rounded-xl bg-[#c57d38] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#a66528]">
                    {{ $editSeller ? 'Simpan Perubahan' : 'Tambah UMKM' }}
                </button>
                @if ($editSeller)
                    <a href="{{ route('admin.sellers') }}" class="rounded-xl border border-[#d8c7b8] px-5 py-2.5 text-sm font-semibold hover:bg-[#f5eee7]">Batal</a>
                @endif
            </div>
        </form>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-6">
        @php $colors = ['#cc8444', '#1fa471', '#2c84e4', '#846cf4', '#c57d38', '#be8146']; @endphp
        @forelse ($sellers as $index => $seller)
            <div class="rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-sm font-bold text-white" style="background-color: {{ $colors[$index % count($colors)] }}">
                        {{ strtoupper(substr($seller->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="truncate font-bold text-sm text-[#3a2010]">{{ $seller->name }}</h4>
                        <p class="text-xs text-[#9a8070]">{{ $seller->region ?? 'Wilayah belum diisi' }}</p>
                    </div>
                </div>
                <div class="mt-4 space-y-1 text-xs text-[#5a4030]">
                    <p class="truncate">{{ $seller->email }}</p>
                    @if ($seller->phone)<p>{{ $seller->phone }}</p>@endif
                </div>
                <div class="mt-4 flex gap-2 border-t border-[#f0e6dc] pt-3">
                    <a href="{{ route('admin.sellers', ['edit' => $seller->id]) }}" class="flex-1 rounded-lg border border-[#d8c7b8] py-1.5 text-center text-xs font-semibold hover:bg-[#f5eee7]">Edit</a>
                    <form action="{{ route('admin.sellers.destroy', $seller) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus penjual ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full rounded-lg border border-red-200 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-[#e7ddd2] bg-white p-12 text-center text-[#9a8070]">
                Belum ada UMKM penjual. Tambahkan penjual pertama Anda.
            </div>
        @endforelse
    </div>

    @if ($sellers->hasPages())
        <div>{{ $sellers->links() }}</div>
    @endif
@endsection
