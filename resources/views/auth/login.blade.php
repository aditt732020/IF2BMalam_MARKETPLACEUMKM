@extends('app')

@section('title', 'Login')

@section('content')
<section class="min-h-[calc(100vh-64px)] bg-slate-50 py-12">
    <div class="mx-auto w-full max-w-md px-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">Masuk ke Akun</h1>
            <p class="mt-2 text-sm text-slate-600">Silakan login untuk melanjutkan ke Marketplace UMKM.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.attempt') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none ring-amber-300 focus:ring"
                        placeholder="contoh@email.com"
                    >
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none ring-amber-300 focus:ring"
                        placeholder="Masukkan password"
                    >
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-amber-500 focus:ring-amber-300">
                    Ingat saya
                </label>

                <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2.5 font-semibold text-white hover:bg-amber-500">
                    Login
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
