<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\InvitationCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminInvite = InvitationCode::firstOrCreate(
            [
                'code' => Str::random(10),
                'expired_at' => now(),
            ],
        );

        $admin = User::firstOrCreate(
            [
                'email' => 'joaopedrorochapinho@gmail.com'
            ],
            [
                'name' => 'admin',
                'role' => 'admin',
                'password' => Hash::make('Teste123'),
                'invitation_code_id' => $adminInvite->id,
            ]
        );

        $regularUserInvite = InvitationCode::firstOrCreate(
            [
                'code' => Str::random(10),
                'expired_at' => now(),
            ],
        );

        $regularUser = User::firstOrCreate(
            [
                'email' => 'user2@gmail.com'
            ],
            [
                'name' => 'Íris',
                'role' => 'regular',
                'password' => Hash::make('Teste123'),
                'invitation_code_id' => $regularUserInvite->id,
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info('Admin users created successfully.');
        } else {
            $this->command->info('Admin users already exists. Seeder skipped.');
        }
    }
}
