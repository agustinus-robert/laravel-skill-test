<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $test = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        echo "Created User: {$test->name} ({$test->email})\n";

        $users = User::factory(10)->create([
            'password' => Hash::make('password'),
        ]);

        foreach ($users as $user) {
            echo "Created User: {$user->name} ({$user->email})\n";
        }
    }
}
