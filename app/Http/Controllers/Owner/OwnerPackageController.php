<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServicePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;

class OwnerPackageController extends Controller
{
    public function index()
    {
        $packages = ServicePackage::with('category')->orderBy('sort_order')->paginate(10);
 
        return view('owner.packages.index', compact('packages'));
    }

    public function create()
    {
        $categories = ServiceCategory::orderBy('sort_order')->get();

        return view('owner.packages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'code' => 'required|string|max:50|unique:service_packages,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'dp_amount' => 'required|numeric|min:0',
            'quota_per_day' => 'required|integer|min:1',
            'is_popular' => 'nullable|boolean',
            'butuh_makeup' => 'nullable|boolean',
            'butuh_baju' => 'nullable|boolean',
            'softlens_wajib_pilih' => 'nullable|boolean',
            'status' => 'required|in:aktif,nonaktif',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',

            // Items
            'items' => 'nullable|array',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit' => 'required_with:items|string|max:50',
            'items.*.is_pihak_lain' => 'nullable|boolean',
            'items.*.biaya_pihak_lain' => 'nullable|numeric|min:0',
            'items.*.keterangan' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $count = 1;
            while (ServicePackage::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $validated['slug'] = $slug;
            $validated['is_popular'] = $request->has('is_popular');
            $validated['butuh_makeup'] = $request->has('butuh_makeup');
            $validated['butuh_baju'] = $request->has('butuh_baju');
            $validated['softlens_wajib_pilih'] = $request->has('softlens_wajib_pilih');

            if ($request->hasFile('image')) {
                $manager = new ImageManager(new Driver());
                $img = $manager->decode($request->file('image'));
                $img->scale(width: 800);
                $encoded = $img->encode(new JpegEncoder(80));

                $filename = 'packages/' . Str::random(40) . '.jpg';
                Storage::disk('public')->put($filename, (string)$encoded);
                $validated['image'] = $filename;
            }

            $package = ServicePackage::create($validated);

            if (! empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    $package->items()->create([
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'],
                        'is_pihak_lain' => isset($item['is_pihak_lain']) && $item['is_pihak_lain'],
                        'biaya_pihak_lain' => $item['biaya_pihak_lain'] ?? 0,
                        'keterangan' => $item['keterangan'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('owner.packages.index')
            ->with('success', 'Paket layanan berhasil ditambahkan.');
    }

    public function edit(ServicePackage $package)
    {
        $categories = ServiceCategory::orderBy('sort_order')->get();
        $package->load('items');

        return view('owner.packages.edit', compact('package', 'categories'));
    }

    public function update(Request $request, ServicePackage $package)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'code' => 'required|string|max:50|unique:service_packages,code,'.$package->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'dp_amount' => 'required|numeric|min:0',
            'quota_per_day' => 'required|integer|min:1',
            'is_popular' => 'nullable|boolean',
            'butuh_makeup' => 'nullable|boolean',
            'butuh_baju' => 'nullable|boolean',
            'softlens_wajib_pilih' => 'nullable|boolean',
            'status' => 'required|in:aktif,nonaktif',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',

            // Items
            'items' => 'nullable|array',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit' => 'required_with:items|string|max:50',
            'items.*.is_pihak_lain' => 'nullable|boolean',
            'items.*.biaya_pihak_lain' => 'nullable|numeric|min:0',
            'items.*.keterangan' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $validated, $package) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $count = 1;
            while (ServicePackage::where('slug', $slug)->where('id', '!=', $package->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $validated['slug'] = $slug;
            $validated['is_popular'] = $request->has('is_popular');
            $validated['butuh_makeup'] = $request->has('butuh_makeup');
            $validated['butuh_baju'] = $request->has('butuh_baju');
            $validated['softlens_wajib_pilih'] = $request->has('softlens_wajib_pilih');

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($package->image) {
                    Storage::disk('public')->delete($package->image);
                }

                $manager = new ImageManager(new Driver());
                $img = $manager->decode($request->file('image'));
                $img->scale(width: 800);
                $encoded = $img->encode(new JpegEncoder(80));

                $filename = 'packages/' . Str::random(40) . '.jpg';
                Storage::disk('public')->put($filename, (string)$encoded);
                $validated['image'] = $filename;
            }

            $package->update($validated);

            // Re-sync package items
            $package->items()->delete();
            if (! empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    $package->items()->create([
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'],
                        'is_pihak_lain' => isset($item['is_pihak_lain']) && $item['is_pihak_lain'],
                        'biaya_pihak_lain' => $item['biaya_pihak_lain'] ?? 0,
                        'keterangan' => $item['keterangan'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('owner.packages.index')
            ->with('success', 'Paket layanan berhasil diperbarui.');
    }

    public function destroy(ServicePackage $package)
    {
        if ($package->bookings()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Paket tidak dapat dihapus karena sudah ada booking yang menggunakan paket ini.');
        }

        DB::transaction(function () use ($package) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $package->items()->delete();
            $package->delete();
        });

        return redirect()->route('owner.packages.index')
            ->with('success', 'Paket layanan berhasil dihapus.');
    }
}
