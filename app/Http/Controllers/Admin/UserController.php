<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class UserController extends Controller
{
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

        return view('admin.user.index', [
            'users'     => $response->json('data'),
            'meta'      => $response->json('meta'),
            'sort'      => $sort,
            'direction' => $direction,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function sync(): RedirectResponse
    {
        $total = UserSyncService::syncByNipLama();
        return redirect()
            ->route('users.index')
            ->with('success', "Sinkronisasi berhasil. {$total} data diproses.");
        //return back()->with('success', "Sinkronisasi berhasil: {$total} data diproses");
    }
}
