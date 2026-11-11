<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $role = $request->query('role');
        $status = $request->query('status');
        $search = trim((string) $request->query('q', ''));

        $users = User::query()
            ->withCount(['orders', 'carts'])
            ->when(in_array($role, ['admin', 'customer'], true), fn ($q) => $q->where('role', $role))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'blocked', fn ($q) => $q->where('is_active', false))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'role', 'status', 'search'));
    }

    public function create(): View
    {
        $user = new User([
            'role' => 'customer',
            'is_active' => true,
        ]);

        return view('admin.users.form', compact('user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'customer'])],
            'phone' => ['nullable', 'string', 'max:25'],
            'city' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'city' => $data['city'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'password' => Hash::make($data['password']),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" created.");
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'customer'])],
            'phone' => ['nullable', 'string', 'max:25'],
            'city' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $isActive = $request->boolean('is_active');

        if ($user->id === Auth::id() && ($data['role'] !== 'admin' || !$isActive)) {
            return back()->with('error', 'You cannot remove your own admin role or deactivate your active session account.');
        }

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'city' => $data['city'] ?? null,
            'is_active' => $isActive,
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" updated.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete the currently signed-in administrator.');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
            return back()->with('error', 'Cannot delete the last remaining active administrator.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$name}\" removed.");
    }
}
