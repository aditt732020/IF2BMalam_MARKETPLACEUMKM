<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $editSeller = null;
        if ($request->filled('edit')) {
            $editSeller = User::where('role', 'seller')->find($request->integer('edit'));
        }

        $sellers = User::where('role', 'seller')->latest()->paginate(10);

        return view('admin.sellers', compact('sellers', 'editSeller'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'seller',
        ]);

        return redirect()->route('admin.sellers')->with('success', 'Penjual berhasil ditambahkan.');
    }

    public function update(Request $request, User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($seller->id),
            ],
            'password' => 'nullable|string|min:6',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $seller->update($payload);

        return redirect()->route('admin.sellers')->with('success', 'Data penjual berhasil diperbarui.');
    }

    public function destroy(User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $seller->delete();

        return redirect()->route('admin.sellers')->with('success', 'Penjual berhasil dihapus.');
    }
}
