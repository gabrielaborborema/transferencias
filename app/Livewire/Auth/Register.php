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
    #[Rule('required', message: '*Campo obrigatório')]
    #[Rule('max:255', message: '*Máximo de 255 caracteres')]
    public string $name = '';

    #[Rule('required', message: '*Campo obrigatório')]
    #[Rule('in:store,common', message: '*Tipo de usuário inválido')]
    public string $type = 'common';

    #[Rule('required', message: '*Campo obrigatório')]
    #[Rule('cpf_ou_cnpj', message: '*Valor do CPF/CNPJ inválido')]
    #[Rule('unique:users,cpf_cnpj', message: '*CPF/CNPJ já cadastrado')]
    public string $cpf_cnpj = '';

    #[Rule('required', message: '* Campo obrigatório')]
    #[Rule('max:255', message: '* Máximo de 255 caracteres')]
    #[Rule('email', message: '* Email inválido')]
    #[Rule('unique:users,email', message: '* Email já cadastrado')]
    public string $email = '';

    #[Rule('required', message: '* Campo obrigatório')]
    #[Rule('confirmed', message: '* Senha de confirmação inválida')]
    public string $password = '';

    public string $password_confirmation = '';

    public function register()
    {
        $validated = $this->validate();

        $user = User::create([
            'name' => $validated['name'],
            'cpf_cnpj' => $validated['cpf_cnpj'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => $validated['type'],
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
