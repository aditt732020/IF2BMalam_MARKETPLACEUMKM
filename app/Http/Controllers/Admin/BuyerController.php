<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
        $editBuyer = null;
        if ($request->filled('edit')) {
            $editBuyer = User::where('role', 'buyer')->find($request->integer('edit'));
        }

        $query = User::where('role', 'buyer')->withCount('orders');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        $buyers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.buyers', compact('buyers', 'editBuyer'));
    }

    public function update(Request $request, User $buyer)
    {
        if ($buyer->role !== 'buyer') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($buyer->id),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $buyer->update($payload);

        return redirect()->route('admin.buyers')->with('success', 'Data pembeli berhasil diperbarui.');
    }

    public function destroy(User $buyer)
    {
        if ($buyer->role !== 'buyer') {
            abort(404);
        }

        $buyer->delete();

        return redirect()->route('admin.buyers')->with('success', 'Pembeli berhasil dihapus.');
    }
}
