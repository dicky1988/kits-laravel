<?php

namespace App\Http\Controllers\Ttesurat\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
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
                'title' => 'TTE Surat',
                'url'   => null, // halaman aktif
            ],
            [
                'title' => 'Monitoring',
                'url'   => route('monitoring.index'),
            ],
            [
                'title' => 'List Data Surat TTE',
                'url'   => null, // halaman aktif
            ],
        ];

        $page    = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search  = $request->get('search');
        $year    = $request->get('year');

        $sort      = $request->get('sort', 'created_at'); // default
        $direction = $request->get('direction', 'desc');  // asc | desc

        // Ambil token dari .env (pastikan sama dengan token statik di API)
        $apiToken = env('API_STATIC_TOKEN');

        $response = Http::timeout(5)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
            ])->get(config('api.base_url') . '/api/surattte',
                [
                    'page' => $page,
                    'per_page' => $perPage,
                    'search'   => $search,
                    'sort'      => $sort,
                    'direction' => $direction,
                    'year'      => $year,
                    'mode_data' => 'monitoring',
                ]
            );

        if ($response->failed()) {
            return abort(500, 'API Surat TTE tidak dapat diakses');
        }

        return view('tte.surat.monitoring.index', [
            'ttesurat'    => $response->json('data'),
            'meta'        => $response->json('meta'),
            'sort'        => $sort,
            'direction'   => $direction,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function previewPdf($tte_id, $id)
    {
        //dd($tte_id,$id);
        if (!auth()->check()) {
            abort(401);
        }

        // Ambil token dari .env (pastikan sama dengan token statik di API)
        $apiToken = env('API_STATIC_TOKEN');

        try {
            $response = Http::timeout(180)
                ->withToken($apiToken)
                ->get(config('api.base_url') . "/api/surattte/files/{$tte_id}/preview/{$id}/pdf");

            if (! $response->successful()) {
                abort($response->status(), 'File tidak ditemukan');
            }

        } catch (\Throwable $e) {
            logger()->error('API PDF ERROR', [
                'message' => $e->getMessage(),
            ]);
            abort(503, 'Gagal terhubung ke server dokumen');
        }

        return response($response->body(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview.pdf"',
            'Cache-Control'       => 'no-store, no-cache',
        ]);
    }
}
