<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Friendship;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $toCreate = new Friendship([
            'id' => uuid_create(),
            'sender_user_id' => 'a11a1f8f-da15-42b4-b8a9-e5f51a236883',
            'receiver_user_id' => 'da00ce58-5a4b-4764-99f3-bd0f424f3b62',
            'allowed' => true
        ]);
        $toCreate->save();
    }
}
