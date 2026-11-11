<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the Royal Collection Contact Us page.
     */
    public function index(): View
    {
        return view('contact');
    }

    /**
     * Persist a customer contact message in the isolated SQLite database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:160'],
            'order_ref' => ['nullable', 'string', 'max:40'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        ContactMessage::create($validated);

        return redirect()
            ->route('contact')
            ->with('contact_success', 'Thank you for reaching out to Royal Collection. Our concierge team will respond within 24 hours.');
    }
}
