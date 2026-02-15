<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'superadmin@email.com',
            'password' => Hash::make('password'),
            'role' => UserRole::SUPERADMIN->value,
        ]);

        Tier::factory()->create([
            'name' => '普通用户',
            'level' => 1,
        ]);

        Tier::factory()->create([
            'name' => '会员',
            'level' => 2,
        ]);
        Tier::factory()->create([
            'name' => '代理',
            'level' => 3,
        ]);
        Tier::factory()->create([
            'name' => '弟子',
            'level' => 4,
        ]);
    }
}
