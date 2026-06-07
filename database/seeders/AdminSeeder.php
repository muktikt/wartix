<?php
namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name'     => 'Admin Wartix',
            'email'    => 'admin@wartix.id',
            'password' => Hash::make('wartix2024'),
            'role'     => 'superadmin',
        ]);
    }
}