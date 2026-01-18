<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModulSurat;
use App\Services\ModulSuratSyncService;
use App\Services\PegawaiSyncService;
use App\Services\TteSyncService;
use App\Services\UserSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class UserController extends Controller
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
                'title' => 'Manajemen User',
                'url'   => route('users.index'),
            ],
            [
                'title' => 'List Users',
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
            ])->get(config('api.base_url') . '/api/users',
            [
                'page' => $page,
                'per_page' => $perPage,
                'search'   => $search,
                'sort'      => $sort,
                'direction' => $direction,
            ]
        );

        if ($response->failed()) {
            return abort(500, 'API User tidak dapat diakses');
        }

        /*$data = DB::connection('tte_new')
            ->table('users')
            ->limit(10)
            ->get();*/
        //dd($data);

        // Ambil semua modul (id => nama)
        $moduls = ModulSurat::pluck('nama', 'id');

        return view('admin.user.index', [
            'users'       => $response->json('data'),
            'moduls'      => $moduls,
            'meta'        => $response->json('meta'),
            'sort'        => $sort,
            'direction'   => $direction,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function sync(): RedirectResponse
    {
        $total = UserSyncService::syncByNipLama();
        $modul = ModulSuratSyncService::syncByAll();
        $pegawai = PegawaiSyncService::syncByAll();
        $tte = TteSyncService::syncByAll();
        return redirect()
            ->route('users.index')
            ->with('success', "Sinkronisasi berhasil. {$total}-{$modul}-{$pegawai}-{$tte} data diproses.");
        //return back()->with('success', "Sinkronisasi berhasil: {$total} data diproses");
    }

    public function activateViaApi($id)
    {
        $response = $this->api()
            ->patch(config('api.base_url') . "/api/users/{$id}/activate");

        if ($response->failed()) {
            return back()->withErrors('Gagal mengaktifkan user');
        }

        return back()->with('success', 'User berhasil diaktifkan');

    }

    public function deactivateViaApi($id)
    {
        $response = $this->api()
            ->patch(config('api.base_url') . "/api/users/{$id}/deactivate");

        if ($response->failed()) {
            return back()->withErrors('Gagal menonaktifkan user');
        }

        return back()->with('success', 'User berhasil dinonaktifkan');
    }

    public function activateSyncViaApi($id)
    {
        $response = $this->api()
            ->patch(config('api.base_url') . "/api/users/{$id}/activate/sync");

        if ($response->failed()) {
            return back()->withErrors('Gagal mengaktifkan sync user');
        }

        return back()->with('success', 'User Sync berhasil diaktifkan');
    }

    public function deactivateSyncViaApi($id)
    {
        $response = $this->api()
            ->patch(config('api.base_url') . "/api/users/{$id}/deactivate/sync");

        if ($response->failed()) {
            return back()->withErrors('Gagal menonaktifkan sync user');
        }

        return back()->with('success', 'User Sync berhasil dinonaktifkan');
    }

    public function activateUjikomViaApi($id, $value)
    {
        $response = $this->api()
            ->patch(config('api.base_url') . "/api/users/{$id}/activate/modul/{$value}/is_ujikom");

        if ($response->failed()) {
            return back()->withErrors('Gagal ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul ujikom');
        }

        return back()->with('success', 'Berhasil ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul ujikom');
    }

    public function activateSertifikatViaApi($id, $value)
    {
        $response = $this->api()
            ->patch(config('api.base_url') . "/api/users/{$id}/activate/modul/{$value}/is_sertifikat");

        if ($response->failed()) {
            return back()->withErrors('Gagal ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul sertifikat');
        }

        return back()->with('success', 'Berhasil ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul sertifikat');
    }

    public function activateBangkomViaApi($id, $value)
    {
        $response = $this->api()
            ->patch(config('api.base_url') . "/api/users/{$id}/activate/modul/{$value}/is_bangkom");

        if ($response->failed()) {
            return back()->withErrors('Gagal ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul bangkom');
        }

        return back()->with('success', 'Berhasil ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul bangkom');
    }

    public function activateSkpViaApi($id, $value)
    {
        $response = $this->api()
            ->patch(config('api.base_url') . "/api/users/{$id}/activate/modul/{$value}/is_skp");

        if ($response->failed()) {
            return back()->withErrors('Gagal ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul skp');
        }

        return back()->with('success', 'Berhasil ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul skp');
    }

    public function activateBidang3ViaApi($id, $value)
    {
        $response = $this->api()
            ->patch(config('api.base_url') . "/api/users/{$id}/activate/modul/{$value}/is_bidang3");

        if ($response->failed()) {
            return back()->withErrors('Gagal ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul bidang 3');
        }

        return back()->with('success', 'Berhasil ' . (($value == 1) ? 'mengaktifkan' : 'menonaktifkan') . ' modul bidang 3');
    }

    public function updateAksesModulViaApi(Request $request, int $id)
    {
        // akses_modul dikirim dari frontend (string: "1,2,3")
        $payload = [
            'akses_modul' => $request->input('akses_modul'),
        ];

        $response = $this->api()->patch(
            config('api.base_url') . "/api/users/{$id}/akses/modul",
            $payload
        );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal menyimpan akses modul',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Akses modul berhasil diperbarui',
        ]);
    }
}
