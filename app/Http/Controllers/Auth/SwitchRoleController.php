<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SwitchRoleController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = auth()->user();

        abort_unless(
            $user->roles->contains('id', $request->role_id),
            403
        );

        $user->update([
            'active_role_id' => $request->role_id
        ]);

        return back();
    }
}
