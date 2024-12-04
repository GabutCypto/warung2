<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Membuat role owner untuk pemilik dapur
        $ownerRole = Role::create([
            'name' => 'owner'
        ]);

        // Membuat role owner untuk pemilik warung
        $warungOwnerRole = Role::create([
            'name' => 'warung_owner'
        ]);

        // Membuat role buyer untuk pembeli
        $buyerRole = Role::create([
            'name' => 'buyer'
        ]);
        
        // Menambahkan pemilik dapur
        $ownerUser = User::create([
            'name' => 'Pemilik Dapur',
            'email' => 'pemilik_dapur@email.com',
            'password' => bcrypt('123123123')
        ]);
        $ownerUser->assignRole($ownerRole);

        // Menambahkan pemilik warung
        $warungOwnerUser = User::create([
            'name' => 'Pemilik Warung',
            'email' => 'pemilik_warung@email.com',
            'password' => bcrypt('123123123')
        ]);
        $warungOwnerUser->assignRole($warungOwnerRole);
    }
}