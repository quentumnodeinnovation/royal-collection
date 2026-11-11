<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public const STATUSES = ['new', 'replied', 'resolved'];

    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('q', ''));

        $inquiries = ContactMessage::query()
            ->when(in_array($status, self::STATUSES, true), fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statuses = self::STATUSES;

        return view('admin.inquiries.index', compact('inquiries', 'status', 'search', 'statuses'));
    }

    public function updateStatus(Request $request, ContactMessage $inquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
        ]);

        $inquiry->update(['status' => $data['status']]);

        return back()->with('success', "Inquiry from {$inquiry->name} marked as ".ucfirst($data['status']).'.');
    }

    public function destroy(ContactMessage $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()
            ->route('admin.inquiries.index')
            ->with('success', 'Contact inquiry deleted.');
    }
}
