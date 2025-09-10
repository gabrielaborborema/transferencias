<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

#[Layout('components.layouts.app')]
class Register extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|max:18|unique:users,cpf')]
    public string $cpf = '';

    #[Rule('required|string|email|max:255|unique:users,email')]
    public string $email = '';

    #[Rule('required|string|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function register()
    {
        $validated = $this->validate();

        $user = User::create([
            'name' => $validated['name'],
            'cpf_cnpj' => $validated['cpf'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => 'common',
            'balance' => 0,
        ]);

        Auth::login($user);

        return $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
