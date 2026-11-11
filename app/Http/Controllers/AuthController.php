<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\StorefrontService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly StorefrontService $storefront)
    {
    }

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('account');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid email address or password.']);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This account is currently deactivated. Contact Royal Collection support.']);
        }

        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now()])->save();

        $rawCart = session('rc_cart', []);
        if (!empty($rawCart)) {
            $this->storefront->syncSessionCartToDatabase($rawCart);
        }

        return redirect()->intended(route('account'))
            ->with('success', 'Welcome back to Royal Collection, '.$user->name.'.');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('account');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:25'],
            'city' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'city' => $data['city'] ?? null,
            'role' => 'customer',
            'is_active' => true,
            'last_login_at' => now(),
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $rawCart = session('rc_cart', []);
        if (!empty($rawCart)) {
            $this->storefront->syncSessionCartToDatabase($rawCart);
        }

        return redirect()->route('account')
            ->with('success', 'Your Royal Collection account has been created.');
    }

    public function account(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $orders = Order::with('items')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('email', $user->email);
            })
            ->latest()
            ->get();

        return view('account', compact('user', 'orders'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:25'],
            'city' => ['nullable', 'string', 'max:100'],
        ]);

        $user->update($data);

        return back()->with('success', 'Your profile details have been updated.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Signed out cleanly.');
    }
}
