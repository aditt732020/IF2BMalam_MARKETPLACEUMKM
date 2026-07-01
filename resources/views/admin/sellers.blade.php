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
                <select name="region" class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                    <option value="">-- Pilih Wilayah --</option>
                    <option value="Aceh" {{ old('region', $editSeller->region ?? '') === 'Aceh' ? 'selected' : '' }}>Aceh</option>
                    <option value="Sumatera Utara" {{ old('region', $editSeller->region ?? '') === 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                    <option value="Sumatera Barat" {{ old('region', $editSeller->region ?? '') === 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                    <option value="Riau" {{ old('region', $editSeller->region ?? '') === 'Riau' ? 'selected' : '' }}>Riau</option>
                    <option value="Kepulauan Riau" {{ old('region', $editSeller->region ?? '') === 'Kepulauan Riau' ? 'selected' : '' }}>Kepulauan Riau</option>
                    <option value="Jambi" {{ old('region', $editSeller->region ?? '') === 'Jambi' ? 'selected' : '' }}>Jambi</option>
                    <option value="Sumatera Selatan" {{ old('region', $editSeller->region ?? '') === 'Sumatera Selatan' ? 'selected' : '' }}>Sumatera Selatan</option>
                    <option value="Bangka Belitung" {{ old('region', $editSeller->region ?? '') === 'Bangka Belitung' ? 'selected' : '' }}>Bangka Belitung</option>
                    <option value="Bengkulu" {{ old('region', $editSeller->region ?? '') === 'Bengkulu' ? 'selected' : '' }}>Bengkulu</option>
                    <option value="Lampung" {{ old('region', $editSeller->region ?? '') === 'Lampung' ? 'selected' : '' }}>Lampung</option>
                    <option value="DKI Jakarta" {{ old('region', $editSeller->region ?? '') === 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                    <option value="Jawa Barat" {{ old('region', $editSeller->region ?? '') === 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                    <option value="Banten" {{ old('region', $editSeller->region ?? '') === 'Banten' ? 'selected' : '' }}>Banten</option>
                    <option value="Jawa Tengah" {{ old('region', $editSeller->region ?? '') === 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                    <option value="DI Yogyakarta" {{ old('region', $editSeller->region ?? '') === 'DI Yogyakarta' ? 'selected' : '' }}>DI Yogyakarta</option>
                    <option value="Jawa Timur" {{ old('region', $editSeller->region ?? '') === 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                    <option value="Bali" {{ old('region', $editSeller->region ?? '') === 'Bali' ? 'selected' : '' }}>Bali</option>
                    <option value="Nusa Tenggara Barat" {{ old('region', $editSeller->region ?? '') === 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                    <option value="Nusa Tenggara Timur" {{ old('region', $editSeller->region ?? '') === 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>
                    <option value="Kalimantan Barat" {{ old('region', $editSeller->region ?? '') === 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                    <option value="Kalimantan Tengah" {{ old('region', $editSeller->region ?? '') === 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                    <option value="Kalimantan Selatan" {{ old('region', $editSeller->region ?? '') === 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                    <option value="Kalimantan Timur" {{ old('region', $editSeller->region ?? '') === 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                    <option value="Kalimantan Utara" {{ old('region', $editSeller->region ?? '') === 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                    <option value="Sulawesi Utara" {{ old('region', $editSeller->region ?? '') === 'Sulawesi Utara' ? 'selected' : '' }}>Sulawesi Utara</option>
                    <option value="Gorontalo" {{ old('region', $editSeller->region ?? '') === 'Gorontalo' ? 'selected' : '' }}>Gorontalo</option>
                    <option value="Sulawesi Tengah" {{ old('region', $editSeller->region ?? '') === 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                    <option value="Sulawesi Barat" {{ old('region', $editSeller->region ?? '') === 'Sulawesi Barat' ? 'selected' : '' }}>Sulawesi Barat</option>
                    <option value="Sulawesi Selatan" {{ old('region', $editSeller->region ?? '') === 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                    <option value="Sulawesi Tenggara" {{ old('region', $editSeller->region ?? '') === 'Sulawesi Tenggara' ? 'selected' : '' }}>Sulawesi Tenggara</option>
                    <option value="Maluku" {{ old('region', $editSeller->region ?? '') === 'Maluku' ? 'selected' : '' }}>Maluku</option>
                    <option value="Maluku Utara" {{ old('region', $editSeller->region ?? '') === 'Maluku Utara' ? 'selected' : '' }}>Maluku Utara</option>
                    <option value="Papua" {{ old('region', $editSeller->region ?? '') === 'Papua' ? 'selected' : '' }}>Papua</option>
                    <option value="Papua Barat" {{ old('region', $editSeller->region ?? '') === 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                    <option value="Papua Selatan" {{ old('region', $editSeller->region ?? '') === 'Papua Selatan' ? 'selected' : '' }}>Papua Selatan</option>
                    <option value="Papua Tengah" {{ old('region', $editSeller->region ?? '') === 'Papua Tengah' ? 'selected' : '' }}>Papua Tengah</option>
                    <option value="Papua Pegunungan" {{ old('region', $editSeller->region ?? '') === 'Papua Pegunungan' ? 'selected' : '' }}>Papua Pegunungan</option>
                </select>
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
                <div class="flex">
                    <span class="inline-flex items-center rounded-l-xl border border-r-0 border-[#e0d8cc] bg-[#f5eee7] px-4 py-2.5 text-sm text-[#5a4030]">+62</span>
                    <input type="text" name="phone" value="{{ old('phone', $editSeller->phone ?? '') }}" placeholder="8xxxxxxxxxx"
                           class="w-full rounded-r-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">
                </div>
                @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase text-[#7a6050]">Alamat Toko</label>
                <textarea name="address" rows="2" class="w-full rounded-xl border border-[#e0d8cc] px-4 py-2.5 text-sm focus:border-[#c57d38] focus:outline-none focus:ring-2 focus:ring-[#c57d38]/20">{{ old('address', $editSeller->address ?? '') }}</textarea>
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
