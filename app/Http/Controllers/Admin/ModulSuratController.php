<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModulSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ModulSuratController extends Controller
{
    private function api()
    {
        return Http::timeout(5)
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('api.token'),
                'Accept'        => 'application/json',
            ]);
    }

    public function index(Request $request)
    {
        // breadcrumb dinamis
        $breadcrumbs = [
            [
                'title' => 'Dashboard',
                'url'   => route('dashboard'),
            ],
            [
                'title' => 'Referensi',
                'url'   => null, // halaman aktif
            ],
            [
                'title' => 'Jenis Surat',
                'url'   => route('modulsurat.index'),
            ],
            [
                'title' => 'List Jenis Surat',
                'url'   => null, // halaman aktif
            ],
        ];

        $page    = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search  = $request->get('search');

        $sort      = $request->get('sort', 'created_at'); // default
        $direction = $request->get('direction', 'desc');  // asc | desc

        // Ambil token dari .env (pastikan sama dengan token statik di API)
        $apiToken = env('API_STATIC_TOKEN');

        $response = Http::timeout(5)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
            ])->get(config('api.base_url') . '/api/modulsurat',
                [
                    'page' => $page,
                    'per_page' => $perPage,
                    'search'   => $search,
                    'sort'      => $sort,
                    'direction' => $direction,
                ]
            );

        if ($response->failed()) {
            return abort(500, 'API Modul Surat tidak dapat diakses');
        }

        return view('admin.referensi.jenissurat.index', [
            'jenissurat'  => $response->json('data'),
            'meta'        => $response->json('meta'),
            'sort'        => $sort,
            'direction'   => $direction,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'  => 'required|string|max:255',
            'icon'  => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        $response = $this->api()->post(
            config('api.base_url') . '/api/modulsurat',
            $validated
        );

        if ($response->failed()) {
            return response()->json([
                'message' => $response->json('message') ?? 'Gagal menyimpan data'
            ], 500);
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $payload = $request->only(['nama', 'icon', 'color']);

        $response = $this->api()->put(
            config('api.base_url') . "/api/modulsurat/{$id}",
            $payload
        );

        if ($response->failed()) {
            return response()->json([
                'message' => $response->json('message') ?? 'Gagal update data'
            ], 500);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $response = $this->api()->delete(
            config('api.base_url') . "/api/modulsurat/{$id}"
        );

        if ($response->failed()) {
            return response()->json(['message' => 'Gagal hapus data'], 500);
        }

        return response()->json(['success' => true]);
    }
}
