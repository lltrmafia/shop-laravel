<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = [
            'name' => 'tim',
            'email' => 'timuraniyatov@gmail.com',
            'password' => Hash::make('123123123'),
        ];
        $user = User::firstOrCreate(['email' => $user['email']], $user);
        $role = Role::firstOrCreate(['title' => RoleEnum::ADMIN->value]);

        $user->roles()->sync([$role->id]);
    }
}
