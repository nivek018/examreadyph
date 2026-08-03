<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdPopup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdPopupController extends Controller
{
    public function index()
    {
        $ads = AdPopup::orderBy('sort_order')->orderByDesc('id')->get();
        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ads.form', ['ad' => new AdPopup()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'image_url_input' => 'nullable|string|max:500',
            'destination_url' => 'required|url|max:500',
            'alt_text' => 'nullable|string|max:255',
            'placement' => 'required|in:all,exam,browse,forum',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $imageUrl = '/images/ads/default-ad.png';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public');
            $imageUrl = '/storage/' . $path;
        } elseif ($request->filled('image_url_input')) {
            $imageUrl = $request->image_url_input;
        }

        AdPopup::create([
            'name' => $validated['name'],
            'image_url' => $imageUrl,
            'destination_url' => $validated['destination_url'],
            'alt_text' => $validated['alt_text'] ?? null,
            'placement' => $validated['placement'],
            'is_active' => $request->has('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'Ad campaign created successfully.');
    }

    public function edit(AdPopup $ad)
    {
        return view('admin.ads.form', compact('ad'));
    }

    public function update(Request $request, AdPopup $ad)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'image_url_input' => 'nullable|string|max:500',
            'destination_url' => 'required|url|max:500',
            'alt_text' => 'nullable|string|max:255',
            'placement' => 'required|in:all,exam,browse,forum',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $imageUrl = $ad->image_url;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public');
            $imageUrl = '/storage/' . $path;
        } elseif ($request->filled('image_url_input')) {
            $imageUrl = $request->image_url_input;
        }

        $ad->update([
            'name' => $validated['name'],
            'image_url' => $imageUrl,
            'destination_url' => $validated['destination_url'],
            'alt_text' => $validated['alt_text'] ?? null,
            'placement' => $validated['placement'],
            'is_active' => $request->has('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'Ad campaign updated successfully.');
    }

    public function destroy(AdPopup $ad)
    {
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Ad campaign deleted.');
    }
}
