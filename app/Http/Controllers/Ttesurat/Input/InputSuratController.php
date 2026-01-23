<?php

namespace App\Http\Controllers\Ttesurat\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InputSuratController extends Controller
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
                'url' => route('dashboard'),
            ],
            [
                'title' => 'TTE Surat',
                'url' => null, // halaman aktif
            ],
            [
                'title' => 'Input Surat',
                'url' => route('input.index'),
            ],
            [
                'title' => 'List Data Surat Konsep',
                'url' => null, // halaman aktif
            ],
        ];

        $page    = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search  = $request->get('search');
        $year    = $request->get('year');

        $sort      = $request->get('sort', 'created_at'); // default
        $direction = $request->get('direction', 'desc');  // asc | desc

        // 🔥 tentukan creator
        $creator = $request->get('creator');

        // jika creator tidak dikirim & user BUKAN superadmin
        if (!$creator && !auth()->user()->hasRole('superadmin')) {
            $creator = auth()->user()->pegawai_id;
        }

        // Ambil token dari .env (pastikan sama dengan token statik di API)
        $apiToken = env('API_STATIC_TOKEN');

        $response = Http::timeout(5)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
            ])->get(config('api.base_url') . '/api/surattte/konsep',
                [
                    'page' => $page,
                    'per_page' => $perPage,
                    'search'   => $search,
                    'sort'      => $sort,
                    'direction' => $direction,
                    'year'      => $year,
                    'creator'   => $creator
                ]
            );

        if ($response->failed()) {
            return abort(500, 'API Surat TTE tidak dapat diakses');
        }

        // =======================
        // AMBIL LIST PEGAWAI
        // =======================
        $pegawaiResponse = Http::timeout(5)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
            ])->get(config('api.base_url') . '/api/pegawai');

        if ($pegawaiResponse->failed()) {
            $pegawaiList = collect(); // fallback aman
        } else {
            $pegawaiList = collect($pegawaiResponse->json('data'))
                ->sortBy('pegawaiName')
                ->values();
        }

        return view('tte.surat.input.index', [
            'konsepsurat' => $response->json('data'),
            'meta'        => $response->json('meta'),
            'sort'        => $sort,
            'direction'   => $direction,
            'breadcrumbs' => $breadcrumbs,
            'pegawaiList' => $pegawaiList,
        ]);
    }
}
