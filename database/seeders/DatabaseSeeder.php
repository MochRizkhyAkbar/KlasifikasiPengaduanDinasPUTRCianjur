<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat List Role
        $roles = [
            'admin',
            'bidangSDA',
            'bidangBINKON',
            'bidangTATARUANG',
            'bidangJALAN'
        ];

        foreach ($roles as $roleName) {
            Role::findOrCreate($roleName);
        }

        // 2. Membuat User dan Assign Role Otomatis
        $users = [
            [
                'name'     => 'Admin Utama',
                'email'    => 'admin@example.com',
                'password' => 'password123',
                'role'     => 'admin',
                'status'   => 'Aktif',
            ],
            [
                'name'     => 'User Bidang SDA',
                'email'    => 'sda@example.com',
                'password' => 'password123',
                'role'     => 'bidangSDA',
                'status'   => 'Aktif',
            ],
            [
                'name'     => 'User Bidang BINKON',
                'email'    => 'binkon@example.com',
                'password' => 'password123',
                'role'     => 'bidangBINKON',
                'status'   => 'Aktif',
            ],
            [
                'name'     => 'User Bidang Tata Ruang',
                'email'    => 'tataruang@example.com',
                'password' => 'password123',
                'role'     => 'bidangTATARUANG',
                'status'   => 'Aktif',
            ],
            [
                'name'     => 'User Bidang Jalan',
                'email'    => 'jalan@example.com',
                'password' => 'password123',
                'role'     => 'bidangJALAN',
                'status'   => 'Aktif',
            ],
        ];

        foreach ($users as $userData) {
            // updateOrCreate memastikan user tidak terduplikasi jika sudah ada
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name'     => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'status'   => $userData['status'],
                ]
            );

            // syncRoles memastikan role diperbarui/disinkronkan tanpa error
            $user->syncRoles($userData['role']);
        }
    }
}
