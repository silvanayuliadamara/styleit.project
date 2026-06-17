<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\WhatsappSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappSettingController extends Controller
{
    public function index()
    {
        $setting = WhatsappSetting::first();
        return view('owner.whatsapp.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nomor_makeup_paket' => 'required|string|max:50',
            'nomor_baju' => 'nullable|string|max:50',
            'template_makeup' => 'nullable|string',
            'template_baju' => 'nullable|string',
        ]);

        $setting = WhatsappSetting::first();
        if ($setting) {
            $setting->update(array_merge($validated, ['updated_by' => Auth::id()]));
        } else {
            WhatsappSetting::create(array_merge($validated, ['updated_by' => Auth::id()]));
        }

        return redirect()->route('owner.whatsapp.index')->with('success', 'Pengaturan WhatsApp berhasil disimpan.');
    }
}
