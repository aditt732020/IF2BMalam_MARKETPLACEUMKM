@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard Admin')

@section('content')
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
            <p class="text-sm text-[#9a8070]">Total Produk</p>
            <p class="mt-2 text-3xl font-extrabold">{{ $stats['totalProducts'] }}</p>
        </div>
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
            <p class="text-sm text-[#9a8070]">Total Pesanan</p>
            <p class="mt-2 text-3xl font-extrabold">{{ $stats['totalOrders'] }}</p>
        </div>
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
            <p class="text-sm text-[#9a8070]">Total Penjual</p>
            <p class="mt-2 text-3xl font-extrabold">{{ $stats['totalSellers'] }}</p>
        </div>
        <div class="rounded-2xl border border-[#e7ddd2] bg-white p-5 shadow-sm">
            <p class="text-sm text-[#9a8070]">Total Pembeli</p>
            <p class="mt-2 text-3xl font-extrabold">{{ $stats['totalBuyers'] }}</p>
        </div>
    </div>

    <div class="mt-8 rounded-2xl border border-[#e7ddd2] bg-white p-6 shadow-sm">
        <h3 class="text-lg font-bold">Aksi Cepat</h3>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.products') }}" class="rounded-lg bg-[#3a2010] px-4 py-2 text-sm font-semibold text-white hover:bg-[#5a4030]">Kelola Produk</a>
            <a href="{{ route('admin.orders') }}" class="rounded-lg bg-[#c97e3a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#a06020]">Kelola Pesanan</a>
            <a href="{{ route('admin.sellers') }}" class="rounded-lg border border-[#d8c7b8] px-4 py-2 text-sm font-semibold hover:bg-[#f5eee7]">Kelola Penjual</a>
        </div>
    </div>
@endsection
