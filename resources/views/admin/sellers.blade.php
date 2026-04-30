@extends('admin.layout')

@section('title', 'Admin Penjual')
@section('header', 'Manajemen Penjual')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-lg font-bold">{{ $editSeller ? 'Edit Penjual' : 'Tambah Penjual' }}</h3>
        <form method="POST" action="{{ $editSeller ? route('admin.sellers.update', $editSeller) : route('admin.sellers.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @if ($editSeller)
                @method('PUT')
            @endif

            <div>
                <label class="mb-1 block text-sm text-[#7a6050]">Nama</label>
                <input type="text" name="name" value="{{ old('name', $editSeller->name ?? '') }}" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm text-[#7a6050]">Email</label>
                <input type="email" name="email" value="{{ old('email', $editSeller->email ?? '') }}" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm text-[#7a6050]">{{ $editSeller ? 'Password baru (opsional)' : 'Password' }}</label>
                <input type="password" name="password" class="w-full rounded-lg border border-[#e0d8cc] px-3 py-2 focus:border-[#c97e3a] focus:outline-none">
                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="rounded-lg bg-[#c97e3a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#a06020]">
                    {{ $editSeller ? 'Update Penjual' : 'Simpan Penjual' }}
                </button>
                @if ($editSeller)
                    <a href="{{ route('admin.sellers') }}" class="rounded-lg border border-[#d8c7b8] px-4 py-2 text-sm font-semibold hover:bg-[#f5eee7]">Batal</a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#e7ddd2] bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-[#f3ebe3] text-left text-[#5a4030]">
                <tr>
                    <th class="px-4 py-3 font-semibold">ID</th>
                    <th class="px-4 py-3 font-semibold">Nama</th>
                    <th class="px-4 py-3 font-semibold">Email</th>
                    <th class="px-4 py-3 font-semibold">Tanggal Daftar</th>
                    <th class="px-4 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f0e6dc]">
                @forelse ($sellers as $seller)
                    <tr>
                        <td class="px-4 py-3">{{ $seller->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $seller->name }}</td>
                        <td class="px-4 py-3">{{ $seller->email }}</td>
                        <td class="px-4 py-3">{{ $seller->created_at?->format('d M Y H:i') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.sellers', ['edit' => $seller->id]) }}" class="rounded border border-[#d8c7b8] px-2 py-1 text-xs hover:bg-[#f5eee7]">Edit</a>
                                <form action="{{ route('admin.sellers.destroy', $seller) }}" method="POST" onsubmit="return confirm('Hapus penjual ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-[#9a8070]">Belum ada data penjual.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sellers->links() }}
    </div>
@endsection
