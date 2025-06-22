<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\RoleMaster;
use App\Models\StatusMaster;
use App\Models\User;
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

        User::create([
            "name"              => "Haziq",
            "email"             =>  "haziq@example.com",
            "email_verified_at" =>  null,
            "password"          =>  "123123123",
            "role_master_id"    =>  1,
            "profile_image"     =>  null,
            "remember_token"    =>  null
        ]);

        $roles = [
            [
                "name"          =>  "Admin",
                "created_by"    =>  1
            ],
            [
                "name"          =>  "User",
                "created_by"    =>  1
            ]
        ];
        foreach ($roles as $roles) {
            RoleMaster::create($roles);
        }


        $status = [
            [
                "name"          =>  "Complete"
            ],
            [
                "name"          =>  "Incomplete"
            ],
            [
                "name"          =>  "Overdue"
            ]
        ];
        foreach ($status as $status) {
            StatusMaster::create($status);
        }
    }
}
