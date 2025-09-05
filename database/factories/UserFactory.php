<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['store', 'common']);
        $cpfCnpj = '';
        $name = '';

        if ($type === 'store') {
            $cpfCnpj = $this->faker->unique()->cnpj();
            $name = $this->faker->company();
        } else {
            $cpfCnpj = $this->faker->unique()->cpf();
            $name = $this->faker->name();
        }

        return [
            'name' => $name,
            'cpf_cnpj' => $cpfCnpj,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'type' => $type,
            'balance' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
