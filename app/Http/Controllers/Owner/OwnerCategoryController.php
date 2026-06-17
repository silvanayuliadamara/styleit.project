<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OwnerCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::orderBy('sort_order')->get();
        return view('owner.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('owner.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'headline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ServiceCategory::create($validated);

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori layanan berhasil ditambahkan.');
    }

    public function edit(ServiceCategory $category)
    {
        return view('owner.categories.edit', compact('category'));
    }

    public function update(Request $request, ServiceCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'headline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? $category->sort_order;

        $category->update($validated);

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori layanan berhasil diperbarui.');
    }

    public function destroy(ServiceCategory $category)
    {
        if ($category->packages()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Kategori tidak dapat dihapus karena memiliki paket layanan aktif.');
        }

        $category->delete();

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori layanan berhasil dihapus.');
    }
}
