<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(20)->create();

        User::factory()->create([
            'name' => 'Usuário de Teste',
            'email' => 'teste@email.com',
            'type' => 'common',
            'balance' => 1000,
        ]);
    }
}
