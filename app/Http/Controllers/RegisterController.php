<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        $inviteCode = $request->query('invite', '');

        return view('auth.register', [
            'inviteCode' => $inviteCode
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $invitationCode = InvitationCode::where('code', $validated['invitation_code'])->first();

        if ($invitationCode->usedBy) {
            return back()->withErrors(['invitation_code' => 'This invitation code has already been used.'])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'invitation_code_id' => $invitationCode->id,
        ]);

        auth()->login($user);

        return redirect()->route('dashboard')->with('status', 'Welcome! Your registration was successful.');
    }
}
