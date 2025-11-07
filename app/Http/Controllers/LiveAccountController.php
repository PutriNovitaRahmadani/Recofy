<?php

namespace App\Http\Controllers;

use App\Models\LiveAccount;
use App\Models\Studio;
use Illuminate\Http\Request;
use App\Services\ShopeeAffiliateScraperService; // 🔹 kita tambahkan service

class LiveAccountController extends Controller
{
    /**
     * Tampilkan daftar akun live.
     */
    public function index()
    {
        $liveAccounts = LiveAccount::with('studio')->get();
        $studios = Studio::all();
        return view('etalase', compact('liveAccounts', 'studios'));
    }

    /**
     * Tampilkan form tambah akun affiliate.
     */
    public function create()
    {
        $studios = Studio::all();
        return view('live_accounts.create', compact('studios'));
    }

    /**
     * Simpan akun affiliate baru.
     */
    public function store(Request $request, ShopeeAffiliateScraperService $scraper)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'studio_id' => 'required|exists:studios,id',
            'cookie_header' => 'required|string',
        ]);

        // simpan dulu
        $liveAccount = LiveAccount::create([
            'name' => $request->name,
            'studio_id' => $request->studio_id,
            'cookie_header' => $request->cookie_header,
        ]);

        // cek cookie valid/tidak
        try {
            $isValid = $scraper->testCookie($request->cookie_header);
            if (!$isValid) {
                return redirect()->back()->with('error', 'Cookie tidak valid atau sudah expired!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cookie tidak valid atau sudah expired!');
        }

        return redirect()->back()->with('success', 'Akun berhasil ditambahkan!');
    }

    /**
     * Edit akun affiliate.
     */
    public function edit($id)
    {
        $liveAccount = LiveAccount::findOrFail($id);
        $studios = Studio::all();
        return view('live_accounts.edit', compact('liveAccount', 'studios'));
    }

    /**
     * Update akun affiliate.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'studio_id' => 'required|exists:studios,id',
            'cookie_header' => 'nullable|string',
        ]);

        $liveAccount = LiveAccount::findOrFail($id);
        $liveAccount->update($request->only(['name', 'studio_id', 'cookie_header']));

        return redirect()->back()->with('success', 'Akun live berhasil diperbarui!');
    }

    /**
     * Hapus akun affiliate.
     */
    public function destroy($id)
    {
        $liveAccount = LiveAccount::findOrFail($id);
        $liveAccount->delete();

        return redirect()->route('live-accounts.index')->with('success', 'Akun live berhasil dihapus!');
    }
}
