<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $hasIsAdmin = Schema::hasColumn('users', 'is_admin');

        // Admin
        $data = [
            'name'     => 'Administrador',
            'password' => Hash::make('Admin123*'),
            'role'     => 'admin',
        ];
        if ($hasIsAdmin) {
            $data['is_admin'] = true;
        }

        User::updateOrCreate(
            ['email' => 'admin@procafes.pe'],
            $data
        );

        // 10 clientes
        $nombres = [
            ['Luis', 'García'], ['María', 'Rojas'], ['Carlos', 'Soto'],
            ['Ana', 'Fernández'], ['Ricardo', 'Quispe'], ['Lucía', 'Chávez'],
            ['Jorge', 'Torres'], ['Diana', 'Flores'], ['Pedro', 'Huamán'], ['Rosa', 'Pérez'],
        ];

        foreach ($nombres as $i => [$n, $a]) {
            $data = [
                'name'     => $n.' '.$a,
                'password' => Hash::make('Cliente123*'),
                'role'     => 'customer',
            ];
            if ($hasIsAdmin) {
                $data['is_admin'] = false;
            }

            User::updateOrCreate(
                ['email' => strtolower($n).$i.'@correo.pe'],
                $data
            );
        }
    }
}
