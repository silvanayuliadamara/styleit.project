<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerAddonController extends Controller
{
    public function index()
    {
        $addons = Addon::with(['categories', 'options'])->get();

        return view('owner.addons.index', compact('addons'));
    }

    public function create()
    {
        $categories = ServiceCategory::orderBy('sort_order')->get();

        return view('owner.addons.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'harga_default' => 'nullable|numeric|min:0',
            'is_pihak_lain' => 'nullable|boolean',
            'biaya_pihak_lain' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',

            // Categories
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:service_categories,id',

            // Options
            'options' => 'nullable|array',
            'options.*.nama_option' => 'required_with:options|string|max:255',
            'options.*.harga' => 'required_with:options|numeric|min:0',
            'options.*.is_pihak_lain' => 'nullable|boolean',
            'options.*.biaya_pihak_lain' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $validated['is_active'] = ($validated['status'] === 'aktif');
            $validated['is_pihak_lain'] = $request->has('is_pihak_lain');
            $validated['harga_default'] = $validated['harga_default'] ?? $validated['price'];

            $addon = Addon::create($validated);

            // Sync categories
            if (! empty($validated['category_ids'])) {
                $syncData = [];
                foreach ($validated['category_ids'] as $catId) {
                    $syncData[$catId] = ['status' => 'aktif'];
                }
                $addon->categories()->sync($syncData);
            }

            // Options
            if (! empty($validated['options'])) {
                foreach ($validated['options'] as $option) {
                    $addon->options()->create([
                        'nama_option' => $option['nama_option'],
                        'tipe_option' => $option['tipe_option'] ?? null,
                        'harga' => $option['harga'],
                        'is_pihak_lain' => isset($option['is_pihak_lain']) && $option['is_pihak_lain'],
                        'biaya_pihak_lain' => $option['biaya_pihak_lain'] ?? 0,
                        'status' => 'aktif',
                    ]);
                }
            }
        });

        return redirect()->route('owner.addons.index')
            ->with('success', 'Addon berhasil ditambahkan.');
    }

    public function edit(Addon $addon)
    {
        $categories = ServiceCategory::orderBy('sort_order')->get();
        $addon->load(['categories', 'options']);

        return view('owner.addons.edit', compact('addon', 'categories'));
    }

    public function update(Request $request, Addon $addon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'harga_default' => 'nullable|numeric|min:0',
            'is_pihak_lain' => 'nullable|boolean',
            'biaya_pihak_lain' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',

            // Categories
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:service_categories,id',

            // Options
            'options' => 'nullable|array',
            'options.*.nama_option' => 'required_with:options|string|max:255',
            'options.*.harga' => 'required_with:options|numeric|min:0',
            'options.*.is_pihak_lain' => 'nullable|boolean',
            'options.*.biaya_pihak_lain' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $validated, $addon) {
            $validated['is_active'] = ($validated['status'] === 'aktif');
            $validated['is_pihak_lain'] = $request->has('is_pihak_lain');
            $validated['harga_default'] = $validated['harga_default'] ?? $validated['price'];

            $addon->update($validated);

            // Sync categories
            if (! empty($validated['category_ids'])) {
                $syncData = [];
                foreach ($validated['category_ids'] as $catId) {
                    $syncData[$catId] = ['status' => 'aktif'];
                }
                $addon->categories()->sync($syncData);
            } else {
                $addon->categories()->detach();
            }

            // Sync options
            $addon->options()->delete();
            if (! empty($validated['options'])) {
                foreach ($validated['options'] as $option) {
                    $addon->options()->create([
                        'nama_option' => $option['nama_option'],
                        'tipe_option' => $option['tipe_option'] ?? null,
                        'harga' => $option['harga'],
                        'is_pihak_lain' => isset($option['is_pihak_lain']) && $option['is_pihak_lain'],
                        'biaya_pihak_lain' => $option['biaya_pihak_lain'] ?? 0,
                        'status' => 'aktif',
                    ]);
                }
            }
        });

        return redirect()->route('owner.addons.index')
            ->with('success', 'Addon berhasil diperbarui.');
    }

    public function destroy(Addon $addon)
    {
        DB::transaction(function () use ($addon) {
            $addon->categories()->detach();
            $addon->options()->delete();
            $addon->delete();
        });

        return redirect()->route('owner.addons.index')
            ->with('success', 'Addon berhasil dihapus.');
    }
}
