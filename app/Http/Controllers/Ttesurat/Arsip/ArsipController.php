<?php

namespace App\Http\Controllers\Ttesurat\Arsip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArsipController extends Controller
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
                'title' => 'Arsip',
                'url'   => route('arsip.index'),
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
                    'mode_data' => 'arsip',
                ]
            );

        if ($response->failed()) {
            return abort(500, 'API Surat TTE tidak dapat diakses');
        }

        //dd($response->json('data'));

        return view('tte.surat.arsip.index', [
            'ttesurat'    => $response->json('data'),
            'meta'        => $response->json('meta'),
            'sort'        => $sort,
            'direction'   => $direction,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function preview($fileId)
    {
        $apiToken = env('API_STATIC_TOKEN');

        $apiUrl = config('api.base_url')
            . '/api/surattte/files/' . $fileId . '/preview/tte';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->withOptions([
            'stream' => true,
            'timeout' => 30,
        ])->get($apiUrl);

        if ($response->failed()) {
            abort(404, 'File tidak ditemukan');
        }

        return new StreamedResponse(function () use ($response) {
            $body = $response->toPsrResponse()->getBody();
            while (! $body->eof()) {
                echo $body->read(1024 * 8);
            }
        }, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ]);
    }
}
