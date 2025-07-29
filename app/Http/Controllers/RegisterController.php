<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        $inviteCode = $request->query('invite', '');

        return view('auth.register', [
            'inviteCode' => $inviteCode
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'invitation_code' => 'required|string|exists:invitation_code,code',
        ]);

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
