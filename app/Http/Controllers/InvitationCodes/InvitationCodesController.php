<?php

namespace App\Http\Controllers\InvitationCodes;

use App\Http\Controllers\Controller;
use App\Models\InvitationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvitationCodesController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(InvitationCode $invitationCode)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $invitationCode = InvitationCode::whereDoesntHave('usedBy')->first();

        if (!$invitationCode) {
            $invitationCode = InvitationCode::create([
                'code' => Str::random(10),
            ]);
        }

        $fullUrl = route('register', ['invite' => $invitationCode->code]);

        return view('invitationCodes.show', [
            'code' => $invitationCode->code,
            'fullUrl' => $fullUrl,
        ]);
    }
}
