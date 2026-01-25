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
            ])->get(config('api.base_url') . '/api/pegawai',[
                'per_page' => 100
            ]);

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

    public function inputStepSatu()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'TTE Surat', 'url' => route('input.index')],
            ['title' => 'Input Surat', 'url' => null],
            ['title' => 'Step 1: Input Judul & File', 'url' => null],
        ];

        $apiToken = env('API_STATIC_TOKEN');
        $baseUrl  = config('api.base_url');

        // =======================
        // AMBIL LIST JENIS SURAT
        // =======================
        $jenisSuratResponse = Http::timeout(5)
            ->withToken($apiToken)
            ->get($baseUrl . '/api/modulsurat', [
                'per_page' => 100,
                'is_aktif' => 1
            ]);

        $jenisSuratList = $jenisSuratResponse->successful()
            ? collect($jenisSuratResponse->json('data'))->sortBy('nama')->values()
            : collect();

        // =======================
        // AMBIL LIST PEGAWAI PER ESELON
        // =======================
        $pegawaiPerEselon = [];

        foreach ([4, 3, 2, 1, 0] as $eselon) {
            $response = Http::timeout(5)
                ->withToken($apiToken)
                ->get($baseUrl . '/api/pegawai', [
                    'per_page'         => 100,
                    'tingkat_review'   => $eselon
                ]);

            $pegawaiPerEselon[$eselon] = $response->successful()
                ? collect($response->json('data'))
                    ->sortBy('pegawaiName')
                    ->values()
                : collect();
        }

        // =======================
        // AMBIL LIST PENANDATANGAN
        // =======================
        $response = Http::timeout(5)
            ->withToken($apiToken)
            ->get($baseUrl . '/api/pegawai', [
                'per_page'         => 100,
                'is_penandatangan' => 1, // lebih aman pakai integer
            ]);

        $pegawaiPenandatangan = $response->successful()
            ? collect($response->json('data'))
                ->sortBy('pegawaiName')
                ->values()
            : collect();

        return view('tte.surat.input.form_step_satu', [
            'breadcrumbs'     => $breadcrumbs,
            'jenisSuratList'  => $jenisSuratList,

            // Pegawai per eselon
            'pegawaiEs4List'  => $pegawaiPerEselon[4],
            'pegawaiEs3List'  => $pegawaiPerEselon[3],
            'pegawaiEs2List'  => $pegawaiPerEselon[2],
            'pegawaiEs1List'  => $pegawaiPerEselon[1],
            'pegawaiEs0List'  => $pegawaiPerEselon[0],

            'pegawaiPenandatangan'  => $pegawaiPenandatangan,
        ]);
    }

    public function storeStepSatu(Request $request)
    {
        // =========================
        // VALIDASI FRONTEND (RINGAN)
        // =========================
        $request->validate([
            'name'             => 'required|string|max:255',
            'number'           => 'required|string|max:100',
            'modul_surat_id'   => 'required|integer',
            'file_surat'       => 'required|file|mimes:pdf,docx|max:10240',
            'qr_location_stat' => 'required|in:2,3,4',
            //'qr_location_page' => 'nullable|integer|min:1',
            'tingkat_review'   => 'required|in:4,3,2,1,0',
        ]);
        //dd($request);

        $apiToken = env('API_STATIC_TOKEN');
        $baseUrl  = config('api.base_url');

        // =========================
        // KIRIM KE API
        // =========================
        $response = Http::timeout(10)
            ->withToken($apiToken)
            ->attach(
                'file_surat',
                file_get_contents($request->file('file_surat')->getRealPath()),
                $request->file('file_surat')->getClientOriginalName()
            )
            ->post($baseUrl . '/api/surattte/simpan', [
                'name'             => $request->name,
                'number'           => $request->number,
                'modul_surat_id'   => $request->modul_surat_id,
                'qr_location_stat' => $request->qr_location_stat,
                'qr_location_page' => $request->qr_location_page,
                'tingkat_review'   => $request->tingkat_review,

                'pegawai_es4' => $request->pegawai_es4,
                'pegawai_es3' => $request->pegawai_es3,
                'pegawai_es2' => $request->pegawai_es2,
                'pegawai_es1' => $request->pegawai_es1,
                'pegawai_es0' => $request->pegawai_es0,

                'pegawai_penandatangan' => $request->pegawai_penandatangan,

                'created_by' => auth()->user()->nip_lama,
                'unitCode'   => auth()->user()->pegawai->s_kd_instansiunitorg,
            ]);

        //dd(auth()->user()->nip_lama);
        dd($response->json('message'));

        // =========================
        // RESPONSE HANDLING
        // =========================
        if (! $response->successful()) {
            return back()
                ->withErrors(['api' => $response->json('message') ?? 'Gagal menyimpan surat'])
                ->withInput();
        }

        return redirect()
            ->route('input.success')
            ->with('success', 'Surat TTE berhasil dibuat');
    }
}
