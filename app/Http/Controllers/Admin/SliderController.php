<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends AdminBaseController
{
    public function index(): View
    {
        $sliders = Slider::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create(): View
    {
        $slider = new Slider([
            'sort_order' => (Slider::max('sort_order') ?? 0) + 1,
            'is_active' => true,
            'primary_cta_label' => 'Explore Collection',
            'primary_cta_url' => '/shop',
        ]);

        return view('admin.sliders.form', compact('slider'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSlider($request);

        $imagePath = $this->resolveImage(
            $request,
            'image_file',
            'image_path',
            'banners',
            null,
            '/images/banners/hero-mens.jpg'
        );

        $slider = Slider::create([
            'eyebrow' => $validated['eyebrow'] ?? null,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'image' => $imagePath,
            'primary_cta_label' => $validated['primary_cta_label'] ?? null,
            'primary_cta_url' => $validated['primary_cta_url'] ?? null,
            'secondary_cta_label' => $validated['secondary_cta_label'] ?? null,
            'secondary_cta_url' => $validated['secondary_cta_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', "Slider \"{$slider->title}\" added.");
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.form', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $validated = $this->validateSlider($request);

        $imagePath = $this->resolveImage(
            $request,
            'image_file',
            'image_path',
            'banners',
            $slider->image
        );

        $slider->update([
            'eyebrow' => $validated['eyebrow'] ?? null,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'image' => $imagePath,
            'primary_cta_label' => $validated['primary_cta_label'] ?? null,
            'primary_cta_url' => $validated['primary_cta_url'] ?? null,
            'secondary_cta_label' => $validated['secondary_cta_label'] ?? null,
            'secondary_cta_url' => $validated['secondary_cta_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', "Slider \"{$slider->title}\" updated.");
    }

    public function toggleActive(Slider $slider): RedirectResponse
    {
        $slider->update(['is_active' => !$slider->is_active]);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', "Slider \"{$slider->title}\" ".($slider->is_active ? 'enabled' : 'hidden').'.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $title = $slider->title;
        $slider->delete();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', "Slider \"{$title}\" removed.");
    }

    private function validateSlider(Request $request): array
    {
        return $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'primary_cta_label' => ['nullable', 'string', 'max:80'],
            'primary_cta_url' => ['nullable', 'string', 'max:255'],
            'secondary_cta_label' => ['nullable', 'string', 'max:80'],
            'secondary_cta_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image_file' => ['nullable', 'image', 'max:5120'],
            'image_path' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
